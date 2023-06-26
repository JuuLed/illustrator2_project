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
	<link rel="stylesheet" href="css/index.css">
	<script src="js/api.js"></script>
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
				<a class="navbar-link <?= $currentPage === 'home' ? 'active' : ""; ?>" href="index.php?page=home">
					Accueil
				</a>
			</li>



			<li class="navbar-item">
				<a class="navbar-link <?= ($currentPage === 'symbols' || $currentPage === 'upload' || $currentPage === 'stats') ? 'active' : ''; ?>" href="index.php?page=symbols">
					Symboles
				</a>
				<ul class="submenu">
					<li class="navbar-item">
						<a class="navbar-link <?= $currentPage === 'symbols' ? 'active' : ""; ?>" href="index.php?page=symbols">
							Liste
						</a>
					</li>
					<li class="navbar-item">
						<a class="navbar-link <?= $currentPage === 'upload' ? 'active' : ""; ?>" href="index.php?page=upload">
							Ajouter (upload)
						</a>
					</li>
					<li class="navbar-item">
						<a class="navbar-link <?= $currentPage === 'stats' ? 'active' : ""; ?>" href="index.php?page=stats">
							Statistiques
						</a>
					</li>
					<li class="navbar-item">
						<a class="navbar-link <?= $currentPage === 'stats' ? 'active' : ""; ?>" href="index.php?page=stats">
							Archives
						</a>
					</li>
				</ul>
			</li>

			<li class="navbar-item">
				<a class="navbar-link <?= $currentPage === 'categories' ? 'active' : ""; ?>" href="index.php?page=categories">
					Categories
				</a>
			</li>
			<li class="navbar-item">
				<a class="navbar-link <?= $currentPage === 'keywords' ? 'active' : ""; ?>" href="index.php?page=keywords">
					Mots-clés
				</a>
			</li>
			<li class="navbar-item">
				<a class="navbar-link <?= $currentPage === 'keyword' ? 'active' : ""; ?>" href="index.php?page=keyword">
					Parametrages
				</a>
			</li>
			
			<div class="gradient-line"></div>

			<li class="navbar-item">
				<a class="navbar-link <?= $currentPage === 'login' ? 'active' : ""; ?>" href="index.php?page=login">
					Connexion
				</a>
			</li>
			<li class="navbar-item">
				<a class="navbar-link <?= $currentPage === 'login' ? 'active' : ""; ?>" href="index.php?page=login">
					Enregistrement
				</a>
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

	<!-- <script src="js/api.js"></script> -->

</body>

</html>