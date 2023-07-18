# Illustrator2

## Description

Un gestionnaire de symboles avancé pour le configurateur de gobelets V2. Permet de créer, modifier, supprimer et gérer facilement les symboles (illustrations) utilisés pour personnaliser les gobelets

## Installation

1. Clonez ce dépôt sur votre machine locale :

	```git clone https://github.com/JuuLed/illustrator2_project.git```


2. Accédez au répertoire du projet :

	```cd illustrator2_project```

3. DOCKER :

1. Pour passé de local a docker ou inversement, commenté/décommenté ces lignes dans ces fichiers :
	BACK :
		config/config.php : ligne 7 à 10
		index.php	 : ligne 28 à 32
	FRONT :
		js/api.js	 : ligne 3 à 7

2. configurer le fichier back/config/config.php selon vos besoins:
	- le chemin de upload des symboles
	- la clé secréte du token
	- les configuration d'accés a la BDD et le noms des ces tables
		!!! --> si il y a modification du nom de la BDD ou des tables, il faut aussi modifier le fichier init.sql !

3. Lancer Docker/Container :
	- Ouvrir terminal/console
	- Navigué jusqu'à la racine du projet (là où il y a docker-compose.yml)
	- Lancé la commande : 
		docker-compose up --build
		et laissé docker travailler 2 minutes.

4. Accés aux aperçus :
	front 
		http://localhost
	back
		http://localhost:8000/index.php/symbols
	phpMyAdmin :
		http://localhost:8081/


Si une erreur persiste sur la BDD (erreur lors du premier démarrage du conteneur MariaDB), ouvrir terminal/console et entrée:
	- docker-compose down --volumes
puis :
	- docker-compose up

## Documentation API

La documentation de l'API pour ce projet est disponible dans le fichier [`docs/illustrator2-1.0.0-resolved.json`](docs/illustrator2-1.0.0-resolved.json).
ou https://app.swaggerhub.com/apis-docs/JLED44_1/illustrator2/1.0.0.

## Tests

Si vous avez des tests, expliquez ici comment les exécuter.

