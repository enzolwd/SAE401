<?php
/*
 * Fichier Modele
 * Contient les fonctions pour l'espace étudiant.
*/

/**
 * Fonction qui récupère les absences et justificatifs d'un étudiant.
 * Elle REÇOIT la connexion en paramètre.
 */
function recupererTableauxEtudiant($conn, $idEtudiantConnecte, $isDateView, $dateSelectionnee) {
    $resultatsdujour = [];
    $resultatsJustificatifs = [];

    try {
        if ($idEtudiantConnecte) {
            $sql = "SELECT 
                        TO_CHAR(absence.date, 'DD/MM/YYYY') as date, 
                        TO_CHAR(absence.heure, 'HH24:MI') as heure_formatee, 
                        TO_CHAR(absence.duree, 'HH24:MI') as duree_formatee, 
                        absence.matiere, 
                        absence.prof, 
                        absence.statut, 
                        absence.evaluation 
                     FROM absence
                     WHERE absence.idutilisateur = :idUtilisateur";

            if ($isDateView) {
                $sql .= " AND absence.date = :dateSelectionnee ORDER BY absence.heure";
                $orderBy = "";
            } else {
                $orderBy = "ORDER BY absence.date DESC, absence.heure";
            }
            $sql .= " " . $orderBy;

            $requete_jour = $conn->prepare($sql);
            $requete_jour->bindParam(':idUtilisateur', $idEtudiantConnecte, PDO::PARAM_INT);

            if ($isDateView) {
                $requete_jour->bindParam(':dateSelectionnee', $dateSelectionnee);
            }
            $requete_jour->execute();
            $resultatsdujour = $requete_jour->fetchAll(PDO::FETCH_ASSOC);

            $sql_justif = "SELECT
                                Justificatif.idjustificatif,
                                TO_CHAR(Justificatif.datedebut, 'DD/MM/YYYY') as datededebut,
                                TO_CHAR(Justificatif.heuredebut, 'HH24:MI') as heuredebut,
                                TO_CHAR(Justificatif.datefin, 'DD/MM/YYYY') as datedefin,
                                TO_CHAR(Justificatif.heurefin, 'HH24:MI') as heurefin,
                                Justificatif.statut
                            FROM Justificatif
                            JOIN Absence ON Absence.idjustificatif = Justificatif.idjustificatif
                            JOIN Utilisateur ON Utilisateur.idutilisateur = Absence.idutilisateur
                            WHERE Utilisateur.idutilisateur = :idutilisateur
                            GROUP BY Justificatif.idjustificatif
                            ORDER BY Justificatif.idjustificatif DESC";

            $requete_justif = $conn->prepare($sql_justif);
            $requete_justif->bindParam(':idutilisateur', $idEtudiantConnecte, PDO::PARAM_INT);
            $requete_justif->execute();
            $resultatsJustificatifs = $requete_justif->fetchAll(PDO::FETCH_ASSOC);
        }

    } catch(PDOException $e) {
        $resultatsdujour = [];
        $resultatsJustificatifs = [];
    }

    return [$resultatsdujour, $resultatsJustificatifs];
}

/**
 * Fonction qui dépose un justificatif.
 * Elle REÇOIT la connexion en paramètre.
 */
function deposerJustificatif($conn1, $idUtilisateurConnecte, $datedebut, $heuredebut, $datefin, $heurefin, $motif, $commentaire, $cheminFichierPourBDD) {
    try {
        $timestampDebutJustificatif = $datedebut . ' ' . $heuredebut;
        $timestampFinJustificatif = $datefin . ' ' . $heurefin;

        $requeteVerifierdoublons = $conn1->prepare("SELECT * FROM Absence WHERE idutilisateur = :idutilisateur
                                                        AND (date + heure + duree) <= TO_TIMESTAMP(:timestampfin, 'YYYY-MM-DD HH24:MI')
                                                        AND (date + heure) >= TO_TIMESTAMP(:timestampdebut, 'YYYY-MM-DD HH24:MI')
                                                        AND statut != 'Plus valable' AND statut != 'plus valable' 
                                                        AND statut != 'non justifie'
                                                        AND statut != 'demande de révision' ");
        $requeteVerifierdoublons->bindParam(':idutilisateur', $idUtilisateurConnecte );
        $requeteVerifierdoublons->bindParam(':timestampdebut', $timestampDebutJustificatif);
        $requeteVerifierdoublons->bindParam(':timestampfin', $timestampFinJustificatif);
        $requeteVerifierdoublons->execute();

        $DejaUnJustificatif = (int)$requeteVerifierdoublons->fetchColumn();

        if ($DejaUnJustificatif === 0) {
            $requeteVerifierUtilite = $conn1->prepare("SELECT * FROM Absence WHERE idutilisateur = :idutilisateur
                                                        AND (date + heure + duree) <= TO_TIMESTAMP(:timestampfin, 'YYYY-MM-DD HH24:MI')
                                                        AND (date + heure) >= TO_TIMESTAMP(:timestampdebut, 'YYYY-MM-DD HH24:MI')");
            $requeteVerifierUtilite->bindParam(':idutilisateur', $idUtilisateurConnecte );
            $requeteVerifierUtilite->bindParam(':timestampdebut', $timestampDebutJustificatif);
            $requeteVerifierUtilite->bindParam(':timestampfin', $timestampFinJustificatif);
            $requeteVerifierUtilite->execute();

            $nbabsence = $requeteVerifierUtilite->rowCount();

            if ($nbabsence !== 0) {
                $requete = $conn1->prepare("INSERT INTO justificatif 
                    (datedebut, datefin, heuredebut, heurefin, commentaireeleve, commentairerespon, statut, motifeleve, motifrespon, fichier) 
                VALUES 
                    ( :datedebut, :datefin, :heuredebut, :heurefin, :commentaire, null, 'en attente', :motif, null, :cheminfichier)");
                $requete->bindParam(':datedebut', $datedebut);
                $requete->bindParam(':datefin', $datefin);
                $requete->bindParam(':heuredebut', $heuredebut);
                $requete->bindParam(':heurefin', $heurefin);
                $requete->bindParam(':commentaire', $commentaire);
                $requete->bindParam(':motif', $motif);
                $requete->bindParam(':cheminfichier', $cheminFichierPourBDD);
                $requete->execute();
                $justificatifID = $conn1->lastInsertId();

                $requeteAbsencesLiees = $conn1->prepare("UPDATE Absence SET idjustificatif = :justificatifID, statut = 'en attente' WHERE idutilisateur = :idutilisateur
                                                        AND (date + heure + duree) <= TO_TIMESTAMP(:timestampfin, 'YYYY-MM-DD HH24:MI')
                                                        AND (date + heure) >= TO_TIMESTAMP(:timestampdebut, 'YYYY-MM-DD HH24:MI')
                                                        AND statut != 'refusé' AND statut != 'accepté'");
                $requeteAbsencesLiees->bindParam(':justificatifID', $justificatifID);
                $requeteAbsencesLiees->bindParam(':idutilisateur', $idUtilisateurConnecte );
                $requeteAbsencesLiees->bindParam(':timestampdebut', $timestampDebutJustificatif);
                $requeteAbsencesLiees->bindParam(':timestampfin', $timestampFinJustificatif);
                $requeteAbsencesLiees->execute();

                $stmtUsedIds = $conn1->query("SELECT DISTINCT idjustificatif FROM Absence WHERE idjustificatif IS NOT NULL");
                $usedJustificatifIds = $stmtUsedIds->fetchAll(PDO::FETCH_COLUMN);
                $stmtNonValableIds = $conn1->prepare("SELECT idjustificatif FROM Justificatif WHERE LOWER(statut) = 'plus valable' OR LOWER(statut) = 'demande de révision'");
                $stmtNonValableIds->execute();
                $nonValableJustificatifIds = $stmtNonValableIds->fetchAll(PDO::FETCH_COLUMN);
                $idsToDelete = array_diff($nonValableJustificatifIds, $usedJustificatifIds);

                if (!empty($idsToDelete)) {
                    $placeholders = implode(',', array_fill(0, count($idsToDelete), '?'));
                    $recupererFichier = $conn1->prepare("SELECT fichier FROM Justificatif WHERE idjustificatif IN ($placeholders)");
                    $recupererFichier->execute(array_values($idsToDelete));
                    $fichiersASupprimer = $recupererFichier->fetchAll(PDO::FETCH_COLUMN);
                    foreach ($fichiersASupprimer as $fichier) {
                        if (!empty($fichier) && file_exists($fichier)) {
                            @unlink($fichier);
                        }
                    }
                    $stmtDelete = $conn1->prepare("DELETE FROM Justificatif WHERE idjustificatif IN ($placeholders)");
                    $stmtDelete->execute(array_values($idsToDelete));
                }

                return "succes";
            } else{
                return "inutile";
            }
        }
        else{
            return "conflict";
        }
    } catch(PDOException $e) {
        return "db_error";
    }
}

/**
 * Fonction qui récupère le motif d'un justificatif (côté étudiant).
 * Elle REÇOIT la connexion en paramètre.
 */
function recupererMotifEtudiant($conn1, $justificatifID) {
    $motifDetails = null;
    try {
        $sql = "SELECT
                    motifrespon,
                    commentairerespon
                FROM Justificatif
                WHERE idjustificatif = :id
                LIMIT 1";

        $requete = $conn1->prepare($sql);
        $requete->bindParam(':id', $justificatifID, PDO::PARAM_INT);
        $requete->execute();
        $motifDetails = $requete->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $motifDetails = false;
    }
    return $motifDetails;
}
?>