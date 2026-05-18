<?php
session_start();

// vérifier que l'utilisateur s'est bien connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Secretaire') {
    header('Location: Page_De_Connexion.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accueil Secrétaire</title>
    <link rel="stylesheet" href="css/Style_Page_D'accueil_Secretaire.css">
</head>
<body>

<div class="header-main">
    <div class="logo-section">
        <img src="images/logo_uphf.png" alt="Logo Université Polytechnique">
    </div>
    <div class="header-right">
        <p>ESPACE NUMÉRIQUE DE TRAVAIL</p>
        <a href="../Presentation/Deconnexion_Presenteur.php" class="bouton-deconnexion">Déconnexion</a>
    </div>
</div>

<div class="container">
    <form method="POST" action="../Presentation/Lire_Le_CSV_Presenteur.php" enctype="multipart/form-data" class="file-upload-area">

        <input type="file" id="csv-file-input" name="csv_file" accept=".csv" style="display: none;">

        <label for="csv-file-input" class="action-button">
            Sélectionner un fichier CSV
        </label>

        <input type="submit" id="submit-csv" value="Lire le Fichier" style="display: none;">

        <p id="file-status">
            <?php
            // Vérifie si un statut est présent
            if (isset($_GET['status'])) {

                // si le fichier a été traité avec succès
                if ($_GET['status'] == 'success') {

                    echo "<span class='info success'>Fichier traité avec succès.</span>";

                    if (isset($_SESSION['lignesLues'])) {
                        $lignesLues = $_SESSION['lignesLues'];
                        $lignesAjoutees = $_SESSION['countAjoutes'];
                        $lignesDoublons = $_SESSION['countDoublons'];
                        $lignesEtuInexistant = $_SESSION['countEtuInexistant'];
                        $lignesDejaJustifiees = $_SESSION['countDejaJustifiees'];
                        $lignesignorees = $lignesDoublons + $lignesEtuInexistant + $lignesDejaJustifiees;
                        $listeEtu = $_SESSION['ListeEtudiantInexistant'];

                        echo "<br><span class='info'>Lignes de données lues : " . $lignesLues . "</span>";
                        echo "<br><span class='info added'>Absences ajoutées : " . $lignesAjoutees . "</span>";
                        echo "<br></span>";
                        echo "<br><span class='info warning'>Doublons ignorés : " . $lignesDoublons . "</span>";
                        echo "<br><span class='info warning'>Absences déjà justifiées : " . $lignesDejaJustifiees . "</span>";
                        echo "<br><span class='info warning'>Absences avec étudiant non identifié : " . $lignesEtuInexistant . "</span>";
                        echo "<br></span>";
                        echo "<br><span class='error'>Nombre total de ligne(s) ignorée(s) : " . $lignesignorees . "</span>";
                        if (!empty($listeEtu)) {
                            echo "<ul class='student-list'>";
                            echo "<strong>Étudiants non trouvés :</strong>";
                            foreach ($listeEtu as $nomEtu) {
                                echo "<li>" . htmlspecialchars($nomEtu) . "</li>";
                            }
                            echo "</ul>";
                        }

                        unset($_SESSION['lignesLues']);
                        unset($_SESSION['countAjoutes']);
                        unset($_SESSION['countDoublons']);
                        unset($_SESSION['countEtuInexistant']);
                        unset($_SESSION['countDejaJustifiees']);
                        unset($_SESSION['ListeEtudiantInexistant']);

                    } else {
                        echo "<br><span class='error'>ERREUR : Statut succès, mais données non trouvées.</span>";
                    }

                } else if ($_GET['status'] == 'error') {
                    echo "<span class='error'>Erreur lors du traitement du fichier.</span>";

                } else if ($_GET['status'] == 'error_db') {
                    echo "<span class='error'><strong>Échec de l'importation :</strong></span><br>";
                    if (isset($_SESSION['import_error_public'])) {
                        echo "<span class='error'>" . htmlspecialchars($_SESSION['import_error_public']) . "</span>";
                        unset($_SESSION['import_error_public']);
                    } else {
                        echo "<span class='error'>Une erreur technique est survenue. Un ou plusieurs étudiants ne sont pas inscrit dans la base de données. Veuillez contacter le service compétent</span>";
                    }
                } else {
                    echo "Aucun fichier sélectionné.";
                }
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
        // Soumet le form dès qu'un fichier est sélectionné
        this.closest('form').submit();
    });
</script>

</body>
</html>