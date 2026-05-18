<?php
/* Prépare les données pour la page de motif d'absence */
session_start();

require_once 'Gestion_Session.php';

require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Etudiant_Modele.php';

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur'])) {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

// récupérer et valider l'ID du Justificatif
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    // Redirection vers la page d'accueil de l'étudiant
    header('Location: ../Vue/Page_Accueil_Etudiant.php');
    exit();
}
$justificatifID = (int)$_GET['id'];

$motifDetails = null;

$conn1 = connecterBDD();

try {
    $motifDetails = recupererMotifEtudiant($conn1, $justificatifID);

} catch(Exception $e) {
    header('Location: ../Vue/Page_Accueil_Etudiant.php');
    exit();
}

$conn1 = null;

// vérifier si le justificatif a été trouvé
if (!$motifDetails) {
    header('Location: ../Vue/Page_Accueil_Etudiant.php');
    exit();
}

?>