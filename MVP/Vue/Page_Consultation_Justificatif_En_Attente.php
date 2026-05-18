<?php
require_once '../Presentation/Consultation_Attente_Presenteur.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Consultation Justificatif Attente</title>
    <link rel="stylesheet" href="css/Style_Page_Consultation_Justificatif_En_Attente.css">
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
    <div class="consultation-wrapper">

        <div class="consultation-body">

            <div class="details-left">
                <p class="absence-date">Absent du : <strong><?php echo htmlspecialchars($justificatifDetails['datedebut_f'] . ' à ' . $justificatifDetails['heuredebut_f']); ?></strong></p>
                <p class="absence-date">Au : <strong><?php echo htmlspecialchars($justificatifDetails['datefin_f'] . ' à ' . $justificatifDetails['heurefin_f']); ?></strong></p>
                <p class="absence-date">Justificatif déposé le : <strong><?php echo date('d/m/Y à H:i', strtotime($justificatifDetails['date_depot'])); ?></strong></p>

                <h3 class="detail-title">Motif : <?php echo htmlspecialchars($justificatifDetails['motifeleve']); ?></h3>

                <h3 class="detail-title">Commentaire :</h3>
                <div class="comment-box">
                    <p>
                        <?php
                        echo nl2br(htmlspecialchars($justificatifDetails['commentaireeleve'] ?? 'Aucun commentaire'));
                        ?>
                    </p>
                </div>
            </div>

            <div class="separator-line"></div>

            <div class="details-right">
                <h3 class="detail-title">Justificatif :</h3>
                <div class="justificatif-buttons">
                    <?php
                    // On vérifie si au moins un fichier a été fourni
                    $fichier1Existe = !empty($justificatifDetails['fichier1']);
                    $fichier2Existe = !empty($justificatifDetails['fichier2']);
                    ?>

                    <?php if ($fichier1Existe) : ?>
                        <a href="<?php echo htmlspecialchars($justificatifDetails['fichier1']); ?>" download class="action-button">Télécharger Fichier 1</a>
                    <?php endif; ?>

                    <?php if ($fichier2Existe) : ?>
                        <a href="<?php echo htmlspecialchars($justificatifDetails['fichier2']); ?>" download class="action-button">Télécharger Fichier 2</a>
                    <?php endif; ?>

                    <?php if (!$fichier1Existe && !$fichier2Existe) : ?>
                        <p>(Aucun fichier fourni)</p>
                    <?php endif; ?>
                </div>

                <p class="student-info-label">Nom : <strong><?php echo htmlspecialchars($justificatifDetails['nom']); ?></strong></p>
                <p class="student-info-label">Prénom : <strong><?php echo htmlspecialchars($justificatifDetails['prénom']); ?></strong></p>
                <p class="student-info-label">Adresse mail : <strong><?php echo htmlspecialchars($justificatifDetails['email']); ?></strong></p>

                <div class="action-trio-buttons">
                    <a href="Page_Demande_Revision.php?id=<?php echo $justificatifID; ?>" class="action-button">Demander une revision</a>
                    <a href="Page_De_Refus.php?id=<?php echo $justificatifID; ?>" class="action-button">Refuser</a>
                    <a href="Page_Acceptation.php?id=<?php echo $justificatifID; ?>" class="action-button">Valider</a>
                </div>
            </div>
        </div>

    </div>
</div>

<footer class="main-footer"></footer>

</body>
</html>