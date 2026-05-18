<?php
/*
 * Fichier Modele
 * Contient les fonctions pour l'ADMIN.
*/

/**
 * Fonction pour insérer un nouvel utilisateur.
 * Elle REÇOIT la connexion en paramètre.
 */
function creerUtilisateur($conn1, $role, $UserName, $hash, $nom, $prenom, $idIUT, $groupe, $mail) {
    try {
        $requete = $conn1->prepare( "INSERT INTO Utilisateur (nomUtilisateur, motDePasse, nom, prénom, role, identifiantiut, groupe, email) VALUES (:UserName, :mdp, :nom, :prenom, :role, :idIUT, :groupe, :mail)");
        $requete->bindParam(':UserName', $UserName);
        $requete->bindParam(':mdp', $hash);
        $requete->bindParam(':nom', $nom);
        $requete->bindParam(':prenom', $prenom);
        $requete->bindParam(':role', $role);
        $requete->bindParam(':idIUT', $idIUT);
        $requete->bindParam(':groupe', $groupe);
        $requete->bindParam(':mail', $mail);

        $requete->execute();

        return true; // Succès

    } catch(PDOException $e) {
        return false; // Echec
    }
}
?>