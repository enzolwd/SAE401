<?php
session_start();

// Vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'Responsable Pedagogique') {
    header('Location: Page_De_Connexion.php');
    exit();
}

// vérifier que l'url contient bien l'id de l'utilisateur
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    header('Location: Page_De_Connexion.php');
    exit();
}
$justificatifID = (int)$_GET['id'];

$errorMessage = '';
if (isset($_GET['error'])) {
    if ($_GET['error'] === 'comment_required') {
        $errorMessage = "Le commentaire est obligatoire pour une demande de révision.";
    } elseif ($_GET['error'] === 'db_update') {
        $errorMessage = "Erreur de base de données. Veuillez réessayer.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Demander une révision</title>
    <link rel="stylesheet" href="css/Style_Page_Demande_Revision.css">
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

    <div class="revision-container">
        <div class="revision-comment-content">

            <form method="post" action="../Presentation/Demande_De_Revision_Presenteur.php" id="demanderModif">
                <div class="revision-comment-body">

                    <?php
                    if (!empty($errorMessage)) {
                        echo '<div class="error-message-box">' . htmlspecialchars($errorMessage) . '</div>';
                    }
                    ?>

                    <p class="revision-instruction">
                        Pour demander une révision du justificatif, vous devez saisir un commentaire. Ce commentaire sera visible par l'étudiant.
                    </p>

                    <h3 class="text-content">Commentaire :</h3>

                    <textarea id="commentaireModif" name="commentaireModif" class="revision-textarea"
                              placeholder="Indiquez ce que l'étudiant doit modifier/ajouter..."
                              required></textarea>

                    <input type="hidden" name="justificatifID_form" value="<?php echo $justificatifID; ?>">

                    <div class="revision-buttons-wrapper">
                        <button type="submit" id="revision" class="action-button" name="revision">Valider</button>
                    </div>
                </div>
            </form>

        </div>
    </div>

</div>

<footer class="main-footer"></footer>

</body>
</html>