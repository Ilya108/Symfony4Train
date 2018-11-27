# Symfony4Train
Un projet de test sur Symfony 4
## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. See deployment for notes on how to deploy the project on a live system.

### Prérequis:
Environnement de dev conseillé:  
Ubuntu 18.04

Pour pouvoir lancer le projet il faut avoir PHP 7.1 et Composer installé sur l'ordinateur.

Installation des dépendences PHP:

```
apt-get update -y
apt-get install php7.2 php7.2-mbstring php7.2-xml php7.2-curl php7.2-zip php7.2-pgsql php-intl git -y
```
Installation du Composer:
```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === '93b54496392c062774670ac18b134c3b3a95e5a5e5c8f1a9f115f203b75bf9a129d5daa8ba6a13e2cc8a1da0806388a8') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

### Initialisation du projet

Récupération des sources du projet sur le serveur Git

```
git clone https://github.com/Ilya108/Symfony4Train.git
cd Symfony4Train
```

Installation des dépendences du projet avec Composer

```
php bin/composer.phar install

```

Démarrage du serveur web intégré à Symfony en local sur le port 8000

```
php bin/console server:run 127.0.0.1:8000

```

### PHPUnit

Outil de tests unitaires situés dans le dossier tests
[Plus de détails...](https://symfony.com/doc/current/testing.html)

```
php ./vendor/bin/simple-phpunit

```

