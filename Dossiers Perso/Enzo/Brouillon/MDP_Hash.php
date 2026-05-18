<?php
// Définit les mots de passe en clair que vous voulez utiliser
$pass_etudiant = '1234';
$pass_responsable = 'admin123';
$pass_secretaire = 'sec123';

// Hache ces mots de passe avec l'algorithme sécurisé
$hash_etudiant = password_hash($pass_etudiant, PASSWORD_DEFAULT);
$hash_responsable = password_hash($pass_responsable, PASSWORD_DEFAULT);
$hash_secretaire = password_hash($pass_secretaire, PASSWORD_DEFAULT);

// Affiche les résultats en clair pour que vous puissiez les copier
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Générateur de Hash</title>
    <style>
        body { font-family: sans-serif; padding: 20px; line-height: 1.6; }
        b { color: #004d66; }
        code { background-color: #f0f0f0; padding: 3px 5px; border-radius: 4px; }
    </style>
</head>
<body>
<h1>Résultats du Hachage</h1>
<p>Copiez-collez ces longs textes dans votre script SQL (dans DataGrip).</p>
<hr>

<p>
    <b>Hash pour 'etudiant' (mdp: 1234):</b><br>
    <code><?php echo $hash_etudiant; ?></code>
</p>

<p>
    <b>Hash pour 'responsable' (mdp: admin123):</b><br>
    <code><?php echo $hash_responsable; ?></code>
</p>

<p>
    <b>Hash pour 'secretaire' (mdp: sec123):</b><br>
    <code><?php echo $hash_secretaire; ?></code>
</p>

</body>
</html>