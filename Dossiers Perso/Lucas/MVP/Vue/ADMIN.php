<?php
session_start();

// vérifier si l'utilisateur s'est connecté
if (!isset($_SESSION['idUtilisateur']) || $_SESSION['role'] != 'ADMIN') {
    header('Location: Page_De_Connexion.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Page ADMIN</title>
    <link rel="stylesheet" href="css/Style_ADMIN.css">
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

    <?php
    // message d'erreur et de réussite de création de l'utilisateur
    if (isset($_GET['success']) && $_GET['success'] === 'true') {
        echo '<div class="success-message">Succès : L\'utilisateur a été créé avec succès.</div>';}

    elseif (isset($_GET['error']) && $_GET['error'] === 'duplicate_idIUT') {
        echo '<div class="error-message">Erreur : L\'identifiant IUT et/ou l\'email saisi existe déjà dans la base de données.</div>';}
    ?>

    <form method="post" action="../Presentation/Ajouter_Utilisateur_Presenteur.php">

        <div class="form-group">
            <label for="role">Rôle</label>
            <select id="role" name="role" required>
                <option value="" disabled selected>Choisir un rôle...</option>
                <option value="Etudiant">Etudiant</option>
                <option value="Professeur">Professeur</option>
                <option value="Secretaire">Secretaire</option>
                <option value="Responsable Pedagogique">Responsable Pedagogique</option>
                <option value="ADMIN">ADMIN</option>
            </select>
        </div>

        <div class="form-group">
            <label>Nom d'utilisateur</label>
            <input id="UserName" name="UserName" placeholder="Entrez un nom d'utilisateur..." required>
        </div>

        <div class="form-group">
            <label>Mot De Passe</label>
            <input type="password" id="mdp" name="mdp" placeholder="Entrez un mot de passe..." required>
        </div>

        <div class="form-group">
            <label>Nom</label>
            <input id="nom" name="nom" placeholder="Entrez le nom..." required>
        </div>

        <div class="form-group">
            <label>Prénom</label>
            <input id="prenom" name="prenom" placeholder="Entrez le prénom..." required>
        </div>

        <div class="form-group">
            <label>E-mail</label>
            <input type="email" id="mail" name="mail" placeholder="Entrez l'adresse mail..." required>
        </div>

        <div id="divIdIUT" style="display: none;">
            <div class="form-group">
                <label>Identifiant IUT</label>
                <input type="number" id="idIUT" name="idIUT" placeholder="Entrez l'identifiant..." required>
            </div>
        </div>

        <div id="groupeEtu" style="display: none;">
            <div class="form-group">
                <label>Groupe</label>
                <input id="groupe" name="groupe" placeholder="Entrez le groupe" required>
            </div>
        </div>

        <button type="submit" name="CréerUtilisateur" value="CréerUtilisateur" class="action-button">
            Créer un utilisateur
        </button>

    </form>

</div>

<footer class="main-footer"></footer>

<script>
    document.addEventListener('DOMContentLoaded', (event) => {

        const roleSelect = document.getElementById('role');
        const idIUTDiv = document.getElementById('divIdIUT');
        const idIUTInput = document.getElementById('idIUT');

        const groupeEtu = document.getElementById('groupeEtu');
        const groupe = document.getElementById('groupe');

        // Fonction pour vérifier qu'on a bien sélectionné étudiant
        function toggleIdIUTField() {
            // Si l'option sélectionnée est 'étudiant'
            if (roleSelect.value === 'Etudiant') {
                // Afficher le champ
                idIUTDiv.style.display = 'block';
                groupeEtu.style.display = 'block';
                // mettre le champ en required
                idIUTInput.setAttribute('required', 'required');
                groupe.setAttribute('required', 'required');
            } else {
                // Masquer le champ
                idIUTDiv.style.display = 'none';
                groupeEtu.style.display = 'none';
                // Retirer required pour ne pas bloquer la création d'un utilisateur qui ne sera pas étudiant
                idIUTInput.removeAttribute('required');
                groupe.removeAttribute('required');
            }
        }

        // permet de récupérer l'information dans la liste (récupérer si étudiant)
        roleSelect.addEventListener('change', toggleIdIUTField);

        toggleIdIUTField();
    });
</script>

</body>
</html>