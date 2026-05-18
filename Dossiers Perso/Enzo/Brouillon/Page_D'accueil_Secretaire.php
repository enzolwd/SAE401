<?php session_start(); ?>
<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="Style_Page_D'accueil_Secretaire.css">
</head>
<body>

<div class="header-main">
    <div class="logo-section">
        <img src="logo_uphf.png" alt="Logo Université Polytechnique">
    </div>
    <div class="header-right">
        <p>ESPACE NUMÉRIQUE DE TRAVAIL</p>
        <a href="Page_De_Connexion.php" class="bouton-deconnexion">Déconnexion</a>
    </div>
</div>

<div class="container">
    <form method="POST" action="Lire_Le_CSV.php" enctype="multipart/form-data" class="file-upload-area">

        <input type="file" id="csv-file-input" name="csv_file" accept=".csv" style="display: none;">

        <label for="csv-file-input" class="action-button">
            Sélectionner un fichier CSV
        </label>

        <input type="submit" id="submit-csv" value="Lire le Fichier" style="display: none;">

        <p id="file-status">
            <?php
            // Vérifie si un statut est présent dans l'URL
            if (isset($_GET['status'])) {

                // Cas 1 : Le fichier a été traité avec succès
                if ($_GET['status'] == 'success') {

                    // Message de succès
                    echo "<span style='color: #155724; font-weight: bold;'>✅ Fichier traité avec succès.</span>";

                    // On vérifie si les données du CSV sont bien stockées en session
                    if (isset($_SESSION['csv_data'])) {

                        // On compte le nombre de lignes
                        $rowCount = count($_SESSION['csv_data']);

                        // On affiche le nombre de lignes (sur une nouvelle ligne grâce à <br>)
                        echo "<br><span style='font-weight: bold;'>Nombre de lignes lues : " . $rowCount . "</span>";

                        // On vide la session pour que le message disparaisse au rechargement
                        unset($_SESSION['csv_data']);
                        unset($_SESSION['csv_filename']);

                    } else {
                        // Sécurité : si la session est vide malgré le succès
                        echo "<br><span style='color: red; font-weight: bold;'>❌ ERREUR : Statut succès, mais données non trouvées.</span>";
                    }

                    // Cas 2 : Erreur lors du traitement
                } else if ($_GET['status'] == 'error') {
                    echo "<span style='color: red; font-weight: bold;'>❌ Erreur lors du traitement du fichier.</span>";

                    // Cas 3 : Autre statut (inconnu)
                } else {
                    echo "Aucun fichier sélectionné.";
                }

                // Cas 4 : La page est chargée sans statut (état initial)
            } else {
                echo "Aucun fichier sélectionné.";
            }
            ?>
        </p>

    </form>
</div>

<footer class="main-footer"></footer>

<script>
    document.getElementById('csv-file-input').addEventListener('change', function() {
        // Soumet le formulaire dès qu'un fichier est sélectionné
        this.closest('form').submit();
    });
</script>

</body>
</html>