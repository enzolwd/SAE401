<?php
/*
 * Fichier Modele
 * Contient les fonctions pour la connexion.
*/

/**
 * Fonction qui cherche un utilisateur dans la base de données par son nom.
 * Elle REÇOIT la connexion en paramètre.
 */
function trouverUtilisateurParNom($conn1, $username) {
    try {
        // on récupère le mot de passe
        $requete = $conn1->prepare( "SELECT motdepasse FROM Utilisateur WHERE nomutilisateur = :nom");
        $requete->bindParam(':nom', $username);
        $requete->execute();
        $hash = $requete->fetchColumn();

        // on récupère le rôle
        $requeteRole = $conn1->prepare( "SELECT role FROM Utilisateur WHERE nomutilisateur = :nom");
        $requeteRole->bindParam(':nom', $username);
        $requeteRole->execute();
        $role = $requeteRole->fetchColumn(); // Note: cette ligne est écrasée si $hash est valide dans le Présentateur

        // on récupère l'ID de l'utilisateur
        $requeteId = $conn1->prepare( "SELECT idutilisateur FROM Utilisateur WHERE nomutilisateur = :nom");
        $requeteId->bindParam(':nom', $username);
        $requeteId->execute();
        $idUtilisateur = $requeteId->fetchColumn();

        // On retourne toutes les informations
        return [
            'hash' => $hash,
            'role' => $role,
            'idUtilisateur' => $idUtilisateur
        ];

    } catch(PDOException $e) {
        return false; // Erreur de requête
    }
}
?>