<?php
date_default_timezone_set('Europe/Paris');

function connecterBDD() {
    $host = "127.0.0.1";
    $port = "5432";
    $dbname = "monsite";
    $user = "testef";
    $passwordbd = "1234";

    try {
        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";
        $conn = new PDO($dsn, $user, $passwordbd);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $conn;

    } catch (PDOException $e) {
        // Affiche le vrai message PDO
        die("Erreur SQL PDO : " . $e->getMessage());
    }
}
?>
