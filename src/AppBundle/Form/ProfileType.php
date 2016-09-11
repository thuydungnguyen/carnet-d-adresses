<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('tel', TextType::class, array('label' => 'form.website', 'translation_domain' => 'FOSUserBundle'))
            ->add('website', TextType::class, array('label' => 'form.website', 'translation_domain' => 'FOSUserBundle'))
            ->add('adresse', TextType::class, array('label' => 'form.adresse', 'translation_domain' => 'FOSUserBundle'))
            ->remove('username')
        ;
    }

    public function getParent()
    {
        return 'FOS\UserBundle\Form\Type\ProfileFormType';
    }

    public function getBlockPrefix()
    {
        return 'app_user_profile';
    }

    public function getName()
    {
        return $this->getBlockPrefix();
    }
}