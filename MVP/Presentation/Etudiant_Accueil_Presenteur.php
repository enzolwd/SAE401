<?php
/* Prépare les données pour la page d'accueil de l'étudiant */
session_start();

require_once 'Gestion_Session.php';

require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Etudiant_Modele.php';

// On vérifie si l'utilisateur est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Etudiant') {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

$idEtudiantConnecte = isset($_SESSION['idUtilisateur']) ? $_SESSION['idUtilisateur'] : null;

$resultatsdujour = [];
$resultatsJustificatifs = [];

$isDateView = (isset($_GET['selected_date']) && !empty($_GET['selected_date']));
$dateSelectionnee = $isDateView ? $_GET['selected_date'] : '';

$conn = connecterBDD();

try {
    list($resultatsdujour, $resultatsJustificatifs) = recupererTableauxEtudiant($conn, $idEtudiantConnecte, $isDateView, $dateSelectionnee);

} catch(Exception $e) {
    // gère une erreur si le modèle échoue
    $resultatsdujour = [];
    $resultatsJustificatifs = [];
}

$conn = null;

// fonction qui permet de convertir le statut en classe CSS
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
?>