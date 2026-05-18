<?php
session_start();

require_once '../Modele/ConnexionBDD.php';
require_once '../Modele/Responsable_Modele.php';
$conn = connecterBDD();

// On récupère la liste des motifs d'acceptation
$listeMotifs = recupererMotifAcceptation($conn);

if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: Page_De_Connexion.php');
    exit();
}

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header('Location: Page_Accueil_Responsable.php');
    exit();
}
$justificatifID_from_url = (int)$_GET['id'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Accepter Justificatif</title>
    <link rel="stylesheet" href="css/Style_Page_Acceptation.css">
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
        <a href="Page_Consultation_Justificatif_En_Attente.php?id=<?php echo $justificatifID_from_url; ?>" class="action-button">Retour</a>
    </div>

    <div id="validation-comment" class="validation-comment">
        <div class="validation-comment-content">
            <div class="validation-comment-body">
                <p class="validation-instruction">
                    Vous êtes sur le point de valider ce justificatif. Sélectionnez un motif existant ou créez-en un nouveau.
                </p>
                <h3 class="title-validation">Validation</h3>

                <form method="POST" action="../Presentation/Acceptation_Presenteur.php" class="form-ajout-motif">
                    <label style="display:block; text-align:left; font-weight:bold; margin-bottom:5px;">Nouveau motif (Ajout BDD)</label>
                    <div class="input-group-row">
                        <input type="text" name="nouveauMotif" class="accepter-select" placeholder="Nouveau motif..." maxlength="65" required>
                        <input type="hidden" name="justificatifID" value="<?php echo $justificatifID_from_url; ?>">
                        <button type="submit" name="ajouter_motif" class="action-button bouton-ajout">Ajouter</button>
                    </div>
                </form>

                <hr style="margin: 20px 0; border: 0; border-top: 1px dashed #ccc;">

                <form method="POST" action="../Presentation/Acceptation_Presenteur.php" id="validerJustificatif">

                    <label style="display:block; text-align:left; font-weight:bold; margin-bottom:5px;">Choisir un motif existant</label>
                    <select id="motifAccepter" name="motifAccepter" class="accepter-select" required>
                        <option value="" selected disabled>-- Choisir un motif --</option>
                        <?php foreach ($listeMotifs as $m) : ?>
                            <option value="<?php echo htmlspecialchars($m['motif']); ?>">
                                <?php echo htmlspecialchars($m['motif']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>

                    <input type="hidden" name="justificatifID" value="<?php echo $justificatifID_from_url; ?>">

                    <label style="display:block; text-align:left; font-weight:bold; margin-bottom:5px;">Commentaire (Facultatif)</label>
                    <textarea id="commentaireValider" name="commentaireValider" class="revision-textarea"
                              placeholder="Écrivez quelque chose..."></textarea>

                    <div class="validation-buttons-wrapper">
                        <button type="submit" id="valider" class="action-button" name="valider">Valider</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<footer class="main-footer"></footer>

</body>
</html>