<?php
/* Prépare les détails d'un justificatif de l'historique */
session_start();

require_once 'Gestion_Session.php';

require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Responsable_Modele.php';

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

// récupérer et valider l'ID du Justificatif
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header('Location: ../Vue/Page_Accueil_Responsable.php');
    exit();
}
$justificatifID = (int)$_GET['id'];

$conn1 = connecterBDD();

try {
    $justificatifDetailsHisto = recupererDetailsJustificatifHistorique($conn1, $justificatifID);
} catch(Exception $e) {
    header('Location: ../Vue/Page_Accueil_Responsable.php');
    exit();
}

$conn1 = null;

// fonction qui permet d'attribuer un css au texte
function getStatusClass($statut) {
    switch (strtolower($statut)) {
        case 'non justifie':
            return 'status-nojustified';
        case 'accepté':
            return 'status-accepted';
        case 'refusé':
            return 'status-refused';
        case 'en attente':
        default:
            return 'status-pending';
    }
}

?>