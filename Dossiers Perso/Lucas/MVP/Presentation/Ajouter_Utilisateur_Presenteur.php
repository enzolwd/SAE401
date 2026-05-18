<?php
/*
 * Fichier Presentation
 * Gère l'ajout d'un utilisateur par l'ADMIN.
*/
session_start();

// On inclut les fichiers Modele
require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Admin_Modele.php';

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur'])) {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

try {
    if (isset($_POST['CréerUtilisateur'])) {
        $role = $_POST['role'];
        $UserName = $_POST['UserName'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $mdp = $_POST['mdp'];
        $idIUT = $_POST['idIUT'];
        $groupe = $_POST["groupe"];
        $mail = $_POST['mail'];

        if ($idIUT === "") {$idIUT = null;}
        if ($groupe === "") {$groupe = null;}

        $hash = password_hash($mdp, PASSWORD_DEFAULT);

        // 1. On crée la connexion
        $conn1 = connecterBDD();

        // 2. On demande au Modele de créer l'utilisateur
        $succes = creerUtilisateur($conn1, $role, $UserName, $hash, $nom, $prenom, $idIUT, $groupe, $mail);

        // 3. On ferme la connexion
        $conn1 = null;

        if ($succes) {
            header('Location: ../Vue/ADMIN.php?success=true');
            exit();
        } else {
            // J'ai gardé votre erreur originale, même si elle n'est pas vérifiée
            header('Location: ../Vue/ADMIN.php?error=duplicate_idIUT');
            exit();
        }
    }
} catch(Exception $e) { // Changé de PDOException car la connexion est gérée ailleurs
    header('Location: ../Vue/ADMIN.php');
    exit();
}
?>