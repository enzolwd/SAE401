<?php
/* Gère le refus d'un justificatif et l'ajout de motif */
session_start();

require_once 'Gestion_Session.php';
require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Responsable_Modele.php';
require_once '../../Fonction_mail.php';

// Vérification connexion
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

$conn1 = connecterBDD();

// ajout d'un motif dans la bdd
if (isset($_POST['ajouter_motif'])) {
    $nouveauMotif = trim($_POST['nouveauMotif']);
    $justificatifID = filter_input(INPUT_POST, 'justificatifID', FILTER_VALIDATE_INT);

    if (!empty($nouveauMotif)) {
        ajouterNouveauMotif($conn1, $nouveauMotif);
    }

    // on retourne sur la page de refus
    header('Location: ../Vue/Page_De_Refus.php?id=' . $justificatifID);
    exit();
}

// valider le refus
if (isset($_POST['refuser']) && isset($_POST['justificatifID'])) {

    $justificatifID = filter_input(INPUT_POST, 'justificatifID', FILTER_VALIDATE_INT);
    $motifSelect = trim($_POST['motifRefus']);
    $commentaireRefus = trim($_POST['commentaireRefus']);

    if ($justificatifID === false || $justificatifID <= 0) {
        header('Location: ../Vue/Page_Accueil_Responsable.php');
        exit();
    }

    // Vérification que le select n'est pas vide
    if (empty($motifSelect)) {
        header('Location: ../Vue/Page_De_Refus.php?id=' . $justificatifID . '&error=empty');
        exit();
    }

    $motifFinal = $motifSelect;
    $commentaireFinal = !empty($commentaireRefus) ? $commentaireRefus : null;

    try {
        $succes = refuserJustificatif($conn1, $justificatifID, $motifFinal, $commentaireFinal);

        $email = recupererMailEtudiant($conn1, $justificatifID);
        $utilisateur = recupererNomEtudiant($conn1, $justificatifID);
        $nomComplet = $utilisateur['prénom'] . ' ' . $utilisateur['nom'];

        envoyerMail($email, $nomComplet,  3);

        header('Location: ../Vue/Page_Accueil_Responsable.php?traitement=refuse');
        exit();

    } catch(Exception $e) {
        header('Location: ../Vue/Page_Accueil_Responsable.php');
        exit();
    }
} else {
    header('Location: ../Vue/Page_Accueil_Responsable.php');
    exit();
}

$conn1 = null;
?>