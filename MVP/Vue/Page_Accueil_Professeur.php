<?php
require_once '../Presentation/Professeur_Accueil_Presenteur.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Professor Page</title>
    <link rel="stylesheet" href="css/Style_Page_Accueil_Professeur.css">
</head>
<body>

<div class="header-main">
    <div class="logo-section">
        <img src="images/logo_uphf.png" alt="Polytechnic University Logo">
    </div>
    <div class="header-right">
        <p>DIGITAL WORKSPACE</p>
        <a href="../Presentation/Deconnexion_Presenteur.php" class="bouton-deconnexion">Logout</a>
    </div>
</div>

<div class="container">

    <h1 class="titre-principal">Make-up Exam List</h1>

    <div class="filtres-container">
        <input type="text" id="filtreDate" placeholder="Date (mm/dd/yyyy)">
        <input type="text" id="filtreEtudiant" placeholder="Student (Last or First Name)">
        <input type="text" id="filtreGroupe" placeholder="Group">
        <input type="text" id="filtreMatiere" placeholder="Subject">
    </div>
    <section class="tableau-wrapper">
        <div class="table-container">
            <table class="tableau" id="tableauRattrapages">
                <thead>
                <tr>
                    <th data-type="date">Date</th>
                    <th data-type="heure">Time</th>
                    <th data-type="heure">Duration</th>
                    <th data-type="texte">Subject</th>
                    <th data-type="texte">Last Name</th>
                    <th data-type="texte">First Name</th>
                    <th data-type="texte">Group</th>
                    <th data-type="texte">Email</th>
                    <th data-type="texte">Authorized Make-up</th>
                </tr>
                </thead>

                <tbody>
                <?php if (empty($lesRattrapages)) : ?>
                    <tr class="empty-table-message">
                        <td colspan="9">No upcoming make-up exams.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($lesRattrapages as $ratrapage) : ?>
                        <tr>
                            <td>
                                <?php
                                // conversion de date française vers anglaise
                                $parts = explode('/', $ratrapage['date']);
                                if(count($parts) == 3) {
                                    echo htmlspecialchars($parts[1] . '/' . $parts[0] . '/' . $parts[2]);
                                } else {
                                    echo htmlspecialchars($ratrapage['date']);
                                }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($ratrapage['heure']); ?></td>
                            <td><?php echo htmlspecialchars($ratrapage['duree']); ?></td>
                            <td><?php echo htmlspecialchars($ratrapage['matiere']); ?></td>
                            <td><?php echo htmlspecialchars($ratrapage['nom']); ?></td>
                            <td><?php echo htmlspecialchars($ratrapage['prénom']); ?></td>
                            <td><?php echo htmlspecialchars($ratrapage['groupe']); ?></td>
                            <td><?php echo htmlspecialchars($ratrapage['email']); ?></td>
                            <?php
                            if ($ratrapage['statut'] === 'accepté') {
                                $classe_statut = 'status-oui';
                                $texte_statut = 'YES';
                            } else {
                                $classe_statut = 'status-non';
                                $texte_statut = 'NO';
                            }
                            ?>

                            <td class="<?php echo $classe_statut; ?>">
                                <?php echo $texte_statut; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>

</div>

<footer class="main-footer"></footer>

<script>
    document.addEventListener('DOMContentLoaded', function () {

        const tableau = document.getElementById('tableauRattrapages');
        const lesEntetes = tableau.querySelectorAll('thead th');
        const corpsDuTableau = tableau.querySelector('tbody');

        const inputDate = document.getElementById('filtreDate');
        const inputEtudiant = document.getElementById('filtreEtudiant');
        const inputMatiere = document.getElementById('filtreMatiere');
        const inputGroupe = document.getElementById('filtreGroupe');

        function appliquerFiltres() {
            const valeurDate = inputDate.value.toLowerCase();
            const valeurEtudiant = inputEtudiant.value.toLowerCase();
            const valeurMatiere = inputMatiere.value.toLowerCase();
            const valeurGroupe = inputGroupe.value.toLowerCase();

            const lignes = corpsDuTableau.querySelectorAll('tr');

            lignes.forEach(ligne => {
                if(ligne.classList.contains('empty-table-message')) return;

                const dateTexte = ligne.children[0].innerText.toLowerCase();
                const matiereTexte = ligne.children[3].innerText.toLowerCase();
                const nomTexte = ligne.children[4].innerText.toLowerCase();
                const prenomTexte = ligne.children[5].innerText.toLowerCase();
                const groupeTexte = ligne.children[6].innerText.toLowerCase();

                const etudiantComplet = nomTexte + " " + prenomTexte;

                const matchDate = dateTexte.includes(valeurDate);
                const matchMatiere = matiereTexte.includes(valeurMatiere);
                const matchGroupe = groupeTexte.includes(valeurGroupe);

                const matchEtudiant = nomTexte.includes(valeurEtudiant) ||
                    prenomTexte.includes(valeurEtudiant) ||
                    etudiantComplet.includes(valeurEtudiant);

                if (matchDate && matchMatiere && matchEtudiant && matchGroupe) {
                    ligne.style.display = '';
                } else {
                    ligne.style.display = 'none';
                }
            });
        }

        inputDate.addEventListener('input', appliquerFiltres);
        inputEtudiant.addEventListener('input', appliquerFiltres);
        inputMatiere.addEventListener('input', appliquerFiltres);
        inputGroupe.addEventListener('input', appliquerFiltres);

        lesEntetes.forEach((entete, indexColonne) => {
            entete.addEventListener('click', () => {
                trierLeTableau(indexColonne, entete);
            });
        });

        function trierLeTableau(index, enteteClique) {
            const lesLignes = Array.from(corpsDuTableau.querySelectorAll('tr'));
            if (lesLignes.length === 1 && lesLignes[0].classList.contains('empty-table-message')) return;

            const estActuellementCroissant = enteteClique.getAttribute('data-ordre') === 'asc';
            const nouvelOrdre = estActuellementCroissant ? 'desc' : 'asc';

            lesEntetes.forEach(th => th.removeAttribute('data-ordre'));
            enteteClique.setAttribute('data-ordre', nouvelOrdre);

            const multiplicateur = (nouvelOrdre === 'asc') ? 1 : -1;
            const typeDeDonnee = enteteClique.getAttribute('data-type');

            lesLignes.sort((ligneA, ligneB) => {
                const contenuA = ligneA.children[index].innerText.trim();
                const contenuB = ligneB.children[index].innerText.trim();

                if (typeDeDonnee === 'date') {
                    const dateA = convertirDateUS(contenuA);
                    const dateB = convertirDateUS(contenuB);
                    return (dateA - dateB) * multiplicateur;
                }
                else if (typeDeDonnee === 'heure') {
                    return contenuA.localeCompare(contenuB) * multiplicateur;
                }
                else {
                    return contenuA.localeCompare(contenuB, 'en', { numeric: true }) * multiplicateur;
                }
            });
            lesLignes.forEach(ligne => corpsDuTableau.appendChild(ligne));
        }

        // pour le format de la date en anglais
        function convertirDateUS(dateString) {
            if (!dateString) return new Date(0);
            const parties = dateString.split('/');
            // parties[0] = Mois, parties[1] = Jour
            const mois = parseInt(parties[0], 10) - 1;
            const jour = parseInt(parties[1], 10);
            const annee = parseInt(parties[2], 10);
            return new Date(annee, mois, jour);
        }
    });
</script>

</body>
</html>