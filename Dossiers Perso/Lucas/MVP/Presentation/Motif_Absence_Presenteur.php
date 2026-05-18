<?php
/*
 * Fichier Presentation
 * Prépare les données pour la page de motif d'absence.
*/
session_start();

// On inclut les fichiers Modele
require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Etudiant_Modele.php';

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur'])) {
    header('Location: ../Vue/Page_de_connexion/Page_De_Connexion.php');
    exit();
}

// récupérer et valider l'ID du Justificatif
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    // Redirection vers la page d'accueil de l'étudiant
    header('Location: ../Vue/Page_Accueil_Etudiant.php');
    exit();
}
$justificatifID = (int)$_GET['id'];

// variable pour stocker les détails
$motifDetails = null;

// 1. On crée la connexion
$conn1 = connecterBDD();

try {
    // 2. On demande les données au Modele
    $motifDetails = recupererMotifEtudiant($conn1, $justificatifID);

} catch(Exception $e) { // Changé de PDOException
    header('Location: ../Vue/Page_Accueil_Etudiant.php');
    exit();
}

// 3. On ferme la connexion
$conn1 = null;

// vérifier si le justificatif a été trouvé
if (!$motifDetails) {
    header('Location: ../Vue/Page_Accueil_Etudiant.php');
    exit();
}

// La Vue (Motif_Absence.php) sera incluse et utilisera $motifDetails
?>