<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mon application</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="navbar">
		<div class="navbar-brand">
			<a class="navbar-logo" href="index.php">Illustrateur V2</a>
		</div>
		<ul class="navbar-menu">
			<li class="navbar-item">
				<a class="navbar-link" href="index.php?page=home">Accueil</a>
			</li>
			<li class="navbar-item">
				<a class="navbar-link" href="index.php?page=symbols">Symboles</a>
			</li>
			<li class="navbar-item">
				<a class="navbar-link" href="index.php?page=category">Category</a>
			</li>
			<li class="navbar-item">
				<a class="navbar-link" href="index.php?page=keyword">Keyword</a>
			</li>
			<li class="navbar-item">
				<a class="navbar-link" href="index.php?page=upload">Uploader</a>
			</li>
			<li class="navbar-item">
				<a class="navbar-link" href="index.php?page=stats">Statistiques</a>
			</li>
			<li class="navbar-item">
				<a class="navbar-link" href="index.php?page=login">Connexion</a>
			</li>
		</ul>
    </div>

    <div class="content">
        <?php
        // Routeur pour gérer les différentes pages
        $page = isset($_GET['page']) ? $_GET['page'] : 'home';

        // Inclusion de la page correspondante
        include_once 'views/' . $page . '.php';
        ?>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
