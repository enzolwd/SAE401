<?php
/*
 * Fichier Presentation
 * Prépare toutes les données statistiques pour UN étudiant.
*/
session_start();

// On inclut les fichiers Modele
require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Responsable_Modele.php';

if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

if (isset($_GET['idUtilisateur']) && !empty($_GET['idUtilisateur'])) {
    $idEtudiantSelectionne = $_GET['idUtilisateur'];

    // 1. On crée la connexion
    $conn = connecterBDD();

    try {
        // 2. On appelle la fonction du modèle qui prépare TOUTES les données
        $donneesStats = recupererStatistiquesEtudiant($conn, $idEtudiantSelectionne);
    } catch (Exception $e) { // Changé de PDOException
        $donneesStats = ['errorMessage' => 'Erreur de connexion à la base de données.'];
    }

    // 3. On ferme la connexion
    $conn = null;

    // 4. On extrait les variables pour la Vue
    extract($donneesStats);

} else {
    header('Location: ../Vue/Page_Selection_Etudiant_Statistique.php');
    exit();
}
// La Vue (Page_Statistique_D_Un_Etudiant.php) sera incluse
?>