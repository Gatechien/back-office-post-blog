# Récap de la journée 6

## notes en vrac

> Dans la vraie vie, vous aurez un user qui vous sera fourni par l'admin de BDD, ou votre hébergeur.

> Pour corriger l'heure dans son Linux :
> `sudo timedatectl set-timezone Europe/Paris`

## Plan d'action pour installer la base de données et créer des entités

Donc dans l'ordre :

* .env.local : je donne la chaine de connexion
* d:d:c : je crée ma BDD (ça teste la connexion au passage)
* make:entity : je crée mes entités (mapping)
* ma:mi : je crée mon fichier de migration (je peux avoir besoin de le modifier)
* d:m:m : j'exécute mes migrations pour créer mes tables dans la BDD
  
> je suis prêt à développer mes controller / templates

En cas de doute :

* `php bin/console make:entity --regenerate`
* `php bin/console doctrine:schema:validate`

## Documentation vers les flashbags

https://symfony.com/doc/5.4/components/http_foundation/sessions.html#flash-messages
https://symfony.com/doc/5.4/controller.html#flash-messages

* le schéma de JB <3

https://whimsical.com/flash-message-7SuXzqyS2hfRCxRM9abE1J

## Exemple concret pour créer une relation entre deux entités Doctrine

Je veux créer une relation entre deux entités suivant mon MCD.

Je vais donc détailler à Doctrine ce que je veux.

Pour les relations, la seule chose qui nous intéresse dans le MCD, c'est la cardinalité MAX, le 0 ou 1 de la cardinalité MIN est là pour l'option de nullité.

Je pars de mon MCD et je note :

* N de mon coté, et 1 de l'autre >
  __ManyToOne__
  
* 1 de mon coté, et N de l'autre >
  __OneToMany__
  
* N de mon coté, et N de l'autre >
  __ManyToMany__

Ce qui est important pour Doctrine c'est celui qui porte la relation : mappedBy OU inversedBy

### ManyToOne

Je suis le porteur de la relation, c'est moi qui dans la base contient la FK.
Dans le code, je doit avoir :

```php
/* dans la classe Post
* @ORM\ManyToOne(targetEntity=Author::class, inversedBy="posts")
*/
 private $author;
 ```

J'ai donc une propriété dans ma classe porteuse avec un objet de la classe correspondante (dans l'exemple Author)
Je doit trouver un inversedBy

### OneToMany

Je NE suis PAS le porteur de la relation, c'est l'autre qui dans la base contient la FK.
Dans le code, je doit avoir :

```php
/** dans la classe Author
 * @ORM\OneToMany(targetEntity=Post::class, mappedBy="author")
 */
private $posts;
```

J'ai donc une propriété dans ma classe avec un ArrayCollection qui contient toutes les instances des objets liés (dans l'exemple Post)
Je doit trouver un mappedBy

### ManyToMany

Aucune des deux tables ne porte de FK, il y a une table pivot.
Dans le code je doit avoir :

```php
/** dans la classe Tag
 * @ORM\ManyToMany(targetEntity=Post::class, mappedBy="tags")
 */
private $posts;
```

```php
/** dans la classe Post
* @ORM\ManyToMany(targetEntity=Tag::class, inversedBy="posts")
*/
private $tags;
```

Mais ?? pourquoi on a quand même mappedBy OU inversedBy ?

Il faut quand même donner à Doctrine qui des deux entités est l'entité porteuse, celle qui est la plus logique, à vous de décider suivant le cas.
L'idée est que l'on veux avoir la collection d'entité depuis l'une plutôt que depuis l'autre.

Dans notre exemple, un Post est notre objet porteur car on affichera les tags dans la page du post, et pas l'inverse.
Donc on doit avoir inversedBy dans notre classe Post
