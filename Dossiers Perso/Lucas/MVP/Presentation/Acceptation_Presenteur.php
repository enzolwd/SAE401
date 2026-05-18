<?php
/*
 * Fichier Presentation
 * Gère l'acceptation d'un justificatif.
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

// vérifier si le formulaire a été soumis
if (isset($_POST['valider']) && isset($_POST['justificatifID'])) {
    $justificatifID = filter_input(INPUT_POST, 'justificatifID', FILTER_VALIDATE_INT);
    $commentaireResponsable = trim($_POST['commentaireValider']);
    $motifrespon = $_POST['motifAccepter'];

    if (empty($commentaireResponsable)) {
        $commentaireResponsable = null;
    }
    if (empty($motifrespon)) {
        $motifrespon = null;
    }

    if ($justificatifID === false || $justificatifID <= 0) {
        header('Location: ../Vue/Page_Accueil_Responsable.php?error=invalid_id');
        exit();
    }

    // 1. On crée la connexion
    $conn1 = connecterBDD();

    try {
        // 2. On demande au Modele de valider
        $succes = accepterJustificatif($conn1, $justificatifID, $commentaireResponsable, $motifrespon);
        header('Location: ../Vue/Page_Accueil_Responsable.php?traitement=succes');
        exit();

    } catch(Exception $e) { // Changé de PDOException
        header('Location: ../Vue/Page_Accueil_Responsable.php');
        exit();
    }

    // 3. On ferme la connexion
    $conn1 = null;

} else {
    header('Location: ../Vue/Page_Accueil_Responsable.php');
    exit();
}
?>