<?php
session_start();

// --- 1. Paramètres de connexion (PostgreSQL - srv-sae12) ---
// J'utilise les infos de votre capture d'écran
$serveur = "srv-sae12";
$port = "5432"; // Port par défaut de PostgreSQL
$utilisateur_db = "usersae301";
$mot_de_passe_db = "psae301";
$nom_db = "bddsae301";

// --- 2. Vérification de la soumission du formulaire ---
// (On suppose que le 'name' du bouton est 'identifiants')
if (isset($_POST['identifiants'])) {

    // --- 3. Connexion à la BDD (CORRIGÉ POUR POSTGRESQL) ---
    try {
        // CORRECTION : Ce n'est pas "mysql:" mais "pgsql:"
        $bdd = new PDO("pgsql:host=$serveur;port=$port;dbname=$nom_db", $utilisateur_db, $mot_de_passe_db);
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        // En cas d'erreur de connexion, on redirige avec une erreur
        $_SESSION['erreur_connexion'] = "Erreur de connexion à la base de données.";
        // On suppose que la page de login s'appelle "Connexion_Traitement.php"
        header("Location: Connexion_Traitement.php?erreur=db");
        exit();
    }

    // --- 4. Récupération et nettoyage des données ---
    $username = $_POST['UserName'];
    $password_saisi = $_POST['mdp'];

    // --- 5. Vérification des identifiants (AVEC VOS NOMS DE COLONNES) ---
    try {
        // Noms de colonnes de votre BDD
        $requete = $bdd->prepare('SELECT * FROM utilisateur WHERE nomutilisateur = ?');
        $requete->execute([$username]);
        $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

        // On vérifie si l'utilisateur existe ET si le mot de passe est correct
        if ($utilisateur && password_verify($password_saisi, $utilisateur['motdepasse'])) {

            // --- 6. Succès : Connexion réussie ---
            $_SESSION['user_id'] = $utilisateur['idutilisateur'];
            $_SESSION['username'] = $utilisateur['nomutilisateur'];
            $_SESSION['role'] = $utilisateur['role'];

            // --- 7. Redirection (CHEMINS CORRIGÉS) ---
            // Puisque tout est dans le même dossier, on n'utilise plus "../"
            switch ($utilisateur['role']) {
                case 'etudiant':
                    // Cible: Page_D'accueil_Etudiant.html (dans le même dossier)
                    header("Location: Page_D'accueil_Etudiant.html");
                    break;
                case 'responsable':
                    // (Assurez-vous que la page responsable est aussi dans ce dossier)
                    header("Location: Page_D'accueil_Responsable.html");
                    break;
                case 'secretaire':
                    // (Assurez-vous que la page secrétaire est aussi dans ce dossier)
                    header("Location: Page_D'accueil_Secretaire.php");
                    break;
                default:
                    $_SESSION['erreur_connexion'] = "Rôle utilisateur non défini.";
                    header("Location: Connexion_Traitement.php?erreur=role");
                    break;
            }
            exit();

        } else {
            // --- 8. Échec : Mauvais identifiant ou mot de passe ---
            $_SESSION['erreur_connexion'] = "Nom d'utilisateur ou mot de passe incorrect.";
            header("Location: Connexion_Traitement.php?erreur=identifiants");
            exit();
        }

    } catch (PDOException $e) {
        $_SESSION['erreur_connexion'] = "Erreur lors de la vérification des identifiants.";
        header("Location: Connexion_Traitement.php?erreur=requete");
        exit();
    }

} else {
    // --- CORRECTION DE LA BOUCLE INFINIE ---
    // Si quelqu'un accède à ce script directement
    // au lieu de le faire boucler, on le renvoie à la page de login.
    header("Location: Page_De_Connexion.php"); // MODIFIÉ
    exit();
}
?>