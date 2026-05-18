<?php
session_start();

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Etudiant') {
    header('Location: Page_De_Connexion.php');
    exit();
}

// message d'erreur
$errorMessage = '';
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
                        <input type="checkbox" id="jourEntierCheckbox" name="jourEntierCheckbox">
                        <label for="jourEntierCheckbox" style="display: inline; margin-left: 5px;">Justifier une journée entière</label>
                    </div>

                    <div id="champsJourEntier" class="champs-date hidden">
                        <div class="form-group">
                            <label for="dateJourEntier">Date de l'absence</label>
                            <input type="date" id="dateJourEntier" name="dateJourEntier" class="form-input">
                        </div>
                    </div>

                    <div id="champsIntervalle" class="champs-date">
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
                        <label for="fichierjustificatif1" class="file-label">Importer fichier 1 (facultatif)</label>
                        <input type="file" id="fichierjustificatif1" name="fichierjustificatif1" class="file-input" accept=".pdf,.png,.jpg,.jpeg">
                        <small>Fichier justificatif 1 (pdf, png, jpeg, jpg)</small>
                        <span id="file-upload-feedback1"></span>
                        <button type="button" id="remove-file1" class="bouton-annuler-fichier">Annuler</button>
                    </div>

                    <div class="form-group">
                        <label for="fichierjustificatif2" class="file-label">Importer fichier 2 (facultatif)</label>
                        <input type="file" id="fichierjustificatif2" name="fichierjustificatif2" class="file-input" accept=".pdf,.png,.jpg,.jpeg">
                        <small>Fichier justificatif 2 (pdf, png, jpeg, jpg)</small>
                        <span id="file-upload-feedback2"></span>
                        <button type="button" id="remove-file2" class="bouton-annuler-fichier">Annuler</button>
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

        function setupFileInputFeedback(inputId, feedbackId, removeButtonId) {
            const fileInput = document.getElementById(inputId);
            const feedbackElement = document.getElementById(feedbackId);
            const removeButton = document.getElementById(removeButtonId);

            if (fileInput && feedbackElement && removeButton) {

                // événement quand un fichier est choisi
                fileInput.addEventListener('change', function() {
                    // vérifier si un fichier est sélectionné
                    if (this.files && this.files.length > 0) {
                        // récupérer le nom du fichier
                        const fileName = this.files[0].name;

                        // afficher le message de confirmation
                        feedbackElement.textContent = 'Fichier importé : ' + fileName;
                        feedbackElement.style.color = '#004d66';

                        // afficher le bouton "Annuler"
                        removeButton.style.display = 'inline';
                    } else {
                        // vider le message si aucun fichier n'est sélectionné
                        feedbackElement.textContent = '';
                        // Cacher le bouton "Annuler"
                        removeButton.style.display = 'none';
                    }
                });

                // événement quand on clique sur "Annuler"
                removeButton.addEventListener('click', function() {
                    // Vider la valeur de l'input pour fichier
                    fileInput.value = '';

                    // Vider le message de feedback
                    feedbackElement.textContent = '';

                    // Cacher le bouton "Annuler"
                    removeButton.style.display = 'none';
                });
            }
        }

        // Appliquer la fonction aux deux inputs de fichier fichier1 et fichier2
        setupFileInputFeedback('fichierjustificatif1', 'file-upload-feedback1', 'remove-file1');
        setupFileInputFeedback('fichierjustificatif2', 'file-upload-feedback2', 'remove-file2');


        // récupérer les éléments
        const checkbox = document.getElementById('jourEntierCheckbox');
        const champsJourEntier = document.getElementById('champsJourEntier');
        const champsIntervalle = document.getElementById('champsIntervalle');

        // Inputs pour le jour entier
        const inputDateJourEntier = document.getElementById('dateJourEntier');

        // Inputs pour l'intervalle
        const inputDateDebut = document.getElementById('dateDebut');
        const inputHeureDebut = document.getElementById('heureDebut');
        const inputDateFin = document.getElementById('dateFin');
        const inputHeureFin = document.getElementById('heureFin');

        // ajouter l'écouteur d'événement pour choisir le mode de justification soit un jour soit interval
        checkbox.addEventListener('change', function() {
            if (this.checked) {
                // Mode "jour entier"
                champsJourEntier.classList.remove('hidden');
                champsIntervalle.classList.add('hidden');

                // Rendre le champ jour entier obligatoire et actif
                inputDateJourEntier.required = true;
                inputDateJourEntier.disabled = false;

                // Rendre les champs intervalle non-obligatoires et inactifs
                inputDateDebut.required = false;
                inputHeureDebut.required = false;
                inputDateFin.required = false;
                inputHeureFin.required = false;

                inputDateDebut.disabled = true;
                inputHeureDebut.disabled = true;
                inputDateFin.disabled = true;
                inputHeureFin.disabled = true;

            } else {
                // Mode "Intervalle"
                champsJourEntier.classList.add('hidden');
                champsIntervalle.classList.remove('hidden');

                // Rendre le champ jour entier non-obligatoire
                inputDateJourEntier.required = false;
                inputDateJourEntier.disabled = true;

                // Rendre les champs intervalle obligatoires
                inputDateDebut.required = true;
                inputHeureDebut.required = true;
                inputDateFin.required = true;
                inputHeureFin.required = true;

                inputDateDebut.disabled = false;
                inputHeureDebut.disabled = false;
                inputDateFin.disabled = false;
                inputHeureFin.disabled = false;
            }
        });

        // initialiser l'état
        checkbox.checked = false;
        const event = new Event('change');
        checkbox.dispatchEvent(event);

    });
</script>

</body>
</html>