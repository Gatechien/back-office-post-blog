# E07

## Je me trompe dans une des prop d'une entité Doctrine

* je modifie mon entité en corrigeant le nom de la propriété mal nommée. Je n'oublie pas de modifier aussi le setter et le getter !
* je peux lancer cette commande `php bin/console doctrine:schema:validate` pour être sur.e de ne pas me tromper dans la modification de mon entité
* ensuite, je génère le fichier de migration `php bin/console make:migration` 
* enfin, j'exécute la migration `php bin/console doctrine:migrations:migrate
  
  
## HTML/Javascript inline (VANILLA)

onclick est un attribut qu'on peut appliquer sur des balises HTML. Cet attribut accepte du JS inline.
Il déclenche le JS au moment où on clique sur la balise.

```HTML
<a onclick="return confirm(' Supprimer l\'article? ')" href="/delete/8">Supprimer</a>

````
Dans cet exemple, on exécute la fonction confirm avec un texte. 

* Si l'utilisateur clique sur OK, le confirm va renvoyer true, et le lien href sera activé.
* Si l'utilisateur clique sur Cancel, le confirm va renvoyer false, et le lien href ne sera pas activé.

##  Les entités :

### Entité Author : création

```bash 

teacher@lemaire-marion-oclock-teacher:/var/www/html/symfony/S02E05-symfo-challenge-doctrine-blog-Marion-Oclock$ php bin/console make:entity

 Class name of the entity to create or update (e.g. BravePizza):
 > Author

 created: src/Entity/Author.php
 created: src/Repository/AuthorRepository.php
 
 Entity generated! Now let's add some fields!
 You can always add more fields later manually or by re-running this command.

 New property name (press <return> to stop adding fields):
 > lastname

 Field type (enter ? to see all types) [string]:
 > 

 Field length [255]:
 > 100

 Can this field be null in the database (nullable) (yes/no) [no]:
 > 

 updated: src/Entity/Author.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > firstname

 Field type (enter ? to see all types) [string]:
 > 

 Field length [255]:
 > 100

 Can this field be null in the database (nullable) (yes/no) [no]:
 > 

 updated: src/Entity/Author.php
 ```

### Entité Author : ajout de props

 ```bash
 teacher@lemaire-marion-oclock-teacher:/var/www/html/symfony/S02E05-symfo-challenge-doctrine-blog-Marion-Oclock$ php bin/console make:entity

 Class name of the entity to create or update (e.g. OrangePizza):
 > Author

 Your entity already exists! So let's add some new fields!

 New property name (press <return> to stop adding fields):
 > createdAt

 Field type (enter ? to see all types) [datetime_immutable]:
 > 

 Can this field be null in the database (nullable) (yes/no) [no]:
 > no

 updated: src/Entity/Author.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > updatedAt

 Field type (enter ? to see all types) [datetime_immutable]:
 > 

 Can this field be null in the database (nullable) (yes/no) [no]:
 > yes

 updated: src/Entity/Author.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > 
 ```

 ### Entité Post : ajout de sa relation avec Author

 ```bash

teacher@lemaire-marion-oclock-teacher:/var/www/html/symfony/S02E05-symfo-challenge-doctrine-blog-Marion-Oclock$ p
hp bin/console make:entity

 Class name of the entity to create or update (e.g. GentleChef):
 > Post

 Your entity already exists! So let's add some new fields!

 New property name (press <return> to stop adding fields):
 > author

 Field type (enter ? to see all types) [string]:
 > relation

 What class should this entity be related to?:
 > Author

What type of relationship is this?
 ------------ ------------------------------------------------------------------ 
  Type         Description                                                       
 ------------ ------------------------------------------------------------------ 
  ManyToOne    Each Post relates to (has) one Author.                            
               Each Author can relate to (can have) many Post objects            
                                                                                 
  OneToMany    Each Post can relate to (can have) many Author objects.           
               Each Author relates to (has) one Post                             
                                                                                 
  ManyToMany   Each Post can relate to (can have) many Author objects.           
               Each Author can also relate to (can also have) many Post objects  
                                                                                 
  OneToOne     Each Post relates to (has) exactly one Author.                    
               Each Author also relates to (has) exactly one Post.               
 ------------ ------------------------------------------------------------------ 

 Relation type? [ManyToOne, OneToMany, ManyToMany, OneToOne]:
 > ManyToOne

 Is the Post.author property allowed to be null (nullable)? (yes/no) [yes]:
 > no

 Do you want to add a new property to Author so that you can access/update Post objects from it - e.g. $author->getPosts()? (yes/no) [yes]:
 > 

 A new property will also be added to the Author class so that you can access the related Post objects from it.

 New field name inside Author [posts]:
 > 

 Do you want to activate orphanRemoval on your relationship?
 A Post is "orphaned" when it is removed from its related Author.
 e.g. $author->removePost($post)
 
 NOTE: If a Post may *change* from one Author to another, answer "no".

 Do you want to automatically delete orphaned App\Entity\Post objects (orphanRemoval)? (yes/no) [no]:
 > yes

 updated: src/Entity/Post.php
 updated: src/Entity/Author.php

 ```

 ```bash
php bin/console make:entity

 Class name of the entity to create or update (e.g. OrangeGnome):
 > Tag

 created: src/Entity/Tag.php
 created: src/Repository/TagRepository.php
 
 Entity generated! Now let's add some fields!
 You can always add more fields later manually or by re-running this command.

 New property name (press <return> to stop adding fields):
 > name

 Field type (enter ? to see all types) [string]:
 > 

 Field length [255]:
 > 100

 Can this field be null in the database (nullable) (yes/no) [no]:
 > 

 updated: src/Entity/Tag.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > 


           
  Success! 
           

 Next: When you're ready, create a migration with php bin/console make:migration
 ```

 ```bash
php bin/console make:entity

 Class name of the entity to create or update (e.g. GrumpyElephant):
 > Post

 Your entity already exists! So let's add some new fields!

 New property name (press <return> to stop adding fields):
 > tags

 Field type (enter ? to see all types) [string]:
 > relation

 What class should this entity be related to?:
 > Tag

What type of relationship is this?
 ------------ --------------------------------------------------------------- 
  Type         Description                                                    
 ------------ --------------------------------------------------------------- 
  ManyToOne    Each Post relates to (has) one Tag.                            
               Each Tag can relate to (can have) many Post objects            
                                                                              
  OneToMany    Each Post can relate to (can have) many Tag objects.           
               Each Tag relates to (has) one Post                             
                                                                              
  ManyToMany   Each Post can relate to (can have) many Tag objects.           
               Each Tag can also relate to (can also have) many Post objects  
                                                                              
  OneToOne     Each Post relates to (has) exactly one Tag.                    
               Each Tag also relates to (has) exactly one Post.               
 ------------ --------------------------------------------------------------- 

 Relation type? [ManyToOne, OneToMany, ManyToMany, OneToOne]:
 > ManyToMany

 Do you want to add a new property to Tag so that you can access/update Post objects from it - e.g. $tag->getPosts()? (yes/no) [yes]:
 > 

 A new property will also be added to the Tag class so that you can access the related Post objects from it.

 New field name inside Tag [posts]:
 > 

 updated: src/Entity/Post.php
 updated: src/Entity/Tag.php

 Add another property? Enter the property name (or press <return> to stop adding fields):
 > 


           
  Success! 
           

 Next: When you're ready, create a migration with php bin/console make:migration
 ```


`php bin/console doctrine:schema:validate`

et si pb : 

`php bin/console doctrine:schema:update --force`