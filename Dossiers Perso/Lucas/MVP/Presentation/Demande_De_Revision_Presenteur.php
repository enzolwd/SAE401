<?php
/*
 * Fichier Presentation
 * Gère la demande de révision d'un justificatif.
*/
session_start();

// On inclut les fichiers Modele
require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Responsable_Modele.php';

// Vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

if (isset($_POST['revision']) && isset($_POST['justificatifID_form'])) {

    $justificatifID = filter_input(INPUT_POST, 'justificatifID_form', FILTER_VALIDATE_INT);
    $commentaireResponsable = trim($_POST['commentaireModif']);

    if ($justificatifID === false || $justificatifID <= 0) {
        header('Location: ../Vue/Page_Accueil_Responsable.php');
        exit();
    }

    if (empty($commentaireResponsable)) {
        header('Location: ../Vue/Page_Demande_Revision.php?id=' . $justificatifID);
        exit();
    }

    // 1. On crée la connexion
    $conn1 = connecterBDD();

    try {
        // 2. On demande au Modele de mettre en révision
        $succes = demanderRevisionJustificatif($conn1, $justificatifID, $commentaireResponsable);
        header('Location: ../Vue/Page_Accueil_Responsable.php?traitement=revision');
        exit();

    } catch(Exception $e) { // Changé de PDOException
        header('Location: ../Vue/Page_Demande_Revision.php?id=' . $justificatifID);
        exit();
    }

    // 3. On ferme la connexion
    $conn1 = null;

} else {
    header('Location: ../Vue/Page_Accueil_Responsable.php');
    exit();
}
?>