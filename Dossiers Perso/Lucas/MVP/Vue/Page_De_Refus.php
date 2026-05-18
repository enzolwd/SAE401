<?php
session_start();

// Vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: Page_De_Connexion.php');
    exit();
}

// vérifier que l'url contient bien l'id de l'utilisateur
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header('Location: Page_Accueil_Responsable.php');
    exit();
}
$justificatifID = (int)$_GET['id'];

$errorMessage = '';
if (isset($_GET['error']) && $_GET['error'] === 'xor') {
    $errorMessage = "Erreur : Vous devez choisir un motif dans la liste ou écrire un motif personnalisé.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Refuser justificatif</title>
    <link rel="stylesheet" href="css/Style_Page_De_Refus.css">
</head>
<body>

<div class="header-main">
    <div class="logo-section">
        <img src="images/logo_uphf.png" alt="Logo Université Polytechnique">
    </div>
    <div class="header-right">
        <p>ESPACE NUMÉRIQUE DE TRAVAIL</p>
        <a href="Page_Statistique_Accueil.php" class="bouton-statistique">Statistique</a>
        <a href="../Presentation/Deconnexion_Presenteur.php" class="bouton-deconnexion">Déconnexion</a>
    </div>
</div>

<div class="container">

    <div class="bouton-retour-wrapper">
        <a href="Page_Consultation_Justificatif_En_Attente.php?id=<?php echo $justificatifID; ?>" class="action-button">Retour</a>
    </div>

    <div class="refusal-wrapper">

        <h3 class="content-title">Motif de Refus du Justificatif</h3>

        <?php
        if (!empty($errorMessage)) {
            echo '<div class="error-message-box">' . htmlspecialchars($errorMessage) . '</div>';
        }
        ?>

        <form method="post" action="../Presentation/Refus_Presenteur.php" id="refuserjustificatif">
            <div class="refusal-body">

                <label for="motifRefus" class="refusal-label detail-title">Motif (Option 1 : Choisir)</label>
                <select id="motifRefus" name="motifRefus" class="refusal-select">
                    <option value="" selected>Choisir un motif de refus...</option>
                    <option>Justificatif invalide</option>
                    <option>Justificatif illisible</option>
                    <option>Pièce justificative manquante</option>
                    <option>Motif d'absence non recevable</option>
                    <option>Dates ou heures incohérentes avec l'absence</option>
                </select>

                <label for="motifPerso" class="refusal-label detail-title">Motif (Option 2 : Écrire)</label>
                <input type="text" id="motifPerso" name="motifPerso" class="refusal-select" placeholder="Écrire un motif personnalisé..." maxlength="65">

                <label for="commentaireRefus" class="refusal-label detail-title">Commentaire pour l'étudiant :</label>
                <textarea id="commentaireRefus" name="commentaireRefus" class="refusal-textarea" placeholder="Expliquez la raison du refus... (facultatif)"></textarea>

                <input type="hidden" name="justificatifID" value="<?php echo $justificatifID; ?>">

                <div class="refusal-buttons-trio">
                    <button type="submit" id="refuser" class="action-button" name="refuser">Valider</button>
                </div>

            </div>
        </form>

    </div>
</div>

<footer class="main-footer"></footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const motifSelect = document.getElementById('motifRefus');
        const motifPerso = document.getElementById('motifPerso');

        // Quand l'utilisateur choisit dans le menu déroulant
        motifSelect.addEventListener('change', function() {
            // S'il choisit une option, on désactive le champ texte
            if (motifSelect.value !== '') {
                motifPerso.disabled = true;
                motifPerso.value = ''; // On vide le champ texte au cas où
            } else {
                // S'il revient sur "Choisir...", on réactive le champ texte
                motifPerso.disabled = false;
            }
        });

        // Quand l'utilisateur écrit dans le champ texte
        motifPerso.addEventListener('input', function() {
            // S'il écrit, on désactive le menu déroulant
            if (motifPerso.value.trim() !== '') {
                motifSelect.disabled = true;
                motifSelect.value = ''; // On réinitialise le menu
            } else {
                // S'il efface tout, on réactive le menu
                motifSelect.disabled = false;
            }
        });
    });
</script>

</body>
</html>