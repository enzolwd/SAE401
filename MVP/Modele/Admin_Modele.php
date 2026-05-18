<?php
/* Contient les fonctions pour l'ADMIN */


function creerUtilisateur($conn1, $role, $UserName, $hash, $nom, $prenom, $idIUT, $groupe, $mail) {
    try {
        $requete = $conn1->prepare( "INSERT INTO utilisateur (nomUtilisateur, motDePasse, nom, prénom, role, identifiantiut, groupe, email) VALUES (:UserName, :mdp, :nom, :prenom, :role, :idIUT, :groupe, :mail)");
        $requete->bindParam(':UserName', $UserName);
        $requete->bindParam(':mdp', $hash);
        $requete->bindParam(':nom', $nom);
        $requete->bindParam(':prenom', $prenom);
        $requete->bindParam(':role', $role);
        $requete->bindParam(':idIUT', $idIUT);
        $requete->bindParam(':groupe', $groupe);
        $requete->bindParam(':mail', $mail);

        $requete->execute();

        return true;

    } catch(PDOException $e) {
        return false;
    }
}
?>