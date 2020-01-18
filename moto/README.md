moto
====

A Symfony project created on May 25, 2018, 5:12 pm.


Vous devez déziper le répertoire sur le MAC puis suivre les étapes suivantes :

- Ouvrir un terminal


- Se placer dans le répertoire moto
Quand vous avez fait cela , vous devez faire afin de lancer le projet:

- php bin/console doctrine:database:create


- php bin/console doctrine:schema:update --force
 

- php bin/console assets:install


- php bin/console server:run

Puis, on lance le navigateur (de préférence Google Chrome)


http://localhost:8000


Sur ce site Web, nous pouvons :


- s'authentifier tout en respectant le fait que 2 utilisiteurs ne peuvent pas avoir le même nom d'utilisateur par exemple


- se déconnecter/désinscrire


- avoir(nous avons) profil utilisateur avec son nom d'utilisateur, sa date de naissance, son email et ses amis et il peut bien sûr éditer ses informations et même changer la photo de profil utilisée par défaut

- envoyer des messages à un autre utilisateur ajouté à la liste d'amis qui les recevera par message privé


- évaluer le message d'un utilisateur en répondant tout simplement à son commentaire


- savoir le nombre de message envoyés sur le site par un utilisateur


- avoir un calendrier avec une gestion des évenements (cela contient la date de l'évnement, son lieu et sa description)


- partager des ressources entre utilisateurs (images, fichiers)
