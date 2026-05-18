<?php
session_start();

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Etudiant') {
    header('Location: Page_De_Connexion.php');
    exit();
}

$errorMessage = ''; // Variable pour le message d'erreur
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'conflict') {
        $errorMessage = "Vous avez déjà déposé un justificatif pour une ou plusieurs absences dans cet intervalle.";
    } elseif ($_GET['error'] === 'upload') {
        $errorMessage = "Erreur lors du téléchargement du fichier.";
    } elseif ($_GET['error'] === 'db') {
        $errorMessage = "Erreur de base de données. Veuillez réessayer.";
    } elseif ($_GET['error'] === 'inutile') {
        $errorMessage = "Ce justificatif ne prend en compte aucune absence.";
    }
}
$succesMessage = '';
if (isset($_GET['succes'])) {
    $succesMessage = 'Le justificatif a bien été envoyé et sera pris en charge dès que possible.';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Justifier une absence - Université Polytechnique</title>
    <link rel="stylesheet" href="css/Style_Page_Deposer_Justificatif.css">
</head>
<body>

<div class="header-main">
    <div class="logo-section">
        <img src="images/logo_uphf.png" alt="Logo Université Polytechnique">
    </div>
    <div class="header-right">
        <p>ESPACE NUMÉRIQUE DE TRAVAIL</p>
        <a href="Politique_Absence.html" class="button">Politique d'absence</a>
        <a href="../Presentation/Deconnexion_Presenteur.php" class="button">Déconnexion</a>
    </div>
</div>

<div class="container">

    <div class="bouton-retour-wrapper">
        <a href="Page_Accueil_Etudiant.php" class="action-button">Retour</a>
    </div>

    <?php
    // Afficher le message d'erreur s'il existe
    if (!empty($errorMessage)) {
        echo '<div class="error-message-box">' . htmlspecialchars($errorMessage) . '</div>';
    }
    elseif (!empty($succesMessage)) {
        echo '<div class="succes-message-box">' . htmlspecialchars($succesMessage) . '</div>';
    }
    ?>

    <form method="post" action="../Presentation/Deposer_Justificatif_Presenteur.php" id="deposerJustificatif" enctype="multipart/form-data">
        <div class="form-content">
            <div class="form-header">
                <h3>Justification d'une absence</h3>
            </div>
            <div class="form-body">
                <div class="form-section">
                    <h4>Absence</h4>
                    <div class="form-group">
                        <label for="dateDebut">Absent du</label>
                        <input type="date" id="dateDebut" name="dateDebut" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="heureDebut">À :</label>
                        <input type="time" id="heureDebut" name="heureDebut" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="dateFin">Au</label>
                        <input type="date" id="dateFin" name="dateFin" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label for="heureFin">À :</label>
                        <input type="time" id="heureFin" name="heureFin" class="form-input" required>
                    </div>

                    <div class="form-group">
                        <label for="motif">Motif</label>
                        <select id="motif" name="motif" class="form-input" required>
                            <option value="">Choisissez le motif</option>
                            <option value="Maladie">Maladie</option>
                            <option value="Rendez-vous médical">Rendez-vous médical</option>
                            <option value="Urgence familiale">Urgence familiale</option>
                            <option value="Décès d'un proche">Décès d'un proche</option>
                            <option value="Problème de transport">Problème de transport</option>
                            <option value="Obligation familiale">Obligation familiale</option>
                            <option value="Convocation administrative">Convocation administrative</option>
                            <option value="Stage ou entretien">entretien</option>
                            <option value="Participation à un événement">Participation à un événement</option>
                            <option value="Examen ou concours">Examen ou concours</option>
                            <option value="Autre">Autre</option>
                        </select>

                    </div>
                    <div class="form-group">
                        <label for="commentaire">Commentaire</label>
                        <textarea id="commentaire" name="commentaire" class="form-input" placeholder="Écrivez un commentaire... (facultatif)"></textarea>
                    </div>
                </div>
                <div class="form-section">
                    <h4>Renseignements/Documents</h4>
                    <div class="form-group">
                        <label for="fichierjustificatif" class="file-label">Importer un fichier</label>
                        <input type="file" id="fichierjustificatif" name="fichierjustificatif" class="file-input" accept=".pdf,.png,.jpg,.jpeg">
                        <small>Fichier justificatif (pdf, png, jpeg, jpg)</small>
                        <span id="file-upload-feedback"></span>
                    </div>

                </div>
            </div>
            <div class="button-footer">
                <button type="submit" id="justifier" name="justifier" class="action-button">Envoyer</button>
            </div>
        </div>
    </form>
</div>

<footer class="main-footer"></footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // sélectionner les éléments qui sont en rapport avec le dépôt du fichier
        const fileInput = document.getElementById('fichierjustificatif');
        const feedbackElement = document.getElementById('file-upload-feedback');

        // écouter l'événement 'change' sur l'input de fichier
        fileInput.addEventListener('change', function() {
            // vérifier si un fichier est sélectionné
            if (this.files && this.files.length > 0) {
                // récupérer le nom du fichier
                const fileName = this.files[0].name;

                // afficher le message de confirmation
                feedbackElement.textContent = 'Fichier importé : ' + fileName;
                feedbackElement.style.color = '#004d66';
            } else {
                // vider le message si aucun fichier n'est sélectionné
                feedbackElement.textContent = '';
            }
        });
    });
</script>

</body>
</html>