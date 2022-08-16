## Challenge Intro à Doctrine

Refaire les manips vues ce jour (depuis un projet Symfo vierge `website-skeleton`) :

=> [Fiche récap' Kourou](https://kourou.oclock.io/ressources/fiche-recap/s2j1-doctrine/)

- Configuration/Création Une BDD _blog_ à la mano.
- Création d'une entité _Post_, exemple de propriétés pour commencer :
    - `title`, `body`, `nbLikes`, `publishedAt`, `createdAt`, `updatedAt`.
- Ajout des informations de mapping.
- Créer une migration.
- Appliquer la migration.
-  Créer cinq routes spécifiques pour :
   - Lire tous les Posts
   - Créer un Post.
   - **Bonus** : Lire un Post via son id
   - **Bonus** : Mettre à jour (mettez à jour la propriété `$updatedAt` ou `$nbLikes` par ex.).
   - **Bonus** : Supprimer un Post.
  
=> N'oubliez pas les redirections si nécessaire :wink:

=> [Documentation Symfony](http://symfony.com/doc/current/doctrine.html)

### Bonus (facultatif :nerd_face:) ajouter des vues Twig

Créer un début d'interface d'admin permettant de :

- Lister les enregistrements.
- Afficher un enregistrement.
- Ajouter (via un formulaire HTML simple, ne gérez pas forcément les erreurs, nous verrons comment intégrer cela avec les Forms Symfony de manière automatique). Voir l'objet Request : https://symfony.com/doc/current/controller.html#the-request-and-response-object
- Supprimer (optionnel : avec un JS `confirm()` au clic/submit).
- Ajouter une navigation pratique et les messages flash qui vont bien.
- Le tout avec Bootstrap histoire que ce soit un minimum joli, titres, tableau, boutons :wink:

### Bonus _lectures_

- [Conversion de paramètre automatique](https://symfony.com/doc/current/doctrine.html#automatically-fetching-objects-paramconverter)
- [Créer de fausses données (fixtures) **spoiler** pour la suite](https://symfony.com/bundles/DoctrineFixturesBundle/current/index.html)
