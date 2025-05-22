<?php
$serveur = "localhost";
$utilisateur = "root";
$motdepasse = "";
$nom_base = "boutique";

$connexion = new mysqli($serveur, $utilisateur, $motdepasse, $nom_base);

if ($connexion->connect_error) {
    die("Échec de la connexion : " . $connexion->connect_error);
}

?>

