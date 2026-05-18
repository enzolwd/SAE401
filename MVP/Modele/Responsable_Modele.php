<?php

function recupererTableauxResponsable($conn1) {
    $lesjustificatifs = [];
    $lesjustificatifsHisto = [];

    try {
        // requête pour les justificatifs en attente
        $requeteAvoirJustificatifs = $conn1->prepare("
            SELECT
                Justificatif.idjustificatif,
                TO_CHAR(Justificatif.date_depot, 'DD/MM/YYYY') as datedepot,
                TO_CHAR(Justificatif.datedebut, 'DD/MM/YYYY') as datededebut,
                TO_CHAR(Justificatif.heuredebut, 'HH24:MI') as heuredebut,
                TO_CHAR(Justificatif.datefin, 'DD/MM/YYYY') as datedefin,
                TO_CHAR(Justificatif.heurefin, 'HH24:MI') as heurefin,
                Utilisateur.nom as nom,
                Utilisateur.prénom as prénom,
                Utilisateur.groupe as groupe
            FROM Justificatif
            JOIN Absence ON Absence.idjustificatif = Justificatif.idjustificatif
            JOIN Utilisateur ON Utilisateur.idutilisateur = Absence.idutilisateur
            WHERE Justificatif.statut = 'en attente'
            GROUP BY Justificatif.idjustificatif, Utilisateur.nom, Utilisateur.prénom, Utilisateur.groupe
            ORDER BY Justificatif.idjustificatif");
        $requeteAvoirJustificatifs->execute();
        $lesjustificatifs = $requeteAvoirJustificatifs->fetchAll(PDO::FETCH_ASSOC);

        // Requête pour l'historique
        $requeteAvoirJustificatifsHistorique = $conn1->prepare("
            SELECT
                Justificatif.idjustificatif,
                TO_CHAR(Justificatif.date_depot, 'DD/MM/YYYY') as datedepot, -- AJOUT ICI
                TO_CHAR(Justificatif.datedebut, 'DD/MM/YYYY') as datededebut,
                TO_CHAR(Justificatif.heuredebut, 'HH24:MI') as heuredebut,
                TO_CHAR(Justificatif.datefin, 'DD/MM/YYYY') as datedefin,
                TO_CHAR(Justificatif.heurefin, 'HH24:MI') as heurefin,
                Justificatif.statut as statut,
                Utilisateur.nom as nom,
                Utilisateur.prénom as prénom,
                Utilisateur.groupe as groupe
            FROM Justificatif
            JOIN Absence ON Absence.idjustificatif = Justificatif.idjustificatif
            JOIN Utilisateur ON Utilisateur.idutilisateur = Absence.idutilisateur
            WHERE Justificatif.statut = 'refusé' OR Justificatif.statut = 'accepté'
            GROUP BY Justificatif.idjustificatif, Utilisateur.nom, Utilisateur.prénom, Utilisateur.groupe 
            ORDER BY Justificatif.idjustificatif DESC");
        $requeteAvoirJustificatifsHistorique->execute();
        $lesjustificatifsHisto = $requeteAvoirJustificatifsHistorique->fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e) {
        $lesjustificatifs = [];
        $lesjustificatifsHisto = [];
    }

    return [$lesjustificatifs, $lesjustificatifsHisto];
}

/**
 * Fonction qui récupère les détails d'un justificatif en attente.
 * Elle REÇOIT la connexion en paramètre.
 */
function recupererDetailsJustificatifAttente($conn1, $justificatifID) {
    try {
        $sql = "SELECT
                    Justificatif.idjustificatif,
                    TO_CHAR(Justificatif.datedebut, 'DD/MM/YYYY') as datedebut_f,
                    TO_CHAR(Justificatif.heuredebut, 'HH24:MI') as heuredebut_f,
                    TO_CHAR(Justificatif.datefin, 'DD/MM/YYYY') as datefin_f,
                    TO_CHAR(Justificatif.heurefin, 'HH24:MI') as heurefin_f,
                    Justificatif.motifeleve,
                    Justificatif.commentaireeleve,
                    Justificatif.fichier1,
                    Justificatif.fichier2,
                    Justificatif.date_depot,
                    Utilisateur.email,
                    Utilisateur.nom,
                    Utilisateur.prénom
                FROM Justificatif
                JOIN Absence ON Absence.idjustificatif = Justificatif.idjustificatif
                JOIN Utilisateur ON Utilisateur.idutilisateur = Absence.idutilisateur
                WHERE Justificatif.idjustificatif = :id
                LIMIT 1";
        $requete = $conn1->prepare($sql);
        $requete->bindParam(':id', $justificatifID, PDO::PARAM_INT);
        $requete->execute();
        return $requete->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return false;
    }
}

/**
 * Fonction qui récupère les détails d'un justificatif de l'historique.
 * Elle REÇOIT la connexion en paramètre.
 */
function recupererDetailsJustificatifHistorique($conn1, $justificatifID) {
    try {
        $sql = "SELECT
                j.idjustificatif,
                TO_CHAR(j.datedebut, 'DD/MM/YYYY') as datedebut_f,
                TO_CHAR(j.heuredebut, 'HH24:MI') as heuredebut_f,
                TO_CHAR(j.datefin, 'DD/MM/YYYY') as datefin_f,
                TO_CHAR(j.heurefin, 'HH24:MI') as heurefin_f,
                j.motifeleve,
                j.commentaireeleve,
                j.fichier1,
                j.fichier2,
                u.email,
                u.email,
                j.statut,
                j.motifrespon,
                j.date_depot,
                j.commentairerespon,
                u.nom,
                u.prénom
            FROM Justificatif j
            JOIN Absence a ON a.idjustificatif = j.idjustificatif
            JOIN Utilisateur u ON u.idutilisateur = a.idutilisateur
            WHERE j.idjustificatif = :id
            GROUP BY j.idjustificatif, u.nom, u.prénom, u.email
            LIMIT 1";
        $requete = $conn1->prepare($sql);
        $requete->bindParam(':id', $justificatifID, PDO::PARAM_INT);
        $requete->execute();
        return $requete->fetch(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        return false;
    }
}

/**
 * Fonction qui accepte un justificatif.
 * Elle REÇOIT la connexion en paramètre.
 */
function accepterJustificatif($conn1, $justificatifID, $commentaireResponsable, $motifrespon) {
    try {
        $sql = "UPDATE Justificatif
                SET statut = 'accepté',          
                    commentairerespon = :commentaire,
                    motifrespon = :motifrespon
                WHERE idjustificatif = :id";
        $requete = $conn1->prepare($sql);
        $requete->bindParam(':commentaire', $commentaireResponsable, $commentaireResponsable === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $requete->bindParam(':motifrespon', $motifrespon, $motifrespon === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $requete->bindParam(':id', $justificatifID, PDO::PARAM_INT);
        $requete->execute();

        $sql2 = "UPDATE Absence
                SET statut = 'accepté'
                WHERE idjustificatif = :id";
        $requete2 = $conn1->prepare($sql2);
        $requete2->bindParam(':id', $justificatifID, PDO::PARAM_INT);
        $requete2->execute();
    } catch(PDOException $e) {
        return false;
    }
    return true;
}


/**
 * Fonction qui refuse un justificatif.
 * Elle REÇOIT la connexion en paramètre.
 */
function refuserJustificatif($conn1, $justificatifID, $motifFinal, $commentaireFinal) {
    try {
        $sql = "UPDATE Justificatif
                SET statut = 'refusé',          
                    commentairerespon = :commentaire,
                    motifrespon = :motif
                WHERE idjustificatif = :id";
        $requete = $conn1->prepare($sql);
        $requete->bindParam(':commentaire', $commentaireFinal, $commentaireFinal === null ? PDO::PARAM_NULL : PDO::PARAM_STR);
        $requete->bindParam(':motif', $motifFinal, PDO::PARAM_STR);
        $requete->bindParam(':id', $justificatifID, PDO::PARAM_INT);
        $requete->execute();

        $sql2 = "UPDATE Absence
                SET statut = 'refusé'
                WHERE idjustificatif = :id";
        $requete2 = $conn1->prepare($sql2);
        $requete2->bindParam(':id', $justificatifID, PDO::PARAM_INT);
        $requete2->execute();
    } catch(PDOException $e) {
        return false;
    }
    return true;
}

/**
 * Fonction qui demande une révision d'un justificatif.
 * Elle REÇOIT la connexion en paramètre.
 */
function demanderRevisionJustificatif($conn1, $justificatifID, $commentaireResponsable) {
    try {
        $sql = "UPDATE Justificatif
                SET statut = 'demande de révision',          
                    commentairerespon = :commentaire,
                    motifrespon = 'Le responsable pédagogique vous demande de re-déposer un justificatif qui répond aux exigences'
                WHERE idjustificatif = :id";
        $requete = $conn1->prepare($sql);
        $requete->bindParam(':commentaire', $commentaireResponsable, PDO::PARAM_STR);
        $requete->bindParam(':id', $justificatifID, PDO::PARAM_INT);
        $requete->execute();

        $sql2 = "UPDATE Absence
                SET statut = 'demande de révision'
                WHERE idjustificatif = :id";
        $requete2 = $conn1->prepare($sql2);
        $requete2->bindParam(':id', $justificatifID, PDO::PARAM_INT);
        $requete2->execute();
    } catch(PDOException $e) {
        return false;
    }
    return true;
}

/**
 * Fonction qui déverrouille un justificatif (passe en "plus valable").
 * Elle REÇOIT la connexion en paramètre.
 */
function deverrouillerJustificatif($conn1, $justificatifID) {
    try {
        $sql2 = "UPDATE Absence
                SET statut = 'non justifie'
                WHERE idjustificatif = :id";
        $requete2 = $conn1->prepare($sql2);
        $requete2->bindParam(':id', $justificatifID, PDO::PARAM_INT);
        $requete2->execute();

        $sql = "UPDATE Justificatif
                SET statut = 'plus valable', commentairerespon = 'Le responsable pédagogique est revenus sur ce justificatif, afin de justifier les absences qui étaient concernées veuillez créer un nouveau justificatif', motifrespon = null
                WHERE idjustificatif = :id";
        $requete = $conn1->prepare($sql);
        $requete->bindParam(':id', $justificatifID, PDO::PARAM_INT);
        $requete->execute();
    } catch(PDOException $e) {
        return false;
    }
    return true;
}

/**
 * Fonction qui cherche les étudiants pour les statistiques.
 * Elle REÇOIT la connexion en paramètre.
 */
function chercherEtudiantsStats($conn) {
    $lesEtudiants = [];
    $errorMessage = '';
    try {
        $sql = "SELECT idUtilisateur, nom, prénom, groupe 
                FROM Utilisateur 
                WHERE role = 'Etudiant' 
                ORDER BY nom, prénom";
        $requete = $conn->prepare($sql);
        $requete->execute();
        $lesEtudiants = $requete->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        $errorMessage = "Erreur de connexion à la base de données : ";
    }
    return [$lesEtudiants, $errorMessage];
}

/**
 * Fonction qui récupère les rattrapages pour la page statistique.
 * Elle REÇOIT la connexion en paramètre.
 */
function recupererRattrapagesStats($conn1, $ressource_selectionnee) {
    $lesRattrapages = [];
    $nbrRattrapages = 0;
    $errorMessage = '';

    if (!empty($ressource_selectionnee)) {
        try {
            $sql = "SELECT TO_CHAR(Absence.date, 'DD/MM/YYYY') as date,
                           TO_CHAR(Absence.heure, 'HH24:MI') as heure,
                           TO_CHAR(Absence.duree, 'HH24:MI') as duree, 
                           Absence.matiere, Utilisateur.nom, Utilisateur.prénom,
                           Utilisateur.groupe, absence.evaluation, Absence.statut
                    FROM Absence
                    JOIN Utilisateur ON Utilisateur.idUtilisateur = Absence.idUtilisateur";
            if ($ressource_selectionnee != 'TOUT') {
                $sql .= " WHERE Absence.ressource = :ressource";
            }
            $sql .= " ORDER BY date";
            $requeteRattrapages = $conn1->prepare($sql);
            if ($ressource_selectionnee != 'TOUT') {
                $requeteRattrapages->bindParam(':ressource', $ressource_selectionnee);
            }
            $requeteRattrapages->execute();
            $lesRattrapages = $requeteRattrapages->fetchAll(PDO::FETCH_ASSOC);
            $nbrRattrapages = count($lesRattrapages);

        } catch(PDOException $e) {
            $errorMessage = "Erreur de connexion à la base de données. Impossible de charger les données.";
        }
    }
    return [$lesRattrapages, $nbrRattrapages, $errorMessage];
}

/**
 * Fonction qui récupère toutes les statistiques d'un étudiant.
 * Elle REÇOIT la connexion en paramètre.
 */
function recupererStatistiquesEtudiant($conn, $idEtudiantSelectionne) {
    $labels_ressources = [];
    $donnees_absences = [];
    $labels_typecours = [];
    $donnees_typecours = [];
    $donnees_par_semestre = [
        'S1' => ['labels' => [], 'data' => []], 'S2' => ['labels' => [], 'data' => []],
        'S3' => ['labels' => [], 'data' => []], 'S4' => ['labels' => [], 'data' => []],
        'S5' => ['labels' => [], 'data' => []], 'S6' => ['labels' => [], 'data' => []],
        'Autre' => ['labels' => [], 'data' => []],
    ];
    $typecours_par_semestre = [
        'S1' => ['labels' => [], 'data' => []], 'S2' => ['labels' => [], 'data' => []],
        'S3' => ['labels' => [], 'data' => []], 'S4' => ['labels' => [], 'data' => []],
        'S5' => ['labels' => [], 'data' => []], 'S6' => ['labels' => [], 'data' => []],
        'Autre' => ['labels' => [], 'data' => []],
    ];
    $tendance_labels = [];
    $tendance_datasets = [];
    $totalAbsences = 0;
    $errorMessage = '';
    $nomEtudiant = '';
    $semestreGroup = 'S1S2';

    try {
        $AbsencesParRessources = $conn->prepare("SELECT 
                                                    ressource, 
                                                    COUNT(*) as nb_absences 
                                                FROM Absence 
                                                WHERE idUtilisateur = :idEtudiant
                                                GROUP BY ressource");
        $AbsencesParRessources->bindParam(':idEtudiant', $idEtudiantSelectionne);
        $AbsencesParRessources->execute();
        $ressources = $AbsencesParRessources->fetchAll(PDO::FETCH_ASSOC);

        $AbsencesParTypeDeCours = $conn->prepare("SELECT 
                                                    typecours, 
                                                    COUNT(*) as nb_absences 
                                                FROM Absence 
                                                WHERE idUtilisateur = :idEtudiant
                                                GROUP BY typecours");
        $AbsencesParTypeDeCours->bindParam(':idEtudiant', $idEtudiantSelectionne);
        $AbsencesParTypeDeCours->execute();
        $parTypesTD_TP_CM = $AbsencesParTypeDeCours->fetchAll(PDO::FETCH_ASSOC);

        $SemestresEtudiant = $conn->prepare("SELECT typecours,
                                CASE
                                    WHEN ressource LIKE 'R1%' OR ressource LIKE 'S1%' THEN 'S1'
                                    WHEN ressource LIKE 'R2%' OR ressource LIKE 'S2%' THEN 'S2'
                                    WHEN ressource LIKE 'R3%' OR ressource LIKE 'S3%' THEN 'S3'
                                    WHEN ressource LIKE 'R4%' OR ressource LIKE 'S4%' THEN 'S4'
                                    WHEN ressource LIKE 'R5%' OR ressource LIKE 'S5%' THEN 'S5'
                                    WHEN ressource LIKE 'R6%' OR ressource LIKE 'S6%' THEN 'S6'
                                    ELSE 'Autre'
                                END as semestre,
                                COUNT(*) as nb_absences
                            FROM Absence
                            WHERE idUtilisateur = :idEtudiant
                            GROUP BY semestre, typecours");
        $SemestresEtudiant->bindParam(':idEtudiant', $idEtudiantSelectionne);
        $SemestresEtudiant->execute();
        $typesParSemestre = $SemestresEtudiant->fetchAll(PDO::FETCH_ASSOC);

        $sql_tendance = "SELECT
                            COALESCE(ressource, 'Non défini') as ressource,
                            TO_CHAR(date, 'YYYY-MM') as mois,
                            COUNT(*) as nb_absences
                        FROM Absence
                        WHERE idUtilisateur = :idEtudiant
                          AND date IS NOT NULL
                        GROUP BY ressource, mois
                        ORDER BY mois ASC, ressource ASC";
        $stmt_tendance = $conn->prepare($sql_tendance);
        $stmt_tendance->bindParam(':idEtudiant', $idEtudiantSelectionne);
        $stmt_tendance->execute();
        $tendance_data = $stmt_tendance->fetchAll(PDO::FETCH_ASSOC);

        foreach ($ressources as $ligne) {
            $ressource_nom = $ligne['ressource'];
            $nb_absences = (int)$ligne['nb_absences'];
            $labels_ressources[] = $ressource_nom;
            $donnees_absences[] = $nb_absences;
            $totalAbsences += $nb_absences;
            if (strpos($ressource_nom, 'R1') === 0 || strpos($ressource_nom, 'S1') === 0) {
                $donnees_par_semestre['S1']['labels'][] = $ressource_nom;
                $donnees_par_semestre['S1']['data'][] = $nb_absences;
            } elseif (strpos($ressource_nom, 'R2') === 0 || strpos($ressource_nom, 'S2') === 0) {
                $donnees_par_semestre['S2']['labels'][] = $ressource_nom;
                $donnees_par_semestre['S2']['data'][] = $nb_absences;
            } elseif (strpos($ressource_nom, 'R3') === 0 || strpos($ressource_nom, 'S3') === 0) {
                $donnees_par_semestre['S3']['labels'][] = $ressource_nom;
                $donnees_par_semestre['S3']['data'][] = $nb_absences;
            } elseif (strpos($ressource_nom, 'R4') === 0 || strpos($ressource_nom, 'S4') === 0) {
                $donnees_par_semestre['S4']['labels'][] = $ressource_nom;
                $donnees_par_semestre['S4']['data'][] = $nb_absences;
            } elseif (strpos($ressource_nom, 'R5') === 0 || strpos($ressource_nom, 'S5') === 0) {
                $donnees_par_semestre['S5']['labels'][] = $ressource_nom;
                $donnees_par_semestre['S5']['data'][] = $nb_absences;
            } elseif (strpos($ressource_nom, 'R6') === 0 || strpos($ressource_nom, 'S6') === 0) {
                $donnees_par_semestre['S6']['labels'][] = $ressource_nom;
                $donnees_par_semestre['S6']['data'][] = $nb_absences;
            } else {
                $donnees_par_semestre['Autre']['labels'][] = $ressource_nom;
                $donnees_par_semestre['Autre']['data'][] = $nb_absences;
            }
        }

        foreach ($parTypesTD_TP_CM as $ligne) {
            $labels_typecours[] = $ligne['typecours'];
            $donnees_typecours[] = (int)$ligne['nb_absences'];
        }

        foreach ($typesParSemestre as $ligne) {
            $sem = $ligne['semestre'];
            $type = $ligne['typecours'];
            $nb = (int)$ligne['nb_absences'];
            if (isset($typecours_par_semestre[$sem])) {
                $typecours_par_semestre[$sem]['labels'][] = $type;
                $typecours_par_semestre[$sem]['data'][] = $nb;
            }
        }

        $tendance_ressources_helper = [];
        foreach ($tendance_data as $ligne) {
            $ressource = $ligne['ressource'];
            $mois = $ligne['mois'];
            $nb = (int)$ligne['nb_absences'];
            if (!in_array($mois, $tendance_labels)) {
                $tendance_labels[] = $mois;
            }
            if (!isset($tendance_ressources_helper[$ressource])) {
                $tendance_ressources_helper[$ressource] = [];
            }
            $tendance_ressources_helper[$ressource][$mois] = $nb;
        }

        $i = 0;
        foreach ($tendance_ressources_helper as $ressource_nom => $data_par_mois) {
            $dataset = [
                'label' => $ressource_nom,
                'data' => [],
                'fill' => false,
                'borderColor' => "hsla(" . ($i * 360 / count($tendance_ressources_helper)) . ", 70%, 50%, 0.8)",
                'tension' => 0.1
            ];
            foreach ($tendance_labels as $mois) {
                $dataset['data'][] = $data_par_mois[$mois] ?? 0;
            }
            $tendance_datasets[] = $dataset;
            $i++;
        }

        if (!empty($donnees_par_semestre['S5']['data']) || !empty($donnees_par_semestre['S6']['data'])) {
            $semestreGroup = 'S5S6';
        } elseif (!empty($donnees_par_semestre['S3']['data']) || !empty($donnees_par_semestre['S4']['data'])) {
            $semestreGroup = 'S3S4';
        }

        $stmtNom = $conn->prepare("SELECT nom, prénom FROM Utilisateur WHERE idUtilisateur = :id");
        $stmtNom->execute(['id' => $idEtudiantSelectionne]);
        $etudiant = $stmtNom->fetch();
        if ($etudiant) {
            $nomEtudiant = htmlspecialchars($etudiant['prénom'] . ' ' . $etudiant['nom']);
        }

    } catch (PDOException $e) {
        $errorMessage = "Erreur de connexion à la base de données.";
    }

    return compact(
        'labels_ressources', 'donnees_absences', 'labels_typecours', 'donnees_typecours',
        'donnees_par_semestre', 'typecours_par_semestre', 'tendance_labels', 'tendance_datasets',
        'totalAbsences', 'errorMessage', 'nomEtudiant', 'semestreGroup'
    );
}


function recupererNomEtudiant($conn1, $idJustificatif)
{
    $sql = "SELECT nom, prénom FROM Utilisateur JOIN Absence ON Absence.idUtilisateur = Utilisateur.idUtilisateur
            WHERE Absence.idJustificatif = :idJustificatif
            LIMIT 1";
    $requete = $conn1->prepare($sql);
    $requete->bindParam(':idJustificatif', $idJustificatif);
    $requete->execute();
    $nom = $requete->fetch(PDO::FETCH_ASSOC);

    return $nom;
}

function recupererMailEtudiant($conn1, $idJustificatif)
{
    $sql = "SELECT u.email 
            FROM Utilisateur u
            JOIN Absence a ON a.idutilisateur = u.idutilisateur
            WHERE a.idjustificatif = :idJustificatif
            LIMIT 1";
    $requete = $conn1->prepare($sql);
    $requete->bindParam(':idJustificatif', $idJustificatif, PDO::PARAM_INT);
    $requete->execute();
    $result = $requete->fetch(PDO::FETCH_ASSOC);

    return $result ? $result['email'] : null;
}



function recuperermotif($conn1){
    $lesMotifs = [];
    try {
        $sql = "SELECT motif FROM motifRefus ORDER BY motif ASC";
        $requete = $conn1->prepare($sql);
        $requete->execute();
        $lesMotifs = $requete->fetchAll(PDO::FETCH_ASSOC);

    } catch(PDOException $e) {
        $lesMotifs = [];
    }
    return $lesMotifs;
}


function ajouterNouveauMotif($conn1, $nouveauMotif) {
    try {
        // vérification des doublons
        $check = $conn1->prepare("SELECT COUNT(*) FROM motifRefus WHERE LOWER(motif) = LOWER(:motif)");
        $check->execute([':motif' => $nouveauMotif]);

        if ($check->fetchColumn() == 0) {
            $sql = "INSERT INTO motifRefus (motif) VALUES (:motif)";
            $requete = $conn1->prepare($sql);
            $requete->bindParam(':motif', $nouveauMotif);
            $requete->execute();
        }
    } catch(PDOException $e) {}
}



function recupererMotifAcceptation($conn1){
    $lesMotifs = [];
    try {
        $sql = "SELECT motif FROM motifacceptation ORDER BY motif ASC";
        $requete = $conn1->prepare($sql);
        $requete->execute();
        $lesMotifs = $requete->fetchAll(PDO::FETCH_ASSOC);
    } catch(PDOException $e) {
        $lesMotifs = [];
    }
    return $lesMotifs;
}

function ajouterMotifAcceptation($conn1, $nouveauMotif) {
    try {
        // Vérification des doublons
        $check = $conn1->prepare("SELECT COUNT(*) FROM motifacceptation WHERE LOWER(motif) = LOWER(:motif)");
        $check->execute([':motif' => $nouveauMotif]);

        if ($check->fetchColumn() == 0) {
            $sql = "INSERT INTO motifacceptation (motif) VALUES (:motif)";
            $requete = $conn1->prepare($sql);
            $requete->bindParam(':motif', $nouveauMotif);
            $requete->execute();
        }
    } catch(PDOException $e) {
        die("ERREUR SQL : " . $e->getMessage());
    }
}

?>