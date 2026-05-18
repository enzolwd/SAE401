<?php
/*
 * Fichier Presentation
 * Prépare les données pour la page d'accueil des statistiques.
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

$ressource_selectionnee = isset($_GET['ressource']) ? $_GET['ressource'] : '';

$lesRattrapages = [];
$nbrRattrapages = 0;
$errorMessage = ''; // Initialisation

// 1. On crée la connexion
$conn1 = connecterBDD();

try {
    // 2. On demande les données au Modele
    list($lesRattrapages, $nbrRattrapages, $errorMessage) = recupererRattrapagesStats($conn1, $ressource_selectionnee);

} catch(Exception $e) { // Changé de PDOException
    $errorMessage = "Erreur de connexion à la base de données. Impossible de charger les données.";
}

// 3. On ferme la connexion
$conn1 = null;

// La Vue (Page_Statistique_Accueil.php) sera incluse
?>