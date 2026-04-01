<?php
$host = "localhost";
$user = "root";
$pass = ""; // Par défaut vide sur Wamp/XAMPP
$dbname= "etat_civil";

// Création de la connexion
$conn = mysqli_connect($host, $user, $pass, $dbname);

// Vérification
if (!$conn) {
    die("La connexion a échoué : " . mysqli_connect_error());
}
?>