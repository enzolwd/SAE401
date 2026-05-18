<?php
/* Gère la demande de révision d'un justificatif */
session_start();

require_once 'Gestion_Session.php';

require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Responsable_Modele.php';
require_once '../../Fonction_mail.php';

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

    $conn1 = connecterBDD();

    try {
        $succes = demanderRevisionJustificatif($conn1, $justificatifID, $commentaireResponsable);

        $email = recupererMailEtudiant($conn1, $justificatifID);
        $utilisateur = recupererNomEtudiant($conn1, $justificatifID);
        $nomComplet = $utilisateur['prénom'] . ' ' . $utilisateur['nom'];

        envoyerMail($email, $nomComplet,  4);

        header('Location: ../Vue/Page_Accueil_Responsable.php?traitement=revision');
        exit();

    } catch(Exception $e) {
        header('Location: ../Vue/Page_Demande_Revision.php?id=' . $justificatifID);
        exit();
    }

    $conn1 = null;

} else {
    header('Location: ../Vue/Page_Accueil_Responsable.php');
    exit();
}
?>