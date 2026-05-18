<?php
/* Prépare toutes les données statistiques pour UN étudiant */
session_start();

require_once 'Gestion_Session.php';

require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Responsable_Modele.php';

if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

if (isset($_GET['idUtilisateur']) && !empty($_GET['idUtilisateur'])) {
    $idEtudiantSelectionne = $_GET['idUtilisateur'];

    $conn = connecterBDD();

    try {
        $donneesStats = recupererStatistiquesEtudiant($conn, $idEtudiantSelectionne);
    } catch (Exception $e) {
        $donneesStats = ['errorMessage' => 'Erreur de connexion à la base de données.'];
    }

    $conn = null;

    extract($donneesStats);

} else {
    header('Location: ../Vue/Page_Selection_Etudiant_Statistique.php');
    exit();
}
?>