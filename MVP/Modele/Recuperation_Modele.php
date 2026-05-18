<?php

function verifierEmailEtRecupererInfos($conn, $email) {
    try {
        $sql = "SELECT idUtilisateur, nom, prénom FROM Utilisateur WHERE email = :email";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return false;
    }
}

function stockerToken($conn, $idUtilisateur, $token) {
    try {
        // Le token expire dans 1 heure
        $sql = "UPDATE Utilisateur 
                SET token_recuperation = :token, 
                    date_expiration_token = NOW() + INTERVAL '1 hour' 
                WHERE idUtilisateur = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':token' => $token, ':id' => $idUtilisateur]);
    } catch (PDOException $e) {
        return false;
    }
}

function verifierToken($conn, $token) {
    try {
        // On cherche un utilisateur avec ce token dont la date d'expiration n'est pas passée
        $sql = "SELECT idUtilisateur FROM Utilisateur 
                WHERE token_recuperation = :token 
                AND date_expiration_token > NOW()";
        $stmt = $conn->prepare($sql);
        $stmt->execute([':token' => $token]);
        return $stmt->fetchColumn();
    } catch (PDOException $e) {
        return false;
    }
}

function mettreAJourMotDePasse($conn, $idUtilisateur, $nouveauMdpHash) {
    try {
        // on change le mot de passe et on supprime le token
        $sql = "UPDATE Utilisateur 
                SET motDePasse = :mdp, 
                    token_recuperation = NULL, 
                    date_expiration_token = NULL 
                WHERE idUtilisateur = :id";
        $stmt = $conn->prepare($sql);
        return $stmt->execute([':mdp' => $nouveauMdpHash, ':id' => $idUtilisateur]);
    } catch (PDOException $e) {
        return false;
    }
}
?>