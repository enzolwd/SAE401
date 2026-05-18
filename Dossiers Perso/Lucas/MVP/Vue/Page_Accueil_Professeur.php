<?php
require_once '../Presentation/Professeur_Accueil_Presenteur.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page Professeur</title>
    <link rel="stylesheet" href="css/Style_Page_Accueil_Professeur.css">
</head>
<body>

<div class="header-main">
    <div class="logo-section">
        <img src="images/logo_uphf.png" alt="Logo Université Polytechnique">
    </div>
    <div class="header-right">
        <p>ESPACE NUMÉRIQUE DE TRAVAIL</p>
        <a href="../Presentation/Deconnexion_Presenteur.php" class="bouton-deconnexion">Déconnexion</a>
    </div>
</div>

<div class="container">

    <h1 class="titre-principal">Liste des rattrapages</h1>

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
                        <td colspan="8">Aucun rattrapage n'est à venir.</td>
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

</div>

<footer class="main-footer"></footer>
</body>
</html>