<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Mon application</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php require_once 'views/navbar.php'; ?>

    <div class="content">
        <?php
        // Routeur pour gérer les différentes pages
        $request = $_SERVER['REQUEST_URI'];

        // Supprimez les éventuels paramètres de requête de l'URI
        $route = strtok($request, '?');
        // Supprimez les éventuels slashs initiaux ou finaux de la route
        $route = trim($route, '/');

        // Choix de la page en fonction de la route
        $page = 'home';

        switch ($route) {
            case '':
                $page = 'home';
                break;
            case 'symbols':
                $page = 'symbols';
                break;
            case 'upload':
                $page = 'upload';
                break;
            case 'edit':
                $page = 'edit';
                break;
            case 'stats':
                $page = 'stats';
                break;
            case 'login':
                $page = 'login';
                break;
            default:
                // $page = '404';
                $page = 'home';
                break;
        }

        // Inclusion de la page correspondante
        include_once 'views/' . $page . '.php';
        ?>
    </div>

    <script src="js/script.js"></script>
</body>
</html>
