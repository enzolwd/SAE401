<?php
/* Contient les fonctions pour l'espace secrétaire */

function traiterFichierCSV($conn, $csv_data) {
    try {
        $header = array_shift($csv_data);

        $totalLignesLues = count($csv_data);
        $countAjoutes = 0;
        $countDoublons = 0;
        $countDejaJustifiees = 0;
        $countEtuInexistant = 0;
        $ListeEtudiantInexistant = [];
        $countIgnorees = 0;

        foreach ($csv_data as $ligne) {
            if (!is_array($ligne) || count($ligne) < 24) {
                $countIgnorees++;
                continue;
            }

            $nom = $ligne[0];
            $prenom = $ligne[1];
            $idIUT = $ligne[4];
            $date = trim($ligne[8]);
            $heure = trim($ligne[9]);
            $duree = trim($ligne[10]);
            $typecours = trim($ligne[11]);
            $matiere = $ligne[12];
            $justification = $ligne[17];
            $prof = trim($ligne[22]);
            $evaluation_str = trim($ligne[23]);

            $ressource = null;
            // capturer la ressource (P, R, ou S) à l'intérieur de parenthèses : "/ (.* ( [PRS][1-6].*? ) ) /"
            $patternRessource = '/\(.*([PRS][1-6].*?)\)/';

            if (preg_match($patternRessource, $matiere, $text)) {
                $ressource = $text[1];
            }

            $evaluation_bool = ($evaluation_str == "Oui");
            if ($justification === '') {$justification = null;}
            if ($prof === '') {$prof = null;}

            $requeteIdEtu = $conn->prepare("SELECT idUtilisateur FROM Utilisateur WHERE nom = :nom AND prénom = :prenom AND identifiantiut = :idIUT");
            $requeteIdEtu->bindParam(':nom', $nom);
            $requeteIdEtu->bindParam(':prenom', $prenom);
            $requeteIdEtu->bindParam(':idIUT', $idIUT);
            $requeteIdEtu->execute();
            $idEtu = $requeteIdEtu->fetch(PDO::FETCH_COLUMN);

            if ($idEtu !== false) {
                if ($justification === "Non justifié") {
                    $requeteDoublons = $conn->prepare(
                        "SELECT * FROM absence
                           JOIN Utilisateur ON absence.idutilisateur = Utilisateur.idutilisateur
                           WHERE Utilisateur.identifiantiut = :idIUT AND date = TO_DATE(:date, 'DD/MM/YYYY') AND heure = REPLACE(:heure, 'H', ':')::time AND duree = REPLACE(:duree, 'H', ':')::time;"
                    );
                    $requeteDoublons->bindParam(':date', $date);
                    $requeteDoublons->bindParam(':heure', $heure);
                    $requeteDoublons->bindParam(':duree', $duree);
                    $requeteDoublons->bindParam(':idIUT', $idIUT);
                    $requeteDoublons->execute();
                    $double = $requeteDoublons->fetchColumn();

                    if ($double == null) {
                        $requeteInsertionAbsence = $conn->prepare("INSERT INTO Absence (date, heure, duree, evaluation, matiere, prof, idutilisateur, idjustificatif, statut, ressource, typecours) VALUES(TO_DATE(:date, 'DD/MM/YYYY'),REPLACE(:heure, 'H', ':')::time,REPLACE(:duree, 'H', ':')::time, :evaluation_bool, :matiere, :prof, :idutilisateur,null, 'non justifie', :ressource, :typecours)");
                        $requeteInsertionAbsence->bindParam(':date', $date);
                        $requeteInsertionAbsence->bindParam(':heure', $heure);
                        $requeteInsertionAbsence->bindParam(':duree', $duree);
                        $requeteInsertionAbsence->bindParam(':evaluation_bool', $evaluation_bool, PDO::PARAM_BOOL);
                        $requeteInsertionAbsence->bindParam(':matiere', $matiere);
                        $requeteInsertionAbsence->bindParam(':prof', $prof);
                        $requeteInsertionAbsence->bindParam(':idutilisateur', $idEtu, PDO::PARAM_INT);
                        $requeteInsertionAbsence->bindParam(':ressource', $ressource);
                        $requeteInsertionAbsence->bindParam(':typecours', $typecours);
                        $requeteInsertionAbsence->execute();
                        $countAjoutes++;
                    } else {
                        $countDoublons++;
                    }
                } else {
                    $countDejaJustifiees++;
                }
            }else {
                $countEtuInexistant++;
                $nomComplet = $nom . " " . $prenom;
                if (!in_array($nomComplet, $ListeEtudiantInexistant)) {
                    $ListeEtudiantInexistant[] = $nomComplet;
                }
            }
        }

        return [
            'status' => 'success',
            'lignesLues' => $totalLignesLues,
            'countAjoutes' => $countAjoutes,
            'countDoublons' => $countDoublons,
            'countDejaJustifiees' => $countDejaJustifiees,
            'countEtuInexistant' => $countEtuInexistant,
            'ListeEtudiantInexistant' => $ListeEtudiantInexistant
        ];

    } catch (PDOException $e) {
        return ['status' => 'error_db'];
    }
}
?>