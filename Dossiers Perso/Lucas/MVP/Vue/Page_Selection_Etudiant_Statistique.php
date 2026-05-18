<?php
require_once '../Presentation/Statistique_Selection_Presenteur.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page Séléction Etudiant Statistique</title>
    <link rel="stylesheet" href="css/Style_Page_Selection_Etudiant_Statistique.css">
</head>

<body>

<div class="header-main">
    <div class="logo-section">
        <img src="images/logo_uphf.png" alt="Logo Université Polytechnique">
    </div>

    <div class="header-right">
        <p>ESPACE NUMÉRIQUE DE TRAVAIL</p>
        <a href="Page_Statistique_Accueil.php" class="bouton-statistique">Liste des rattrapages</a>
        <a href="../Presentation/Deconnexion_Presenteur.php" class="bouton-deconnexion">Déconnexion</a>
    </div>
</div>

<div class="container">

    <div class="stats-recherche-container">

        <?php if (!empty($errorMessage)) : ?>
            <div class="message-erreur">
                <?php echo htmlspecialchars($errorMessage); ?>
            </div>
        <?php endif; ?>

        <h2>Consulter les statistiques d'un étudiant</h2>

        <form action="Page_Statistique_D_Un_Etudiant.php" method="GET">

            <label for="recherche-etu-input">Affiner la recherche :</label>
            <input type="text" id="recherche-etu-input" placeholder="Filtrer par nom, prénom, groupe...">

            <label for="etu-selectionne">Sélectionner un étudiant :</label>
            <select name="idUtilisateur" id="etu-selectionne" required>
                <option value="">-- Choisir un étudiant --</option>

                <?php foreach ($lesEtudiants as $etudiant) : ?>
                    <option value="<?php echo htmlspecialchars($etudiant['idutilisateur']); ?>">
                        <?php
                        echo htmlspecialchars($etudiant['nom']) . ' Page_Selection_Etudiant_Statistique.php' .
                            htmlspecialchars($etudiant['prénom']) . ' (' .
                            htmlspecialchars($etudiant['groupe']) . ')';
                        ?>
                    </option>
                <?php endforeach; ?>

                <option value="" id="etu-non-trouve" style="display:none;" disabled>Aucun étudiant trouvé</option>
            </select>

            <button type="submit" class="action-button">
                Consulter
            </button>

        </form>
    </div>

</div>

<footer class="main-footer"></footer>

<script>
    document.getElementById('recherche-etu-input').addEventListener('keyup', function() {
        let filter = this.value.toLowerCase();
        let select = document.getElementById('etu-selectionne');
        let options = select.getElementsByTagName('option');
        let found = false;
        let noResultOption = document.getElementById('etu-non-trouve');

        // On fait une boucle sur toutes les options
        for (let i = 1; i < options.length; i++) {
            let option = options[i];

            // On ignore l'option "aucun résultat"
            if (option.id === 'etu-non-trouve') continue;

            let text = option.textContent.toLowerCase();

            // Si le texte de l'option contient le filtre
            if (text.includes(filter)) {
                option.style.display = ''; // On l'affiche
                found = true;
            } else {
                option.style.display = 'none'; // On la cache
            }
        }

        // Si on n'a rien trouvé, on affiche "Aucun étudiant trouvé"
        noResultOption.style.display = found ? 'none' : '';
    });
</script>

</body>
</html>