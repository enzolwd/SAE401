<?php
/*
 * Fichier Presentation
 * Fait le lien entre la Vue (formulaire de connexion) et le Modele (BDD).
*/

session_start();

// On inclut les fichiers Modele nécessaires
require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Connexion_Modele.php';

// On vérifie si le formulaire a été soumis
if (isset($_POST['identifiants'])) {

    try {
        $username = $_POST['UserName'];
        $password = $_POST['mdp'];

        // 1. On crée la connexion
        $conn = connecterBDD();

        // 2. On demande les informations au Modele
        $utilisateur = trouverUtilisateurParNom($conn, $username);

        // 3. On ferme la connexion
        $conn = null;

        // On vérifie le mot de passe
        if ($utilisateur && $utilisateur['hash'] && password_verify($password, $utilisateur['hash'])) {

            // On stocke l'ID dans la session
            $_SESSION['idUtilisateur'] = $utilisateur['idUtilisateur'];

            // On récupère le rôle (attention: votre code original le récupérait 2 fois, j'ai gardé la 2e)
            $role = $utilisateur['role']; // Utilise le rôle récupéré par la fonction
            $_SESSION['role'] = $role;

            // pour savoir sur quelle page rediriger l'utilisateur
            switch ($role) {
                case "Etudiant":
                    header('Location: ../Vue/Page_Accueil_Etudiant.php');
                    break;
                case "Professeur":
                    header('Location: ../Vue/Page_Accueil_Professeur.php');
                    break;
                case "ADMIN":
                    header('Location: ../Vue/ADMIN.php');
                    break;
                case "Responsable Pedagogique":
                    header('Location: ../Vue/Page_Accueil_Responsable.php');
                    break;
                case "Secretaire":
                    header('Location: ../Vue/Page_Accueil_Secretaire.php');
                    break;
            }
            exit();

        } else {
            // Si le mot de passe ou le nom sont faux
            $_SESSION['login_error'] = "Nom d'utilisateur ou mot de passe incorrect.";
            header('Location: ../Vue/Page_De_Connexion.php');
            exit();
        }

    } catch(Exception $e) { // On capture l'erreur de connexion si elle arrive
        $_SESSION['login_error'] = "Erreur de connexion. Veuillez réessayer plus tard.";
        // $_SESSION['login_error'] = $e->getMessage(); // (gardé en commentaire)
        header('Location: ../Vue/Page_De_Connexion.php');
        exit();
    }
}
?>