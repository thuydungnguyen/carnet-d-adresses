Simple carnet d'adresses
========================
+   Identification (Inscription/Connexion/Déconnexion) de l'utilisateur par nom/mot de passe

+   Ajouter/Lister/Supprimer des contacts de son carnet d'adresses (membres qui pourront aussi devenir utilisateurs)

+   Afficher/Modifier les informations personnelles (e-mail / adresse / téléphone / site web)

reste à faire: 
bug sur les methodes add et remove, vu que c'est une relation reflexive many to many, le comportement par défaut de doctrine supprime tous les entités avant de affectuer l'action (add/remove) 
