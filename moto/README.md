moto
====

A Symfony project created on May 25, 2018, 5:12 pm.


Vous devez déziper le répertoire sur le MAC puis suivre les étapes suivantes :

- Ouvrir un terminal

- Se placer dans le répertoire moto

Quand vous avez fait cela , vous devez  faire afin de lancer le projet:

php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console assets:install
php bin/console server:run 

Puis, on lance le navigateur (de préférence Google Chrome)

http://localhost:8000
