<?php

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
        $role = $requeteRole->fetchColumn();

        // on récupère l'ID de l'utilisateur
        $requeteId = $conn1->prepare( "SELECT idutilisateur FROM Utilisateur WHERE nomutilisateur = :nom");
        $requeteId->bindParam(':nom', $username);
        $requeteId->execute();
        $idUtilisateur = $requeteId->fetchColumn();

        // tentatives échouées
        $reqTentatives = $conn1->prepare("SELECT tentatives_echouees FROM Utilisateur WHERE nomutilisateur = :nom");
        $reqTentatives->bindParam(':nom', $username);
        $reqTentatives->execute();
        $tentatives = $reqTentatives->fetchColumn();

        // date fin blocage
        $reqBlocage = $conn1->prepare("SELECT date_fin_blocage FROM Utilisateur WHERE nomutilisateur = :nom");
        $reqBlocage->bindParam(':nom', $username);
        $reqBlocage->execute();
        $blocage = $reqBlocage->fetchColumn();

        // dernière tentative
        $reqDerniere = $conn1->prepare("SELECT derniere_tentative FROM Utilisateur WHERE nomutilisateur = :nom");
        $reqDerniere->bindParam(':nom', $username);
        $reqDerniere->execute();
        $derniere = $reqDerniere->fetchColumn();

        return [
            'hash' => $hash,
            'role' => $role,
            'idUtilisateur' => $idUtilisateur,
            'tentatives' => $tentatives,
            'blocage' => $blocage,
            'derniere_tentative' => $derniere
        ];

    } catch(PDOException $e) {
        return false;
    }
}

