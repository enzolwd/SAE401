<?php
session_start();

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
    <title>Confirmer Déverrouillage Justificatif</title>
    <link rel="stylesheet" href="css/Style_Page_Confirmation_Deverouillage.css">
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
        <a href="Page_Accueil_Responsable.php" class="action-button">Retour</a>
    </div>

    <div id="confirmation-deverrouillage" class="confirmation-bloc">
        <div class="confirmation-contenu">
            <h3>Confirmation</h3>
            <p>Voulez-vous vraiment déverrouiller ce justificatif ?<br>
                L'étudiant pourra de nouveau déposer un justificatif concernant les absences que couvrait ce justificatif.</p>

            <form method="POST" action="../Presentation/Confirmation_Deverouillage_Presenteur.php" class="confirmation-boutons">

                <input type="hidden" name="justificatifID" value="<?php echo $justificatifID_from_url; ?>">

                <button type="submit" name="confirm-deverrouiller" class="action-button">Confirmer</button>

            </form>
        </div>
    </div>
</div>

<footer class="main-footer"></footer>

</body>
</html>