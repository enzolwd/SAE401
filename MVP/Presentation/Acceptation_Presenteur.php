<?php
/* Gère l'acceptation d'un justificatif et l'ajout de motif */
session_start();

require_once 'Gestion_Session.php';
require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Responsable_Modele.php';
require_once '../../Fonction_mail.php';

// vérification connexion
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

$conn1 = connecterBDD();

// ajout d'un motif d'acceptation dans la bdd
if (isset($_POST['ajouter_motif'])) {
    $nouveauMotif = trim($_POST['nouveauMotif']);
    $justificatifID = filter_input(INPUT_POST, 'justificatifID', FILTER_VALIDATE_INT);

    if (!empty($nouveauMotif)) {
        ajouterMotifAcceptation($conn1, $nouveauMotif);
    }

    // Retour à la page d'acceptation
    header('Location: ../Vue/Page_Acceptation.php?id=' . $justificatifID);
    exit();
}

// validation d'un justificatif
if (isset($_POST['valider']) && isset($_POST['justificatifID'])) {

    $justificatifID = filter_input(INPUT_POST, 'justificatifID', FILTER_VALIDATE_INT);
    $commentaireResponsable = trim($_POST['commentaireValider']);
    $motifrespon = trim($_POST['motifAccepter']);

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

    try {
        $succes = accepterJustificatif($conn1, $justificatifID, $commentaireResponsable, $motifrespon);

        $email = recupererMailEtudiant($conn1, $justificatifID);
        $utilisateur = recupererNomEtudiant($conn1, $justificatifID);
        $nomComplet = $utilisateur['prénom'] . ' ' . $utilisateur['nom'];

        envoyerMail($email, $nomComplet, 2);

        header('Location: ../Vue/Page_Accueil_Responsable.php?traitement=succes');
        exit();

    } catch(Exception $e) {
        header('Location: ../Vue/Page_Accueil_Responsable.php');
        exit();
    }

} else {
    if(isset($_GET['id'])) {
        // on ne fait rien
    } else {
        header('Location: ../Vue/Page_Accueil_Responsable.php');
        exit();
    }
}

$conn1 = null;
?>