<?php session_start(); ?>


<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Mot de passe oublié</title>
    <link rel="stylesheet" href="css/Style_Page_De_Connexion.css"> </head>
<body>
<div class="header-main">
    <div class="logo-section"><img src="images/logo_uphf.png" alt="Logo"></div>
    <div class="header-right"><p>RÉCUPÉRATION</p></div>
</div>

<div class="login-container">
    <div class="login-box">
        <h3>Récupération de mot de passe</h3>

        <?php if (isset($_GET['info']) && $_GET['info'] == 'sent') : ?>
            <p style="color: green; text-align: center;">Si cet email existe, un lien a été envoyé.</p>
        <?php else: ?>
            <form method="post" action="../Presentation/Traitement_Mdp_Oublie.php">
                <div class="form-group">
                    <label for="email">Votre Email UPHF</label>
                    <input type="email" id="email" name="email" placeholder="exemple@uphf.fr" required>
                </div>
                <input type="submit" class="login-button" name="demande_recup" value="Envoyer le lien">
            </form>
        <?php endif; ?>

        <div style="text-align: center; margin-top: 10px;">
            <a href="Page_De_Connexion.php">Retour à la connexion</a>
        </div>
    </div>
</div>
<footer class="main-footer"></footer>
</body>
</html>