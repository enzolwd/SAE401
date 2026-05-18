<?php
/* Prépare la liste des étudiants pour la sélection */
session_start();

require_once 'Gestion_Session.php';

require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Responsable_Modele.php';

if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

$lesEtudiants = [];
$errorMessage = '';

$conn = connecterBDD();

try {
    list($lesEtudiants, $errorMessage) = chercherEtudiantsStats($conn);

} catch (Exception $e) {
    $errorMessage = "Erreur de connexion à la base de données : ";
}

$conn = null;

?>