<?php
/*
 * Fichier Presentation
 * Prépare les données pour la page d'accueil du professeur.
*/
session_start();

// On inclut les fichiers Modele
require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Professeur_Modele.php';

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Professeur') { // Sécurité rôle
    header('Location: ../Vue/Page_de_connexion/Page_De_Connexion.php');
    exit();
}

$idProf = $_SESSION['idUtilisateur'];

// 1. On crée la connexion
$conn1 = connecterBDD();

try {
    // 2. On demande les données au Modele
    $lesRattrapages = recupererRattrapagesProf($conn1, $idProf);

} catch(Exception $e) { // Changé de PDOException
    header('Location: ../Vue/Page_de_connexion/Page_De_Connexion.php');
    exit();
}

// 3. On ferme la connexion
$conn1 = null;

// La Vue (Page_Accueil_Professeur.php) sera incluse et utilisera $lesRattrapages
?>