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
            <table class="absence-table" id="tableauAbsences">
                <thead>
                <tr>
                    <th data-type="date">Date</th>
                    <th data-type="heure">Heure</th>
                    <th data-type="heure">Durée</th>
                    <th data-type="texte">Matière</th>
                    <th data-type="texte">Professeur</th>
                    <th data-type="texte">Statut</th>
                    <th data-type="texte">Évaluation</th>
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
            <table class="absence-table history" id="tableauJustificatifs">
                <thead>
                <tr>
                    <th data-type="date">Du</th>
                    <th data-type="heure">À</th>
                    <th data-type="date">Au</th>
                    <th data-type="heure">À</th>
                    <th data-type="texte">Statut</th>
                    <th data-type="aucun">Voir le motif</th>
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
                                <?php
                                if (trim(strtolower($justif['statut'])) === 'plus valable') {
                                    echo "À re-justifier<br>(plus valable)";
                                } else {
                                    echo htmlspecialchars($justif['statut']);} ?>
                            </td>
                            <td>
                                <a href="Motif_Absence.php?id=<?php echo htmlspecialchars($justif['idjustificatif']); ?>" class="action-button">Consulter</a>
                            </td>
                        </tr> <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="justify-button">
            <a href="Page_Deposer_Justificatif.php" class="action-button">Déposer un justificatif</a>
        </div>
    </div>
</div>

<footer class="main-footer"></footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // On initialise le tri sur les deux tableaux de la page
        rendreTableauTriable('tableauAbsences');
        rendreTableauTriable('tableauJustificatifs');

        function rendreTableauTriable(idTableau) {
            const tableau = document.getElementById(idTableau);
            // Si le tableau n'existe pas, on arrete
            if (!tableau) return;

            const lesEntetes = tableau.querySelectorAll('thead th');
            const corpsDuTableau = tableau.querySelector('tbody');

            lesEntetes.forEach((entete, indexColonne) => {
                // On n'ajoute pas le clic si le type est 'aucun'
                if (entete.getAttribute('data-type') !== 'aucun') {
                    entete.addEventListener('click', () => {
                        trierLeTableau(lesEntetes, corpsDuTableau, indexColonne, entete);
                    });
                }
            });
        }

        function trierLeTableau(lesEntetes, corpsDuTableau, index, enteteClique) {
            const lesLignes = Array.from(corpsDuTableau.querySelectorAll('tr'));

            // Si le tableau est vide, on ne trie pas
            if (lesLignes.length === 1 && lesLignes[0].classList.contains('empty-table-message')) return;

            // Détection de l'ordre
            const estCroissant = enteteClique.getAttribute('data-ordre') === 'asc';
            const nouvelOrdre = estCroissant ? 'desc' : 'asc';
            const multiplicateur = (nouvelOrdre === 'asc') ? 1 : -1;

            // Réinitialisation des attributs sur les autres colonnes
            lesEntetes.forEach(th => th.removeAttribute('data-ordre'));
            enteteClique.setAttribute('data-ordre', nouvelOrdre);

            const typeDeDonnee = enteteClique.getAttribute('data-type');

            // Algorithme de tri
            lesLignes.sort((ligneA, ligneB) => {
                const contenuA = ligneA.children[index].innerText.trim();
                const contenuB = ligneB.children[index].innerText.trim();

                if (typeDeDonnee === 'date') {
                    return (convertirDateFrancais(contenuA) - convertirDateFrancais(contenuB)) * multiplicateur;
                }
                else if (typeDeDonnee === 'heure') {
                    // Tri alphabétique
                    return contenuA.localeCompare(contenuB) * multiplicateur;
                }
                else {
                    // Tri alphabétique pour le texte
                    return contenuA.localeCompare(contenuB, 'fr', { numeric: true }) * multiplicateur;
                }
            });

            // réinsertion des lignes triées
            lesLignes.forEach(ligne => corpsDuTableau.appendChild(ligne));
        }

        function convertirDateFrancais(chaineDate) {
            if (!chaineDate) return new Date(0);
            const parties = chaineDate.split('/');
            // compte les mois de 0 à 11, donc on fait -1 sur le mois
            return new Date(parties[2], parties[1] - 1, parties[0]);
        }
    });
</script>

</body>
</html>