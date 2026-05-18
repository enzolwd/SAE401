<?php

session_start();

require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Connexion_Modele.php';

if (isset($_POST['identifiants'])) {

    try {
        $username = $_POST['UserName'];
        $password = $_POST['mdp'];

        $conn = connecterBDD();

        // récupérer les infos utilisateur
        $utilisateur = trouverUtilisateurParNom($conn, $username);

        // reset des tentatives si ça fait plus d'1h depuis la dernière tentative et que le compte n'est pas bloqué
        if ($utilisateur && $utilisateur['blocage'] === null && $utilisateur['tentatives'] < 5
            && $utilisateur['derniere_tentative'] !== null
            && strtotime($utilisateur['derniere_tentative']) < time() - 3600) {

            $reqResetHour = $conn->prepare("
                UPDATE Utilisateur 
                SET tentatives_echouees = 0, derniere_tentative = NULL 
                WHERE idutilisateur = :id");
            $reqResetHour->bindParam(':id', $utilisateur['idUtilisateur']);
            $reqResetHour->execute();

            $utilisateur['tentatives'] = 0;
        }

        // vérifier si le compte est bloqué
        if ($utilisateur && $utilisateur['blocage'] && strtotime($utilisateur['blocage']) > time()) {
            $temps = strtotime($utilisateur['blocage']) - time();
            $minutes = floor($temps / 60);
            $_SESSION['login_error'] = "Compte bloqué. Temps restant : {$minutes} min.";
            header('Location: ../Vue/Page_De_Connexion.php');
            exit();
        }

        // mot de passe correct
        if ($utilisateur && $utilisateur['hash'] && password_verify($password, $utilisateur['hash'])) {
            // reset des tentatives et blocage et derniere_tentative
            $reqReset = $conn->prepare("
                UPDATE Utilisateur 
                SET tentatives_echouees = 0, date_fin_blocage = NULL, derniere_tentative = NULL 
                WHERE idutilisateur = :id");
            $reqReset->bindParam(':id', $utilisateur['idUtilisateur']);
            $reqReset->execute();

            $_SESSION['idUtilisateur'] = $utilisateur['idUtilisateur'];
            $_SESSION['role'] = $utilisateur['role'];

            switch ($utilisateur['role']) {
                case "Etudiant":
                    header('Location: ../Vue/Page_Accueil_Etudiant.php'); break;
                case "Professeur":
                    header('Location: ../Vue/Page_Accueil_Professeur.php'); break;
                case "ADMIN":
                    header('Location: ../Vue/ADMIN.php'); break;
                case "Responsable Pedagogique":
                    header('Location: ../Vue/Page_Accueil_Responsable.php'); break;
                case "Secretaire":
                    header('Location: ../Vue/Page_Accueil_Secretaire.php'); break;
            }
            exit();

        } else {
            // gestion tentatives échouées
            if ($utilisateur) {
                $nouvellesTentatives = $utilisateur['tentatives'] + 1;

                if ($nouvellesTentatives >= 5) {
                    $finBlocage = date('Y-m-d H:i:s', time() + (15 * 60));
                    $reqBlocage = $conn->prepare("
                        UPDATE Utilisateur 
                        SET tentatives_echouees = :t, date_fin_blocage = :d, derniere_tentative = NOW()
                        WHERE idutilisateur = :id
                    ");
                    $reqBlocage->bindParam(':t', $nouvellesTentatives);
                    $reqBlocage->bindParam(':d', $finBlocage);
                    $reqBlocage->bindParam(':id', $utilisateur['idUtilisateur']);
                    $reqBlocage->execute();

                    $_SESSION['login_error'] = "Trop de tentatives. Compte bloqué 15 minutes.";

                } else {
                    // ajout de 1 sur le nombre de tentatives avec mise à jour de derniere_tentative
                    $reqUpdate = $conn->prepare("
                        UPDATE Utilisateur 
                        SET tentatives_echouees = :t, derniere_tentative = NOW() 
                        WHERE idutilisateur = :id
                    ");
                    $reqUpdate->bindParam(':t', $nouvellesTentatives);
                    $reqUpdate->bindParam(':id', $utilisateur['idUtilisateur']);
                    $reqUpdate->execute();

                    $_SESSION['login_error'] = "Nom d'utilisateur ou mot de passe incorrect.";
                }

            } else {
                $_SESSION['login_error'] = "Nom d'utilisateur ou mot de passe incorrect.";
            }

            header('Location: ../Vue/Page_De_Connexion.php');
            exit();
        }

    } catch(Exception $e) {
        $_SESSION['login_error'] = "Erreur de connexion. Veuillez réessayer plus tard.";
        header('Location: ../Vue/Page_De_Connexion.php');
        exit();
    }
}

