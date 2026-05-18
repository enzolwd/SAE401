<?php
/*
 * Fichier Presentation
 * Gère le dépôt de justificatif par l'étudiant.
*/
session_start();

// On inclut les fichiers Modele
require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Etudiant_Modele.php';

// On vérifie si l'utilisateur est connecté
if (!isset($_SESSION['idUtilisateur'])) {
    header('Location: ../Vue/Page_De_Connexion.php');
    exit();
}

$idUtilisateurConnecte = $_SESSION['idUtilisateur'];

if (isset($_POST['justifier'])) {

    // Gestion de l'uploads du fichier du justificatif
    $cheminFichierPourBDD = null;
    if (isset($_FILES['fichierjustificatif']) && $_FILES['fichierjustificatif']['error'] === UPLOAD_ERR_OK) {

        // Le chemin pointe maintenant vers le dossier uploads du Modele
        $uploadDir = '../Modele/uploads/';
        $fileTmpName = $_FILES['fichierjustificatif']['tmp_name'];
        $fileName = basename($_FILES['fichierjustificatif']['name']);
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName = uniqid('', true) . 'Presentation' . $fileExtension;
        $destination = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpName, $destination)) {
            $cheminFichierPourBDD = $destination;
        } else {
            header('Location: ../Vue/Page_Deposer_Justificatif.php?error=uploads');
            exit();
        }
    }
    // fin de la gestion de l'upload

    $datedebut = $_POST['dateDebut'];
    $heuredebut = $_POST['heureDebut'];
    $datefin = $_POST['dateFin'];
    $heurefin = $_POST['heureFin'];
    $motif = $_POST['motif'];
    $commentaire = empty($_POST['commentaire']) ? null : $_POST['commentaire'];

    // 1. On crée la connexion
    $conn1 = connecterBDD();

    // 2. On demande au Modele de traiter le justificatif
    $resultat = deposerJustificatif($conn1, $idUtilisateurConnecte, $datedebut, $heuredebut, $datefin, $heurefin, $motif, $commentaire, $cheminFichierPourBDD);

    // 3. On ferme la connexion
    $conn1 = null;

    // 4. On redirige en fonction de la réponse du Modele
    if ($resultat === "succes") {
        header('Location: ../Vue/Page_Deposer_Justificatif.php?succes');
        exit();
    } elseif ($resultat === "inutile") {
        header('Location: ../Vue/Page_Deposer_Justificatif.php?error=inutile');
        exit();
    } elseif ($resultat === "conflict") {
        header('Location: ../Vue/Page_Deposer_Justificatif.php?error=conflict');
        exit();
    } else { // "db_error"
        header('Location: ../Vue/Page_Deposer_Justificatif.php?error=db');
        exit();
    }
}
?>