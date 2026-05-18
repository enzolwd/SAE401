<?php
session_start();
require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Recuperation_Modele.php';
require_once '../../Fonction_mail.php';

if (isset($_POST['demande_recup']) && !empty($_POST['email'])) {
    $email = trim($_POST['email']);
    $conn = connecterBDD();

    // vérifier si l'email existe
    $infosUtilisateur = verifierEmailEtRecupererInfos($conn, $email);

    if ($infosUtilisateur) {
        // générer un token cryptographique
        $token = bin2hex(random_bytes(32));

        // stocker le token dans la bdd
        stockerToken($conn, $infosUtilisateur['idutilisateur'], $token);


        $lien = "http://localhost:63342/SAE301Nouvelle/MVP/Vue/Page_Reinitialisation.php?token=" . $token;


        // envoyer le mail
        $nomComplet = $infosUtilisateur['prénom'] . ' ' . $infosUtilisateur['nom'];
        envoyerMail($email, $nomComplet, 10, $lien);
    }

    // on affiche le message de succès
    header('Location: ../Vue/Page_Mot_De_Passe_Oublie.php?info=sent');
    exit();
} else {
    header('Location: ../Vue/Page_De_Connexion.php');
}