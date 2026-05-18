<?php
require_once '../Presentation/Responsable_Accueil_Presenteur.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Tableau de bord Responsable</title>
    <link rel="stylesheet" href="css/Style_Page_Accueil_Responsable.css">
</head>

<body>

<?php
// affiche la notification si $notificationMessage existe
if (!empty($notificationMessage)) {
    echo '<div id="toast" class="toast-notification ' . htmlspecialchars($notificationType) . '">';
    echo htmlspecialchars($notificationMessage);
    echo '</div>';
}
?>
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
    <div class="dashboard-content-wrapper">

        <h1 class="titre-tableau-de-bord">Tableau De Bord</h1>

        <div class="filtres-container" style="width: 95%; margin: 0 auto 20px auto;">
            <input type="text" id="filtreDate" placeholder="Date Dépôt (jj/mm/aaaa)">
            <input type="text" id="filtreEtudiant" placeholder="Étudiant (Nom ou Prénom)">
            <input type="text" id="filtreGroupe" placeholder="Groupe">
        </div>

        <div class="content-tables-container">

            <section class="section-justificatifs">
                <h2>Justificatifs en attente</h2>
                <div class="table-container">
                    <table class="tableau" id="tableauAttente">
                        <thead>
                        <tr>
                            <th data-type="date">Date Dépôt</th>
                            <th data-type="date">Du</th>
                            <th data-type="heure">À</th>
                            <th data-type="date">Au</th>
                            <th data-type="heure">À</th>
                            <th data-type="texte">Nom</th>
                            <th data-type="texte">Prénom</th>
                            <th data-type="texte">Groupe</th>
                            <th data-type="aucun">Consulter</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($lesjustificatifs)) : ?>
                            <tr class="empty-table-message">
                                <td colspan="9">Aucun justificatif en attente.</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($lesjustificatifs as $justif) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($justif['datedepot'] ?? ''); ?></td>

                                    <td><?php echo htmlspecialchars($justif['datededebut']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['heuredebut']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['datedefin']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['heurefin']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['prénom']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['groupe']); ?></td>
                                    <td>
                                        <a href="Page_Consultation_Justificatif_En_Attente.php?id=<?php echo htmlspecialchars($justif['idjustificatif']); ?>" class="action-button">Consulter</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                            <tr class="row-no-result" style="display: none;">
                                <td colspan="9">Aucun justificatif ne correspond à votre recherche.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="section-historique">
                <h2>Historique</h2>

                <div class="table-container">
                    <table class="tableau historique" id="tableauHistorique">
                        <thead>
                        <tr>
                            <th data-type="date">Date Dépôt</th>
                            <th data-type="date">Du</th>
                            <th data-type="heure">À</th>
                            <th data-type="date">Au</th>
                            <th data-type="heure">À</th>
                            <th data-type="texte">Nom</th>
                            <th data-type="texte">Prénom</th>
                            <th data-type="texte">Groupe</th>
                            <th data-type="texte">Statut</th>
                            <th data-type="aucun">Consulter</th>
                            <th data-type="aucun">Déverrouiller</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php if (empty($lesjustificatifsHisto)) : ?>
                            <tr class="empty-table-message">
                                <td colspan="11">Aucun justificatif dans l'historique.</td>
                            </tr>
                        <?php else : ?>
                            <?php foreach ($lesjustificatifsHisto as $justif) : ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($justif['datedepot'] ?? ''); ?></td>

                                    <td><?php echo htmlspecialchars($justif['datededebut']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['heuredebut']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['datedefin']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['heurefin']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['nom']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['prénom']); ?></td>
                                    <td><?php echo htmlspecialchars($justif['groupe']); ?></td>
                                    <td class="<?php echo getStatusClass($justif['statut']); ?>">
                                        <?php echo htmlspecialchars($justif['statut']); ?></td>
                                    <td>
                                        <a href="Page_Consultation_Justificatif_Historique.php?id=<?php echo htmlspecialchars($justif['idjustificatif']); ?>" class="action-button">Consulter</a>
                                    </td>
                                    <td>
                                        <a href="Page_Confirmation_Deverouillage.php?id=<?php echo htmlspecialchars($justif['idjustificatif']); ?>" class="action-button">Déverrouiller</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                            <tr class="row-no-result" style="display: none;">
                                <td colspan="11">Aucun justificatif ne correspond à votre recherche.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>

        </div>
    </div>
</div>
<footer class="main-footer"></footer>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // gestion des notifs
        const toast = document.getElementById('toast');
        if (toast) {
            setTimeout(function() {
                toast.classList.add('fade-out');
                setTimeout(function() {
                    if (toast.parentNode) toast.parentNode.removeChild(toast);
                }, 1000);
            }, 4000);
        }

        // filtrage
        const inputDate = document.getElementById('filtreDate');
        const inputEtudiant = document.getElementById('filtreEtudiant');
        const inputGroupe = document.getElementById('filtreGroupe');

        // les ID des deux tableaux à traiter
        const idsTableaux = ['tableauAttente', 'tableauHistorique'];

        function appliquerFiltres() {
            const valeurDate = inputDate.value.toLowerCase();
            const valeurEtudiant = inputEtudiant.value.toLowerCase();
            const valeurGroupe = inputGroupe.value.toLowerCase();

            // On boucle sur tout les tableaux
            idsTableaux.forEach(idTableau => {
                const tableau = document.getElementById(idTableau);
                if (!tableau) return;

                const corpsTableau = tableau.querySelector('tbody');
                // On récupère les lignes de données
                const lignesDonnees = corpsTableau.querySelectorAll('tr:not(.empty-table-message):not(.row-no-result)');
                const ligneAucunResultat = corpsTableau.querySelector('.row-no-result');

                let compteurLignesVisibles = 0;

                lignesDonnees.forEach(ligne => {

                    const dateDepot = ligne.children[0].innerText.toLowerCase();
                    const nom = ligne.children[5].innerText.toLowerCase();
                    const prenom = ligne.children[6].innerText.toLowerCase();
                    const groupe = ligne.children[7].innerText.toLowerCase();
                    const etudiantComplet = nom + " " + prenom;

                    // Vérifications
                    const matchDate = dateDepot.includes(valeurDate);
                    const matchEtudiant = nom.includes(valeurEtudiant) ||
                        prenom.includes(valeurEtudiant) ||
                        etudiantComplet.includes(valeurEtudiant);
                    const matchGroupe = groupe.includes(valeurGroupe);

                    // Affichage de la ligne
                    if (matchDate && matchEtudiant && matchGroupe) {
                        ligne.style.display = '';
                        compteurLignesVisibles++;
                    } else {
                        ligne.style.display = 'none';
                    }
                });

                // Si on a des données dans la base mais que le filtre a tout caché
                if (ligneAucunResultat) {
                    if (compteurLignesVisibles === 0 && lignesDonnees.length > 0) {
                        ligneAucunResultat.style.display = ''; // On affiche le message
                    } else {
                        ligneAucunResultat.style.display = 'none'; // On le cache
                    }
                }
            });
        }

        // les écouteurs
        if(inputDate) inputDate.addEventListener('input', appliquerFiltres);
        if(inputEtudiant) inputEtudiant.addEventListener('input', appliquerFiltres);
        if(inputGroupe) inputGroupe.addEventListener('input', appliquerFiltres);


        // tri
        rendreTableauTriable('tableauAttente');
        rendreTableauTriable('tableauHistorique');

        function rendreTableauTriable(idTableau) {
            const tableau = document.getElementById(idTableau);
            if (!tableau) return;

            const lesEntetes = tableau.querySelectorAll('thead th');
            const corpsDuTableau = tableau.querySelector('tbody');

            lesEntetes.forEach((entete, indexColonne) => {
                if (entete.getAttribute('data-type') !== 'aucun') {
                    entete.addEventListener('click', () => {
                        trierLeTableau(lesEntetes, corpsDuTableau, indexColonne, entete);
                    });
                }
            });
        }

        function trierLeTableau(lesEntetes, corpsDuTableau, index, enteteClique) {
            // On trie les lignes
            const lesLignes = Array.from(corpsDuTableau.querySelectorAll('tr:not(.empty-table-message):not(.row-no-result)'));
            const ligneAucunResultat = corpsDuTableau.querySelector('.row-no-result');

            // Si pas de données, on ne fait rien
            if (lesLignes.length === 0) return;

            const estActuellementCroissant = enteteClique.getAttribute('data-ordre') === 'asc';
            const nouvelOrdre = estActuellementCroissant ? 'desc' : 'asc';
            const multiplicateur = (nouvelOrdre === 'asc') ? 1 : -1;

            lesEntetes.forEach(th => th.removeAttribute('data-ordre'));
            enteteClique.setAttribute('data-ordre', nouvelOrdre);

            const typeDeDonnee = enteteClique.getAttribute('data-type');

            lesLignes.sort((ligneA, ligneB) => {
                const contenuA = ligneA.children[index].innerText.trim();
                const contenuB = ligneB.children[index].innerText.trim();

                if (typeDeDonnee === 'date') {
                    return (convertirDateFrancais(contenuA) - convertirDateFrancais(contenuB)) * multiplicateur;
                } else if (typeDeDonnee === 'heure') {
                    return contenuA.localeCompare(contenuB) * multiplicateur;
                } else {
                    return contenuA.localeCompare(contenuB, 'fr', { numeric: true }) * multiplicateur;
                }
            });

            // réinsertion des lignes triées
            lesLignes.forEach(ligne => corpsDuTableau.appendChild(ligne));

            // On remet la ligne "aucun résultat" à la fin pour qu'elle ne se retrouve pas mélangée
            if (ligneAucunResultat) corpsDuTableau.appendChild(ligneAucunResultat);
        }

        function convertirDateFrancais(dateString) {
            if (!dateString) return new Date(0);
            const parties = dateString.split('/');
            return new Date(parties[2], parties[1] - 1, parties[0]);
        }
    });
</script>

</body>
</html>