<?php
/*
 * Fichier Presentation
 * Prépare les données pour la page d'accueil de l'étudiant.
*/
session_start();

// On inclut les fichiers Modele
require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Etudiant_Modele.php';

// On vérifie si l'utilisateur est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Etudiant') { // Sécurité rôle
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

$idEtudiantConnecte = isset($_SESSION['idUtilisateur']) ? $_SESSION['idUtilisateur'] : null;

$resultatsdujour = [];
$resultatsJustificatifs = [];

$isDateView = (isset($_GET['selected_date']) && !empty($_GET['selected_date']));
$dateSelectionnee = $isDateView ? $_GET['selected_date'] : '';

// 1. On crée la connexion
$conn = connecterBDD();

try {
    // 2. On demande les données au Modele
    list($resultatsdujour, $resultatsJustificatifs) = recupererTableauxEtudiant($conn, $idEtudiantConnecte, $isDateView, $dateSelectionnee);

} catch(Exception $e) {
    // Gère une erreur si le modèle échoue
    $resultatsdujour = [];
    $resultatsJustificatifs = [];
}

// 3. On ferme la connexion
$conn = null;

// fonction qui permet de convertir le statut en classe CSS (logique de Presentation)
function getStatusClass($statut) {
    switch (strtolower($statut)) {
        case 'non justifie':
            return 'status-nojustified';
        case 'accepte':
        case 'accepté':
            return 'status-accepted';
        case 'refuse':
        case 'refusé':
        case 'plus valable':
            return 'status-refused';
        case 'demande de révision':
            return 'status-revision';
        default:
            return 'status-pending';
    }
}

// La Vue (Page_Accueil_Etudiant.php) sera incluse et utilisera les variables
?>