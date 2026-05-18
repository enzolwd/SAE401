<?php
// Démarre la session pour stocker les données du CSV
session_start();

// Le nom de la page qui contient le formulaire
$target_page = "Page_D'accueil_Secretaire.php";

$csv_data = [];

// Vérifie si le fichier a été correctement envoyé
if (isset($_FILES['csv_file']) && $_FILES['csv_file']['error'] === UPLOAD_ERR_OK) {
    $file_tmp_path = $_FILES['csv_file']['tmp_name'];

    // Ouvre le fichier
    if (($handle = fopen($file_tmp_path, "r")) !== FALSE) {

        // Correction pour le Deprecated : Utilisation de la syntaxe complète pour fgetcsv
        // Paramètres : $handle, $length, $delimiter ';', $enclosure '"', $escape ''
        while (($data = fgetcsv($handle, 1000, ";", "\"", '')) !== FALSE) {
            $csv_data[] = $data;
        }

        fclose($handle);

        // Stockage des données dans $_SESSION
        $_SESSION['csv_data'] = $csv_data;
        $_SESSION['csv_filename'] = $_FILES['csv_file']['name'];

        // Redirige vers la page du formulaire avec statut de succès
        header('Location: ' . $target_page . '?status=success');
        exit;

    } else {
        // Erreur lors de l'ouverture
        header('Location: ' . $target_page . '?status=error');
        exit;
    }
} else {
    // Erreur d'upload ou fichier manquant
    header('Location: ' . $target_page . '?status=error');
    exit;
}
?>