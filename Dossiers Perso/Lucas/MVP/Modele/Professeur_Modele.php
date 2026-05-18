<?php

/**
 * Fonction qui récupère les rattrapages pour un professeur.
 * Elle REÇOIT la connexion en paramètre.
 */
function recupererRattrapagesProf($conn1, $idProf) {
    $lesRattrapages = [];
    try {
        $recupererNomPrenom = $conn1->prepare("SELECT nom, prénom FROM Utilisateur WHERE idutilisateur = :idProf");
        $recupererNomPrenom->bindValue(':idProf', $idProf);
        $recupererNomPrenom->execute();

        $nomPrenom = $recupererNomPrenom->fetch(PDO::FETCH_ASSOC);

        if ($nomPrenom) {
            $nomComplet = $nomPrenom['nom'] . ' ' . $nomPrenom['prénom'];
            $nomCompletLower = strtolower($nomComplet);
        } else {
            $nomCompletLower = '';
        }


        $sql = "SELECT TO_CHAR(Absence.date, 'DD/MM/YYYY') as date,
                       TO_CHAR(Absence.heure, 'HH24:MI') as heure,
                       TO_CHAR(Absence.duree, 'HH24:MI') as duree, 
                       Absence.matiere, Utilisateur.nom, Utilisateur.prénom,
                       Utilisateur.groupe, Utilisateur.email
        FROM Absence
        JOIN Utilisateur ON Utilisateur.idutilisateur = Absence.idUtilisateur
        WHERE LOWER(Absence.statut) = 'accepté' AND Absence.evaluation IS TRUE
        AND LOWER(prof) = :nomCompletLower
        ORDER BY date";
        $requeteRattrapages = $conn1->prepare($sql);
        $requeteRattrapages->bindValue(':nomCompletLower', $nomCompletLower);
        $requeteRattrapages->execute();
        $lesRattrapages = $requeteRattrapages->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $lesRattrapages = false;
    }
    return $lesRattrapages;
}
?>