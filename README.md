# Illustrator2

## Description

Un gestionnaire de symboles avancé pour le configurateur de gobelets V2.
Il permet de créer, modifier, supprimer et gérer facilement les symboles (illustrations) utilisés pour personnaliser les gobelets.

## Installation

1. Clonez ce dépôt sur votre machine locale :

	```git clone https://github.com/JuuLed/illustrator2_project.git```


2. Accédez au répertoire du projet :

	```cd illustrator2_project```

3. DOCKER :

4. Pour passer de local à docker ou inversement, commentez/décommentez ces lignes dans ces fichiers :
	- BACK :
		- config/config.php : ligne 7 à 10
		- index.php	 : ligne 28 à 32
	- FRONT :
		- js/api.js	 : ligne 3 à 7

5. Configurez les variables du fichier back/config/config.php selon vos besoins :
	- le chemin d'upload des symboles
	- la clé secrète du token
	- les configurations d'accès à la BDD et les noms de ses tables
		- (s'il y a modification du nom de la BDD ou des tables, il faut aussi modifier le fichier back/config/init.sql)

6. Lancer Docker/Container :
	- Ouvrir terminal/console
	- Naviguez jusqu'à la racine du projet (là où se trouve docker-compose.yml)
	- Lancez la commande : 
		```docker-compose up --build```
		
		et laissez docker travailler 2 minutes.

7. Accédez aux aperçus :
	- front 
		- http://localhost
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
- [`docs/illustrator2-1.0.0-resolved.json`](docs/illustrator2-1.0.0-resolved.json).

ou sur le lien :
- https://app.swaggerhub.com/apis-docs/JLED44_1/illustrator2/1.0.0.

## Tests

Si vous avez des tests, expliquez ici comment les exécuter.

