<?php
/*
 * Fichier Presentation
 * Prépare la liste des étudiants pour la sélection.
*/
session_start();

// On inclut les fichiers Modele
require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Responsable_Modele.php';

if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

$lesEtudiants = [];
$errorMessage = '';

// 1. On crée la connexion
$conn = connecterBDD();

try {
    // 2. On demande les données au Modele
    list($lesEtudiants, $errorMessage) = chercherEtudiantsStats($conn);

} catch (Exception $e) { // Changé de PDOException
    $errorMessage = "Erreur de connexion à la base de données : ";
}

// 3. On ferme la connexion
$conn = null;

// La Vue (Page_Selection_Etudiant_Statistique.php) sera incluse
?>