<?php

// (30 minutes * 60 secondes = 1800 secondes)
$temps_limite = 1800;

// vérifier si la variable de dernière activité existe
if (isset($_SESSION['DERNIERE_ACTIVITE']) && (time() - $_SESSION['DERNIERE_ACTIVITE'] > $temps_limite)) {
    // si la dernière activité remonte à plus de 30 min
    session_unset();
    session_destroy();

    // rediriger vers la page de connexion avec un message
    header("Location: ../Vue/Page_De_Connexion.php?error=timeout");
    exit();
}

// mettre à jour le timestamp de la dernière activité à l'heure actuelle
$_SESSION['DERNIERE_ACTIVITE'] = time();
?>