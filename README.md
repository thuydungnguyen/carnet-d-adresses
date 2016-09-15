Simple carnet d'adresses
========================
+  Identification (Inscription/Connexion/Déconnexion) de l'utilisateur par nom/mot de passe

+   Afficher/Modifier les informations personnelles (e-mail / adresse / téléphone / site web)

+   Ajouter/Lister/Supprimer des contacts de ton carnet d'adresses (comme les contacts dans ton portable en fait)

========================

+ Pour les premiers deux points, j'ai utilisé FOSUserBundle.

+ Pour le troisième point, j'ai utilisé une association many to many self referencing et eventListener dans le formulaire.  


