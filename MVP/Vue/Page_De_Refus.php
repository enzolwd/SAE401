<?php
session_start();

require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Responsable_Modele.php';
$conn = connecterBDD();
$listeDesMotifs = recuperermotif($conn);

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
if (isset($_GET['error']) && $_GET['error'] === 'empty') {
    $errorMessage = "Erreur : Veuillez sélectionner un motif dans la liste.";
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

        <h3 class="content-title">Gestion du Refus</h3>

        <?php
        if (!empty($errorMessage)) {
            echo '<div class="error-message-box">' . htmlspecialchars($errorMessage) . '</div>';
        }
        ?>

        <form method="post" action="../Presentation/Refus_Presenteur.php" class="form-ajout-motif">
            <label class="refusal-label detail-title">Créer un nouveau motif (Ajout BDD)</label>
            <div class="input-group-row">
                <input type="text" name="nouveauMotif" class="refusal-select" placeholder="Nouveau motif..." maxlength="65" required>
                <input type="hidden" name="justificatifID" value="<?php echo $justificatifID; ?>">
                <button type="submit" name="ajouter_motif" class="action-button bouton-ajout">Ajouter</button>
            </div>
        </form>

        <hr style="margin: 20px 0; border: 0; border-top: 1px dashed #ccc;">

        <form method="post" action="../Presentation/Refus_Presenteur.php" id="refuserjustificatif">
            <div class="refusal-body">

                <label for="motifRefus" class="refusal-label detail-title">Sélectionner le motif de refus</label>
                <select id="motifRefus" name="motifRefus" class="refusal-select" required>
                    <option value="" selected disabled>-- Choisir dans la liste --</option>
                    <?php foreach ($listeDesMotifs as $m) : ?>
                        <option value="<?php echo htmlspecialchars($m['motif']); ?>">
                            <?php echo htmlspecialchars($m['motif']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="commentaireRefus" class="refusal-label detail-title">Commentaire pour l'étudiant :</label>
                <textarea id="commentaireRefus" name="commentaireRefus" class="refusal-textarea" placeholder="Expliquez la raison du refus... (facultatif)"></textarea>

                <input type="hidden" name="justificatifID" value="<?php echo $justificatifID; ?>">

                <div class="refusal-buttons-trio">
                    <button type="submit" id="refuser" class="action-button" name="refuser">Valider le Refus</button>
                </div>

            </div>
        </form>

    </div>
</div>

<footer class="main-footer"></footer>

</body>
</html>