<?php
require_once '../Presentation/Consultation_Historique_Presenteur.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Historique Justificatif Historique</title>
    <link rel="stylesheet" href="css/Style_Page_Consultation_Justificatif_Historique.css">
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

    <div id="consultation-bloc" class="consultation-bloc-wrapper">
        <div class="consultation-contenu">

            <div class="consultation-corps">
                <div class="details-left">
                    <p class="absence-date">Absent du : <strong><?php echo htmlspecialchars($justificatifDetailsHisto['datedebut_f'] . ' à ' . $justificatifDetailsHisto['heuredebut_f']); ?></strong></p>
                    <p class="absence-date">Au : <strong><?php echo htmlspecialchars($justificatifDetailsHisto['datefin_f'] . ' à ' . $justificatifDetailsHisto['heurefin_f']); ?></strong></p>
                    <p class="absence-date">Justificatif déposé le : <strong><?php echo date('d/m/Y à H:i', strtotime($justificatifDetailsHisto['date_depot'])); ?></strong></p>

                    <p class="student-info-label">Nom : <strong><?php echo htmlspecialchars($justificatifDetailsHisto['nom']); ?></strong></p>
                    <p class="student-info-label">Prénom : <strong><?php echo htmlspecialchars($justificatifDetailsHisto['prénom']); ?></strong></p>
                    <p class="student-info-label">Adresse mail : <strong><?php echo htmlspecialchars($justificatifDetailsHisto['email']); ?></strong></p>

                    <p>Motif (Étudiant) : <?php echo htmlspecialchars($justificatifDetailsHisto['motifeleve'] ?? 'Non spécifié'); ?></p>

                    <p class="comment-label">Commentaire (Étudiant) :</p>
                    <div class="comment-box">
                        <p>
                            <?php echo nl2br(htmlspecialchars($justificatifDetailsHisto['commentaireeleve'] ?? 'Aucun commentaire')); ?>
                        </p>
                    </div>
                </div>

                <div class="separator-line"></div>

                <div class="details-right">
                    <h3 class="detail-title">Justificatif :</h3>
                    <div class="justificatif-buttons">
                        <?php
                        // on vérifie si au moins un fichier a été fourni
                        $fichier1Existe = !empty($justificatifDetailsHisto['fichier1']);
                        $fichier2Existe = !empty($justificatifDetailsHisto['fichier2']);
                        ?>

                        <?php if ($fichier1Existe) : ?>
                            <a href="<?php echo htmlspecialchars($justificatifDetailsHisto['fichier1']); ?>" download class="action-button">Télécharger Fichier 1</a>
                        <?php endif; ?>

                        <?php if ($fichier2Existe) : ?>
                            <a href="<?php echo htmlspecialchars($justificatifDetailsHisto['fichier2']); ?>" download class="action-button">Télécharger Fichier 2</a>
                        <?php endif; ?>

                        <?php if (!$fichier1Existe && !$fichier2Existe) : ?>
                            <p>(Aucun fichier fourni)</p>
                        <?php endif; ?>
                    </div>

                    <p>Motif (Responsable) : <?php echo htmlspecialchars($justificatifDetailsHisto['motifrespon'] ?? 'Non spécifié'); ?></p>

                    <p class="comment-label">Commentaire (Responsable) :</p>
                    <div class="comment-box">
                        <p>
                            <?php echo nl2br(htmlspecialchars($justificatifDetailsHisto['commentairerespon'] ?? 'Aucun commentaire')); ?>
                        </p>
                    </div>

                    <h3 class="finalite-label">Finalité : <span class="<?php echo getStatusClass($justificatifDetailsHisto['statut']); ?>"><?php echo htmlspecialchars(ucfirst($justificatifDetailsHisto['statut'])); ?></span></h3>
                </div>
            </div>

        </div>
    </div>
</div>

<footer class="main-footer"></footer>

</body>
</html>