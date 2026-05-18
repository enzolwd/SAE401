<?php
/*
 * Fichier Presentation
 * Gère le déverrouillage d'un justificatif.
*/
session_start();

// On inclut les fichiers Modele
require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Responsable_Modele.php';

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

if (isset($_POST['confirm-deverrouiller']) && isset($_POST['justificatifID'])) {
    $justificatifID = filter_input(INPUT_POST, 'justificatifID', FILTER_VALIDATE_INT);

    if ($justificatifID === false || $justificatifID <= 0) {
        header('Location: ../Vue/Page_Accueil_Responsable.php');
        exit();
    }

    // 1. On crée la connexion
    $conn1 = connecterBDD();

    try {
        // 2. On demande au Modele de déverrouiller
        $succes = deverrouillerJustificatif($conn1, $justificatifID);

    } catch(Exception $e) { // Changé de PDOException
        // On redirige même si ça échoue
    }

    // 3. On ferme la connexion
    $conn1 = null;

    header('Location: ../Vue/Page_Accueil_Responsable.php');
    exit();

} else {
    header('Location: ../Vue/Page_Accueil_Responsable.php');
    exit();
}
?>