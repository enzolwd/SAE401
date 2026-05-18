<?php
/* Prépare les détails d'un justificatif en attente */
session_start();

require_once 'Gestion_Session.php';

require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Responsable_Modele.php';

// Vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

// récupérer et Valider l'ID du justificatif
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header('Location: ../Vue/Page_Accueil_Responsable.php');
    exit();
}
$justificatifID = (int)$_GET['id'];

$conn1 = connecterBDD();

try {
    $justificatifDetails = recupererDetailsJustificatifAttente($conn1, $justificatifID);

} catch(Exception $e) {
    header('Location: ../Vue/Page_Accueil_Responsable.php');
    exit();
}

$conn1 = null;
?>