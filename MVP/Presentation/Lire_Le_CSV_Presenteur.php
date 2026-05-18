<?php
/* Gère l'upload de CSV par la secrétaire */
session_start();

require_once 'Gestion_Session.php';

require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Secretaire_Modele.php';

// vérifier que l'utilisateur s'est bien connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Secretaire') {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

try {
    // Vérifie si le fichier a été correctement envoyé
    if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
        $fichier = $_FILES['csv_file']['tmp_name'];

        if (($f = fopen($fichier, "r")) !== FALSE) {
            $csv_data = [];
            while (($data = fgetcsv($f, 1000, ";", "\"", '')) !== FALSE) {
                $csv_data[] = $data;
            }
            fclose($f);

            $conn = connecterBDD();

            $resultats = traiterFichierCSV($conn, $csv_data);

            $conn = null;

            // on gère la réponse
            if ($resultats['status'] === 'success') {
                $_SESSION['lignesLues'] = $resultats['lignesLues'];
                $_SESSION['countAjoutes'] = $resultats['countAjoutes'];
                $_SESSION['countDoublons'] = $resultats['countDoublons'];
                $_SESSION['countDejaJustifiees'] = $resultats['countDejaJustifiees'];
                $_SESSION['countEtuInexistant'] = $resultats['countEtuInexistant'];
                $_SESSION['ListeEtudiantInexistant'] = $resultats['ListeEtudiantInexistant'];

                header('Location: ../Vue/Page_Accueil_Secretaire.php?status=success');
                exit;
            } else {
                $_SESSION['import_error_public'] = "Une erreur technique est survenue. Veuillez contacter le service compétent.";
                header('Location: ../Vue/Page_Accueil_Secretaire.php?status=error_db');
                exit;
            }
        } else {
            header('Location: ../Vue/Page_Accueil_Secretaire.php?status=error');
            exit;
        }
    } else {
        header('Location: ../Vue/Page_Accueil_Secretaire.php?status=error');
        exit;
    }
} catch (Exception $e) {
    $_SESSION['import_error_public'] = "Une erreur technique est survenue. Veuillez contacter le service compétent.";
    header('Location: ../Vue/Page_Accueil_Secretaire.php?status=error_db');
    exit;
}
?>