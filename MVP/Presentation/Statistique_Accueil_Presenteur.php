<?php
/* Prépare les données pour la page d'accueil des statistiques */
session_start();

require_once 'Gestion_Session.php';

require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Responsable_Modele.php';

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

$ressource_selectionnee = isset($_GET['ressource']) ? $_GET['ressource'] : '';

$lesRattrapages = [];
$nbrRattrapages = 0;
$errorMessage = '';

$conn1 = connecterBDD();

try {
    list($lesRattrapages, $nbrRattrapages, $errorMessage) = recupererRattrapagesStats($conn1, $ressource_selectionnee);

} catch(Exception $e) {
    $errorMessage = "Erreur de connexion à la base de données. Impossible de charger les données.";
}

$conn1 = null;


function getStatusClass($statut) {
    switch (strtolower($statut)) {
        case 'non justifie':
            return 'status-nojustified';
        case 'accepte':
        case 'accepté':
            return 'status-accepted';
        case 'refuse':
        case 'refusé':
            return 'status-refused';
        case 'demande de révision':
            return 'status-revision';
        default:
            return 'status-pending';
    }
}

?>