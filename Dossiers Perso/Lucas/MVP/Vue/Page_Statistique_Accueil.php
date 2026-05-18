<?php
require_once '../Presentation/Statistique_Accueil_Presenteur.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page Statistique Accueil</title>
    <link rel="stylesheet" href="css/Style_Page_Statistique_Accueil.css">
</head>

<body>

<div class="header-main">
    <div class="logo-section">
        <img src="images/logo_uphf.png" alt="Logo Université Polytechnique">
    </div>

    <div class="header-right">
        <p>ESPACE NUMÉRIQUE DE TRAVAIL</p>
        <a href="Page_Accueil_Responsable.php" class="bouton-statistique">Tableau de bord</a>
        <a href="../Presentation/Deconnexion_Presenteur.php" class="bouton-deconnexion">Déconnexion</a>
    </div>
</div>

<div class="container">

    <?php if (!empty($errorMessage)) : ?>
        <div class="message-erreur">
            <?php echo htmlspecialchars($errorMessage); ?>
        </div>
    <?php endif; ?>

    <h1 class="titre-principal">Liste des rattrapages</h1>

    <div class="content-wrapper">

        <section class="tableau-wrapper">
            <div class="table-container">
                <table class="tableau">
                    <thead>
                    <tr>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Durée</th>
                        <th>Matière</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Groupe</th>
                        <th>Email</th>
                    </tr>
                    </thead>

                    <tbody>
                    <?php if (empty($lesRattrapages)) : ?>
                        <tr class="empty-table-message">
                            <td colspan="8">Aucun rattrapage n'est à venir pour cette ressource.</td>
                        </tr>
                    <?php else : ?>
                        <?php foreach ($lesRattrapages as $ratrapage) : ?>
                            <tr>
                                <td><?php echo htmlspecialchars($ratrapage['date']); ?></td>
                                <td><?php echo htmlspecialchars($ratrapage['heure']); ?></td>
                                <td><?php echo htmlspecialchars($ratrapage['duree']); ?></td>
                                <td><?php echo htmlspecialchars($ratrapage['matiere']); ?></td>
                                <td><?php echo htmlspecialchars($ratrapage['nom']); ?></td>
                                <td><?php echo htmlspecialchars($ratrapage['prénom']); ?></td>
                                <td><?php echo htmlspecialchars($ratrapage['groupe']); ?></td>
                                <td><?php echo htmlspecialchars($ratrapage['email']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <aside class="sidebar-section">
            <div class="stats-box">
                <h3>Nombre de rattrapages par ressource</h3>

                <form action="Page_Statistique_Accueil.php" method="get" id="form-ressource">
                    <select name="ressource" id="ressource-select" onchange="this.form.submit();">
                        <option value="" <?php if ($ressource_selectionnee == '') echo 'selected'; ?>>Choisir une ressource</option>
                        <option value="TOUT" <?php if ($ressource_selectionnee == 'TOUT') echo 'selected'; ?>>TOUT</option>

                        <option value="R1.01" <?php if ($ressource_selectionnee == 'R1.01') echo 'selected'; ?>>R1.01</option>
                        <option value="R1.02" <?php if ($ressource_selectionnee == 'R1.02') echo 'selected'; ?>>R1.02</option>
                        <option value="R1.03" <?php if ($ressource_selectionnee == 'R1.03') echo 'selected'; ?>>R1.03</option>
                        <option value="R1.04" <?php if ($ressource_selectionnee == 'R1.04') echo 'selected'; ?>>R1.04</option>
                        <option value="R1.05" <?php if ($ressource_selectionnee == 'R1.05') echo 'selected'; ?>>R1.05</option>
                        <option value="R1.06" <?php if ($ressource_selectionnee == 'R1.06') echo 'selected'; ?>>R1.06</option>
                        <option value="R1.07" <?php if ($ressource_selectionnee == 'R1.07') echo 'selected'; ?>>R1.07</option>
                        <option value="R1.08" <?php if ($ressource_selectionnee == 'R1.08') echo 'selected'; ?>>R1.08</option>
                        <option value="R1.09" <?php if ($ressource_selectionnee == 'R1.09') echo 'selected'; ?>>R1.09</option>
                        <option value="R1.10" <?php if ($ressource_selectionnee == 'R1.10') echo 'selected'; ?>>R1.10</option>
                        <option value="R1.11" <?php if ($ressource_selectionnee == 'R1.11') echo 'selected'; ?>>R1.11</option>
                        <option value="R1.12" <?php if ($ressource_selectionnee == 'R1.12') echo 'selected'; ?>>R1.12</option>
                        <option value="R1.13" <?php if ($ressource_selectionnee == 'R1.13') echo 'selected'; ?>>R1.13</option>
                        <option value="S1.01" <?php if ($ressource_selectionnee == 'S1.01') echo 'selected'; ?>>S1.01</option>
                        <option value="S1.02" <?php if ($ressource_selectionnee == 'S1.02') echo 'selected'; ?>>S1.02</option>
                        <option value="S1.03" <?php if ($ressource_selectionnee == 'S1.03') echo 'selected'; ?>>S1.03</option>
                        <option value="S1.04" <?php if ($ressource_selectionnee == 'S1.04') echo 'selected'; ?>>S1.04</option>
                        <option value="S1.05" <?php if ($ressource_selectionnee == 'S1.05') echo 'selected'; ?>>S1.05</option>
                        <option value="S1.06" <?php if ($ressource_selectionnee == 'S1.06') echo 'selected'; ?>>S1.06</option>

                        <option value="R2.01" <?php if ($ressource_selectionnee == 'R2.01') echo 'selected'; ?>>R2.01</option>
                        <option value="R2.02" <?php if ($ressource_selectionnee == 'R2.02') echo 'selected'; ?>>R2.02</option>
                        <option value="R2.03" <?php if ($ressource_selectionnee == 'R2.03') echo 'selected'; ?>>R2.03</option>
                        <option value="R2.04" <?php if ($ressource_selectionnee == 'R2.04') echo 'selected'; ?>>R2.04</option>
                        <option value="R2.05" <?php if ($ressource_selectionnee == 'R2.05') echo 'selected'; ?>>R2.05</option>
                        <option value="R2.06" <?php if ($ressource_selectionnee == 'R2.06') echo 'selected'; ?>>R2.06</option>
                        <option value="R2.07" <?php if ($ressource_selectionnee == 'R2.07') echo 'selected'; ?>>R2.07</option>
                        <option value="R2.08" <?php if ($ressource_selectionnee == 'R2.08') echo 'selected'; ?>>R2.08</option>
                        <option value="R2.09" <?php if ($ressource_selectionnee == 'R2.09') echo 'selected'; ?>>R2.09</option>
                        <option value="R2.10" <?php if ($ressource_selectionnee == 'R2.10') echo 'selected'; ?>>R2.10</option>
                        <option value="R2.11" <?php if ($ressource_selectionnee == 'R2.11') echo 'selected'; ?>>R2.11</option>
                        <option value="R2.12" <?php if ($ressource_selectionnee == 'R2.12') echo 'selected'; ?>>R2.12</option>
                        <option value="R2.13" <?php if ($ressource_selectionnee == 'R2.13') echo 'selected'; ?>>R2.13</option>
                        <option value="S2.01" <?php if ($ressource_selectionnee == 'S2.01') echo 'selected'; ?>>S2.01</option>
                        <option value="S2.02" <?php if ($ressource_selectionnee == 'S2.02') echo 'selected'; ?>>S2.02</option>
                        <option value="S2.03" <?php if ($ressource_selectionnee == 'S2.03') echo 'selected'; ?>>S2.03</option>
                        <option value="S2.04" <?php if ($ressource_selectionnee == 'S2.04') echo 'selected'; ?>>S2.04</option>
                        <option value="S2.05" <?php if ($ressource_selectionnee == 'S2.05') echo 'selected'; ?>>S2.05</option>
                        <option value="S2.06" <?php if ($ressource_selectionnee == 'S2.06') echo 'selected'; ?>>S2.06</option>
                    </select>
                </form>

                <p class="stats-result">Il y a <?php echo htmlspecialchars($nbrRattrapages) ?> rattrapage(s) à faire passer.</p>
            </div>

            <a href="Page_Selection_Etudiant_Statistique.php" class="action-button-stat-etu">Consulter les statistiques d'un étudiant</a>
        </aside>

    </div>

</div>

<footer class="main-footer"></footer>

</body>
</html>