<?php

namespace AppBundle\Controller;

use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserInterface;

class UserController extends Controller
{

    public function listAction()
    {
        $me = $this->getUser();
        if (!is_object($me) || !$me instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
        $listContacts = $me->getFriends();
        return $this->render('AppBundle:User:list.html.twig', array('listContacts' => $listContacts));
    }

    public function addAction(Request $request)
    {
        $me = $this->getUser();
        if (!is_object($me) || !$me instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $friends = $me->getFriends();
        $contacts[] = $me;
        foreach($friends as $friend){
            $contacts[] = $friend;
        }

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $me);
        $formBuilder
            ->add('friends', EntityType::class, array(
                'class' => 'AppBundle:User',
                'choice_label' => 'username',
                'multiple'     => true,
                'expanded'     => false,
                'query_builder'=> function(EntityRepository $er) use($contacts){
                    return $er->createQueryBuilder('u')
                        ->where("u.id NOT IN (:contacts)")->setParameter('contacts',$contacts);
                }
            ))
        ;
        $form = $formBuilder->getForm();

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $em = $this->getDoctrine()->getManager();
            foreach($contacts as $old_friend){
                if($old_friend !== $me){
                    $me->addFriend($old_friend);
                }
            }
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Contact ajouté');
            return $this->redirectToRoute('contact_list');
        }

        return $this->render('AppBundle:User:edit.html.twig', array('form'  => $form->createView()));
    }

    public function deleteAction(Request $request)
    {
        $me = $this->getUser();
        if (!is_object($me) || !$me instanceof UserInterface) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }

        $friends = $me->getFriends();

        $contacts = array();
        foreach($friends as $friend){
            $contacts[] = $friend;
        }

        $em = $this->getDoctrine()->getManager();
        global $friends_to_remove;


        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $me);
        $formBuilder
            ->add('friends', EntityType::class, array(
                'class' => 'AppBundle:User',
                'choice_label' => 'username',
                'multiple'     => true,
                'expanded'     => false,
                'query_builder'=> function(EntityRepository $er) use($friends){
                    return $er->createQueryBuilder('u')
                        ->where("u.id IN (:friends)")->setParameter('friends',$friends);
                }
            ))
            ->addEventListener(FormEvents::PRE_SUBMIT, function(FormEvent $event){
                global $friends_to_remove;
                $friends_to_remove = $event->getData()['friends'];
            })
            ->addEventListener(FormEvents::POST_SUBMIT, function(FormEvent $event) use($contacts, $em){
                $me = $event->getData();

                foreach ($contacts as $contact){
                    $me->addFriend($contact);
                }

                global $friends_to_remove;

                foreach($friends_to_remove as $friend_to_remove){
                    $friend_by_adding = $em->getRepository('AppBundle:User')->find($friend_to_remove);
                    $friend_by_form = $em->getRepository('AppBundle:User')->find($friend_to_remove);
                    $me->removeFriend($friend_by_adding);
                    $me->removeFriend($friend_by_form);
                }
            })
        ;
        $form = $formBuilder->getForm();

        if($request->isMethod('POST') && $form->handleRequest($request)->isValid()){
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'Contact supprimé');
            return $this->redirectToRoute('contact_list');
        }

        return $this->render('AppBundle:User:edit.html.twig', array('form'  => $form->createView()));
    }
}