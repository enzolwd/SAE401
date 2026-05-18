<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="Style_Page_De_Connexion.css">

    <style>
        .erreur-message {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>

<div class="header-main">
    <div class="logo-section">
        <img src="logo_uphf.png" alt="Logo Université Polytechnique">
    </div>
    <div class="header-right">
        <p>ESPACE NUMÉRIQUE DE TRAVAIL</p>
    </div>
</div>

<div class="login-container">
    <div class="login-box">

        <?php
        if (isset($_SESSION['erreur_connexion'])) {
            echo '<div class="erreur-message">' . htmlspecialchars($_SESSION['erreur_connexion']) . '</div>';
            unset($_SESSION['erreur_connexion']);
        }
        ?>

        <form method="post" action="Connexion_Traitement.php">

            <div class="form-group">
                <label for="username">Nom d'utilisateur</label>
                <input id="UserName" name="UserName" placeholder="Entrez votre nom d'utilisateur..." required>
            </div>

            <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <input type="password" id="mdp" name="mdp" placeholder="Entrez votre mot de passe..." required>
            </div>

            <input type="submit" class="login-button" name="identifiants" value="Se connecter">

        </form>
    </div>
</div>

<footer class="main-footer"></footer>
</body>
</html>