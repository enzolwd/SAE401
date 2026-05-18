<?php
/* Gère le déverrouillage d'un justificatif */
session_start();

require_once 'Gestion_Session.php';

require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Responsable_Modele.php';
require_once '../../Fonction_mail.php';

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

    $conn1 = connecterBDD();

    try {
        $succes = deverrouillerJustificatif($conn1, $justificatifID);

        $email = recupererMailEtudiant($conn1, $justificatifID);
        $utilisateur = recupererNomEtudiant($conn1, $justificatifID);
        $nomComplet = $utilisateur['prénom'] . ' ' . $utilisateur['nom'];

        envoyerMail($email, $nomComplet,  5);

    } catch(Exception $e) {
    }

    $conn1 = null;

    header('Location: ../Vue/Page_Accueil_Responsable.php');
    exit();

} else {
    header('Location: ../Vue/Page_Accueil_Responsable.php');
    exit();
}
?>