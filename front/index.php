<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Mon application</title>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" 
		integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" 
		crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="icon" href="public/logo-rubicode.png" type="image/x-icon">
	<link rel="stylesheet" href="css/style.css">
</head>
<?
$currentPage = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<body>
	<div class="navbar">
		<div class="navbar-brand">
			<a class="navbar-logo" href="index.php">Illustrateur V2</a>
		</div>
		<ul class="navbar-menu">
			<li class="navbar-item">
				<a class="navbar-link <?= $currentPage === 'home' ? 'active' : ""; ?>"
					href="index.php?page=home">Accueil</a>
			</li>
			<li class="navbar-item">
				<a class="navbar-link <?= $currentPage === 'symbols' ? 'active' : ""; ?>"
					href="index.php?page=symbols">Symboles</a>
			</li>
			<li class="navbar-item">
				<a class="navbar-link <?= $currentPage === 'category' ? 'active' : ""; ?>"
					href="index.php?page=category">Category</a>
			</li>
			<li class="navbar-item">
				<a class="navbar-link <?= $currentPage === 'keyword' ? 'active' : ""; ?>"
					href="index.php?page=keyword">Keyword</a>
			</li>
			<li class="navbar-item">
				<a class="navbar-link <?= $currentPage === 'upload' ? 'active' : ""; ?>"
					href="index.php?page=upload">Uploader</a>
			</li>
			<li class="navbar-item">
				<a class="navbar-link <?= $currentPage === 'stats' ? 'active' : ""; ?>"
					href="index.php?page=stats">Statistiques</a>
			</li>
			<li class="navbar-item">
				<a class="navbar-link <?= $currentPage === 'login' ? 'active' : ""; ?>"
					href="index.php?page=login">Connexion</a>
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