<?php
session_start();
require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Recuperation_Modele.php';

// on vérifie que le champ confirm_mdp est bien envoyé
if (isset($_POST['valider_nouveau_mdp']) && !empty($_POST['token']) && !empty($_POST['new_mdp']) && !empty($_POST['confirm_mdp'])) {

    $token = $_POST['token'];
    $nouveauMdp = $_POST['new_mdp'];
    $confirmMdp = $_POST['confirm_mdp'];

    // vérifier que les deux mots de passe correspondent
    if ($nouveauMdp !== $confirmMdp) {
        header('Location: ../Vue/Page_Reinitialisation.php?token=' . $token . '&error=mismatch');
        exit();
    }

    $conn = connecterBDD();

    // Vérifier la validité du token
    $idUtilisateur = verifierToken($conn, $token);

    if ($idUtilisateur) {
        $mdpHash = password_hash($nouveauMdp, PASSWORD_DEFAULT);

        // mettre à jour
        mettreAJourMotDePasse($conn, $idUtilisateur, $mdpHash);

        // On utilise 'login_success' pour le message vert
        $_SESSION['login_success'] = "Mot de passe modifié avec succès. Veuillez vous connecter.";

        header('Location: ../Vue/Page_De_Connexion.php');
        exit();
    } else {
        header('Location: ../Vue/Page_Reinitialisation.php?error=invalid');
        exit();
    }
} else {
    if (isset($_POST['token'])) {
        header('Location: ../Vue/Page_Reinitialisation.php?token=' . $_POST['token'] . '&error=empty');
    } else {
        header('Location: ../Vue/Page_De_Connexion.php');
    }
}
?>