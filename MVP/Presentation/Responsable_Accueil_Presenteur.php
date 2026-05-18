<?php
/* Prépare les données pour la page d'accueil du responsable */
session_start();

require_once 'Gestion_Session.php';

require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Responsable_Modele.php';

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

// gestion des messages de notification
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

$conn1 = connecterBDD();

try {
    list($lesjustificatifs, $lesjustificatifsHisto) = recupererTableauxResponsable($conn1);

} catch(Exception $e) {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

$conn1 = null;


// fonction qui permet de convertir le statut en classe CSS
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
?>