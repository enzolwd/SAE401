<?php
/* Gère l'ajout d'un utilisateur par l'ADMIN */
session_start();

require_once 'Gestion_Session.php';

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

        $conn1 = connecterBDD();

        $succes = creerUtilisateur($conn1, $role, $UserName, $hash, $nom, $prenom, $idIUT, $groupe, $mail);

        $conn1 = null;

        if ($succes) {
            header('Location: ../Vue/ADMIN.php?success=true');
            exit();
        } else {
            header('Location: ../Vue/ADMIN.php?error=duplicate_idIUT');
            exit();
        }
    }
} catch(Exception $e) {
    header('Location: ../Vue/ADMIN.php');
    exit();
}
?>