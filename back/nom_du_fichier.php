<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$host = getenv('DB_HOST');
$user = getenv('DB_USER');
$pass = getenv('DB_PASSWORD');
$db = getenv('DB_NAME');

try {
  $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
  echo 'Connecté à la base de données';
} catch (PDOException $e) {
  echo 'Erreur de connexion : ' . $e->getMessage();
}
