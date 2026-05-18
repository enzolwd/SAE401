<?php
/*
 * Fichier Presentation
 * Prépare les données pour la page d'accueil du responsable.
*/
session_start();

// On inclut les fichiers Modele
require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Responsable_Modele.php';

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

// Logique de Presentation (gestion des messages de notification)
$notificationMessage = '';
$notificationType = '';
if (isset($_GET['traitement'])) {
    switch ($_GET['traitement']) {
        case 'succes':
            $notificationMessage = 'Le justificatif a été validé avec succès.';
            $notificationType = 'succes';
            break;
        case 'refuse':
            $notificationMessage = 'Le justificatif a été refusé.';
            $notificationType = 'refuse';
            break;
        case 'revision':
            $notificationMessage = 'La demande de révision a été envoyée.';
            $notificationType = 'revision';
            break;
    }
}

// 1. On crée la connexion
$conn1 = connecterBDD();

try {
    // 2. On demande les données au Modele
    list($lesjustificatifs, $lesjustificatifsHisto) = recupererTableauxResponsable($conn1);

} catch(Exception $e) { // Changé de PDOException
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

// 3. On ferme la connexion
$conn1 = null;


// fonction qui permet de convertir le statut en classe CSS (logique de Presentation)
function getStatusClass($statut) {
    switch (strtolower($statut)) {
        case 'non justifie':
            return 'status-nojustified';
        case 'accepte':
        case 'accepté':
            return 'status-accepted';
        case 'refuse':
        case 'refusé':
        case 'plus valable':
            return 'status-refused';
        case 'demande de révision':
            return 'status-revision';
        default:
            return 'status-pending';
    }
}

// La Vue (Page_Accueil_Responsable.php) sera incluse et utilisera les variables
?>