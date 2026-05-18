<?php
session_start();
if (!isset($_GET['token']) || empty($_GET['token'])) {
    header('Location: Page_De_Connexion.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Nouveau mot de passe</title>
    <link rel="stylesheet" href="css/Style_Page_De_Connexion.css">
</head>
<body>
<div class="header-main">
    <div class="logo-section"><img src="images/logo_uphf.png" alt="Logo"></div>
</div>

<div class="login-container">
    <div class="login-box">
        <h3>Nouveau mot de passe</h3>

        <?php if (isset($_GET['error'])): ?>
            <?php if ($_GET['error'] === 'mismatch'): ?>
                <p class="error-message">Les mots de passe ne correspondent pas.</p>
            <?php else: ?>
                <p class="error-message">Lien invalide ou expiré.</p>
            <?php endif; ?>
        <?php endif; ?>

        <form method="post" action="../Presentation/Traitement_Reinitialisation.php">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($_GET['token'] ?? ''); ?>">

            <div class="form-group">
                <label for="new_mdp">Nouveau mot de passe</label>
                <input type="password" id="new_mdp" name="new_mdp" required minlength="3">
            </div>

            <div class="form-group">
                <label for="confirm_mdp">Confirmer le mot de passe</label>
                <input type="password" id="confirm_mdp" name="confirm_mdp" required minlength="3">
            </div>

            <input type="submit" class="login-button" name="valider_nouveau_mdp" value="Changer le mot de passe">
        </form>
    </div>
</div>
<footer class="main-footer"></footer>
</body>
</html>