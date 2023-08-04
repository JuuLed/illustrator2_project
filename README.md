# Illustrator2

## Description

Un gestionnaire de symboles avancé pour le configurateur de gobelets V2.
Il permet de créer, modifier, supprimer et gérer facilement les symboles (illustrations) utilisés pour personnaliser les gobelets.


## Prérequis

Avant de pouvoir utiliser ou contribuer à ce projet, vous devrez installer et configurer certains outils sur votre machine locale.

Docker et Docker Compose : 
Docker est un outil qui permet de containeriser et d'exécuter des applications dans un environnement isolé. Docker Compose est un outil qui permet de définir et de gérer des applications multi-conteneurs avec Docker. Vous pouvez les installer avec les instructions ci-dessous ou les télécharger depuis [le site officiel de Docker](https://www.docker.com/products/docker-desktop/).

Sur Mac et Windows :

- Téléchargez et installez [Docker Desktop](https://www.docker.com/products/docker-desktop/).

Sur Linux :

- Mettez à jour votre liste de paquets :

	```sudo apt-get update```

- Installez Docker :

	```sudo apt-get install docker-ce```
	
- Vérifiez que Docker est bien installé :

	```docker --version```

- Téléchargez et installez Docker Compose :

	```bash
	sudo curl -L "https://github.com/docker/compose/releases/download/1.27.4/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
	sudo chmod +x /usr/local/bin/docker-compose

- Vérifiez que Docker Compose est bien installé :

	```docker-compose --version```


Composer : 
Composer est un outil de gestion des dépendances pour PHP. Vous pouvez l'installer avec les instructions ci-dessous ou le télécharger depuis [le site officiel de Composer](https://getcomposer.org/download/).

	
	curl -sS https://getcomposer.org/installer | php
	mv composer.phar /usr/local/bin/composer
	composer --version


## Installation

1. Clonez ce dépôt sur votre machine locale :

	```git clone https://github.com/JuuLed/illustrator2_project.git```


2. Accédez au répertoire du projet :

	```cd illustrator2_project```

3. DOCKER :

4. Pour passer de local à docker ou inversement, commentez/décommentez ces lignes dans ces fichiers :
	- BACK :
		- config/config.php : ligne 3 à 13
		- index.php	 : ligne 34 à 37
	- FRONT :
		- js/api.js	 : ligne 4 à 7

5. Configurez les variables du fichier back/config/config.php selon vos besoins :
	- le chemin d'upload des symboles
	- la clé secrète du token
	- les configurations d'accès à la BDD et les noms de ses tables
		- (s'il y a modification du nom de la BDD ou des tables, il faut aussi modifier le fichier back/config/init.sql)

6. Lancer Docker/Container :
	- Ouvrir terminal/console
	- Naviguez jusqu'à la racine du projet (là où se trouve docker-compose.yml)
	- Lancez la commande : 
		- ```docker-compose up --build```
		
		et laissez docker travailler 2 minutes.

7. Accédez aux aperçus :
	- front 
		- http://localhost/index.php
	- back
		- http://localhost:8000/index.php
	- phpMyAdmin :
		- http://localhost:8081/


Si une erreur persiste sur la BDD (erreur lors du premier démarrage du conteneur MariaDB), ouvrez un terminal/console et entrez :

```docker-compose down --volumes```

puis :

```docker-compose up```


## Documentation API

La documentation de l'API pour ce projet est disponible dans le fichier :
- [`docs/illustrator2-1.0.0-resolved.json`](docs/swagger/JLED44_1-illustrator2-1.0.0-resolved.json)

ou sur le lien :
- https://app.swaggerhub.com/apis-docs/JLED44_1/illustrator2/1.0.0

ou en cliquant sur ce badge :

[![Swagger](https://img.shields.io/badge/-Swagger-%23Clover)](https://app.swaggerhub.com/apis-docs/JLED44_1/illustrator2/1.0.0)


## Tests

- Naviguez jusqu'au dossier back/tests/ à l'aide du terminal :
	- ```cd .\back\tests\```

- puis lancez, toujours dans le terminal : ..\vendor\bin\phpunit + LeNomDuFichierTest.php.
exemple :
    - ```..\vendor\bin\phpunit UserControllerTest.php```


