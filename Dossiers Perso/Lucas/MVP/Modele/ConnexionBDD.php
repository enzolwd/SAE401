<?php
/*
 * Fichier Modele
 * Contient la fonction UNIQUE pour se connecter à la BDD.
*/

/**
 * Fonction qui crée et retourne l'objet de connexion PDO.
 */
function connecterBDD() {
    $host = "localhost";
    $dbname = "sae301";
    $user = "plichon";
    $passwordbd = "zsZ72ANM";

    try {
        $conn = new PDO("pgsql:host=$host;dbname=$dbname", $user, $passwordbd);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;
    } catch (PDOException $e) {
        // En cas d'échec de connexion, on arrête tout
        die("Erreur de connexion à la base de données.");
    }
}
?>