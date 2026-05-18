<?php
require_once '../Presentation/Motif_Absence_Presenteur.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Motif Justificatif</title>
    <link rel="stylesheet" href="css/Style_Motif_Absence.css">
</head>
<body>

<div class="header-main">
    <div class="logo-section">
        <img src="images/logo_uphf.png" alt="Logo Université Polytechnique">
    </div>
    <div class="header-right">
        <p>ESPACE NUMÉRIQUE DE TRAVAIL</p>
        <a href="Politique_Absence.html" class="bouton">Politique d'absence</a>
        <a href="../Presentation/Deconnexion_Presenteur.php" class="bouton">Déconnexion</a>
    </div>
</div>

<div class="container">

    <div class="bouton-retour-wrapper">
        <a href="Page_Accueil_Etudiant.php" class="action-button">Retour</a>
    </div>
    <div class="content">
        <div class="header">
            <h3>Motif de l'absence</h3>
        </div>
        <div class="content-body">
            <div class="form-section">
                <div class="form-group">
                    <label>Motif du Resp. Pédagogique</label>
                    <p class="content-value">
                        <?php
                        echo htmlspecialchars($motifDetails['motifrespon'] ?? 'Le responsable n\'a pas donné de motif');
                        ?>
                    </p>
                </div>
                <div class="form-group">
                    <label>Commentaire du Resp. Pédagogique :</label>
                    <div class="comment-box">
                        <p>
                            <?php
                            echo nl2br(htmlspecialchars($motifDetails['commentairerespon'] ?? 'Le responsable n\'a pas donné de commentaire'));
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<footer class="main-footer"></footer>

</body>
</html>