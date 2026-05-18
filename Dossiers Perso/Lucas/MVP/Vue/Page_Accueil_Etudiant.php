<?php
require_once '../Presentation/Etudiant_Accueil_Presenteur.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Etudiante</title>
    <link rel="stylesheet" href="css/Style_Page_D'accueil_Etudiant.css">
</head>
<body>
<div class="header-main">
    <div class="logo-section">
        <img src="images/logo_uphf.png" alt="Logo Université Polytechnique">
    </div>
    <div class="header-right">
        <p>ESPACE NUMÉRIQUE DE TRAVAIL</p>
        <a href="Politique_Absence.html" class="button">Politique d'absence</a>
        <a href="../Presentation/Deconnexion_Presenteur.php" class="button">Déconnexion</a>
    </div>
</div>

<div class="container">
    <h1>Mon tableau de bord</h1>

    <div class="dashboard-content daily-view">

        <h2>Absences</h2>

        <form method="GET" action="Page_Accueil_Etudiant.php" class="date-filter-form">
            <label for="date-select">Afficher les absences du :</label>
            <input type="date" id="date-select" name="selected_date" value="<?php echo htmlspecialchars($dateSelectionnee); ?>">
            <button type="submit" class="action-button">Afficher</button>

            <a href="Page_Accueil_Etudiant.php" class="action-button">Toutes dates</a>
        </form>

        <div class="table-container">
            <table class="absence-table">
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Heure</th>
                    <th>Durée</th>
                    <th>Matière</th>
                    <th>Professeur</th>
                    <th>Statut</th>
                    <th>Évaluation</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($resultatsdujour)) : ?>
                    <tr class="empty-table-message">
                        <td colspan="7">Aucune absence enregistrée.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($resultatsdujour as $abs_jour) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($abs_jour['date']); ?></td>
                            <td><?php echo htmlspecialchars($abs_jour['heure_formatee']); ?></td>
                            <td><?php echo htmlspecialchars($abs_jour['duree_formatee']); ?></td>
                            <td><?php echo htmlspecialchars($abs_jour['matiere'] ?? 'x'); ?></td>
                            <td><?php echo htmlspecialchars($abs_jour['prof'] ?? 'sans enseignant '); ?></td>
                            <td class="<?php echo getStatusClass($abs_jour['statut']); ?>">
                                <?php echo htmlspecialchars($abs_jour['statut']); ?>
                            </td>
                            <td><?php echo $abs_jour['evaluation'] ? 'oui' : 'non'; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="dashboard-content history-view">
        <h2>Mes justificatifs déposés </h2>
        <div class="table-container">
            <table class="absence-table history">
                <thead>
                <tr>
                    <th>Du</th>
                    <th>À</th>
                    <th>Au</th>
                    <th>À</th>
                    <th>Statut</th>
                    <th>Voir le motif</th>
                </tr>
                </thead>
                <tbody>
                <?php if (empty($resultatsJustificatifs)) : ?>
                    <tr class="empty-table-message">
                        <td colspan="6">Aucun justificatif déposé.</td>
                    </tr>
                <?php else : ?>
                <?php foreach ($resultatsJustificatifs as $justif) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($justif['datededebut']); ?></td>
                    <td><?php echo htmlspecialchars($justif['heuredebut']); ?></td>
                    <td><?php echo htmlspecialchars($justif['datedefin']); ?></td>
                    <td><?php echo htmlspecialchars($justif['heurefin']); ?></td>
                    <td class="<?php echo getStatusClass($justif['statut']); ?>">
                        <?php echo htmlspecialchars($justif['statut']); ?>
                    </td>
                    <td>
                        <a href="Motif_Absence.php?id=<?php echo htmlspecialchars($justif['idjustificatif']); ?>" class="action-button">Consulter</a>
                    </td>
                    <?php endforeach; ?>
                    <?php endif; ?>

                </tr>

                </tbody>
            </table>
        </div>
        <div class="justify-button">
            <a href="Page_Deposer_Justificatif.php" class="action-button">Déposer un justificatif</a>
        </div>
    </div>
</div>

<footer class="main-footer"></footer>

</body>
</html>