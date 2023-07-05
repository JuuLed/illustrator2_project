<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Illustrateur V2</title>
	<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
		integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
		crossorigin="anonymous" referrerpolicy="no-referrer" />
	<link rel="icon" href="public/logo-rubicode.png" type="image/x-icon">
	<link rel="stylesheet" href="css/index.css">
	<script src="js/api.js"></script>
	<script src="js/auth.js"></script>
</head>
<?
// Liste des pages autorisées
$allowedPages = ['symbols', 'upload', 'categories', 'keywords', 'login', 'register'];

$currentPage = isset($_GET['page']) ? $_GET['page'] : 'login';

if(!in_array($currentPage, $allowedPages)) {
    // La page demandée n'est pas dans la liste des pages autorisées
    // Vous pouvez rediriger l'utilisateur vers une page 404 ou vers la page d'accueil
    header('Location: index.php?page=symbols');
    exit;
}
?>

<body>
	<div class="navbar">
		<div class="navbar-brand">
			<img class="rubicode" src="public/logo-rubicode.png">
			<a class="navbar-logo" href="index.php">Illustrateur V2</a>
		</div>
		<ul class="navbar-menu">
			<div>

				<li class="navbar-item">
					<a class="navbar-link <?= ($currentPage === 'symbols' || $currentPage === 'upload' || $currentPage === 'stats') ? 'active' : ''; ?>"
						href="index.php?page=symbols">
						Symboles
					</a>
					<ul class="submenu">
						<li class="navbar-item">
							<a class="navbar-link <?= $currentPage === 'symbols' ? 'active' : ""; ?>"
								href="index.php?page=symbols">
								Liste
							</a>
						</li>
						<li class="navbar-item">
							<a class="navbar-link <?= $currentPage === 'upload' ? 'active' : ""; ?>"
								href="index.php?page=upload">
								Ajouter (upload)
							</a>
						</li>
					</ul>
				</li>

				<li class="navbar-item">
					<a class="navbar-link <?= $currentPage === 'categories' ? 'active' : ""; ?>"
						href="index.php?page=categories">
						Categories
					</a>
				</li>
				<li class="navbar-item">
					<a class="navbar-link <?= $currentPage === 'keywords' ? 'active' : ""; ?>"
						href="index.php?page=keywords">
						Mots-clés
					</a>
				</li>

			</div>
			<div>

				<div class="gradient-line"></div>

				<a class="navbar-link <?= $currentPage === 'login' ? 'active' : ""; ?>" href="index.php?page=login">
					Connexion
				</a>
				<a class="navbar-link <?= $currentPage === 'register' ? 'active' : ""; ?>"
					href="index.php?page=register">
					Enregistrement
				</a>
				<div class="welcome">
					<a>Bienvenue </a>
					<a id="username-display"></a>
				</div>
				<!-- <a id="username-display">Bienvenue </a> -->
				<a class="navbar-link" id="logout-button" style="display: none;">Déconnexion</a>
			</div>
		</ul>
	</div>

	<div class="content"
	style="visibility: <?= ($currentPage === 'register' || $currentPage === 'login') ? 'visible' : 'hidden'; ?>;"
	>
		<?php
		// Routeur pour gérer les différentes pages
		$page = isset($_GET['page']) ? $_GET['page'] : 'symbols';

		// Inclusion de la page correspondante
		include_once 'views/' . $page . '.php';
		?>
	</div>


</body>

</html>