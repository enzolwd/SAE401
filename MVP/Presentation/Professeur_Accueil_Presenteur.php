<?php
/* Prépare les données pour la page d'accueil du professeur */
session_start();

require_once 'Gestion_Session.php';

require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Professeur_Modele.php';

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Professeur') {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

$idProf = $_SESSION['idUtilisateur'];

$conn1 = connecterBDD();

try {
    $lesRattrapages = recupererRattrapagesProf($conn1, $idProf);

} catch(Exception $e) {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

$conn1 = null;

?>