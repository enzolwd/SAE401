<?php
header('Content-Type: text/html; charset=utf-8');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

function envoyerMail($destEmail, $destName, $type, $lienOptionnel = '') {

    $mails = [
        1 => [
            'sujet' => "Confirmation de soumission justificatif",
            'message' => "<p>Bonjour $destName,
                          <p>Nous vous confirmons la bonne réception de votre justificatif d'absence.</p>
                          <p>Celui-ci sera <strong>traité</strong> par le service pédagogique dans les plus brefs délais. Vous serez notifié par email dès qu'une décision (validation ou refus) aura été prise.</p>"
        ],
        2 => [
            'sujet' => "Validation de justificatif",
            'message' => "<p>Bonjour $destName,
                          <p>Nous avons le plaisir de vous informer qu'un justificatif a été <strong>validé</strong> par les responsables pédagogiques.</p>
                          <p>Votre dossier a été mis à jour. Les absences concernant ce justificatif sont désormaient considérées comme justifiées.</p>"
        ],
        3 => [
            'sujet' => "Refus du justificatif",
            'message' => "<p>Bonjour $destName, 
                          <p>Nous avons le regret de vous informer qu'un justificatif a été <strong>réfusé</strong> par les responsables pédagogiques.</p>
                          <p>Votre dossier a été mis à jour. Les absences concernant ce justificatif sont désormaient considérées comme refusées.</p>
                          <p>Toutes les évaluations qui ont eu lieu durant cette période ne pourront pas être rattrapées.</p>"
        ],
        4 => [
            'sujet' => "Demande de révision",
            'message' => "<p>Bonjour $destName, 
                          <p>Après étude de votre dossier, les responsables pédagogiques ont <strong>demandé une révision</strong> de votre justificatif.</p>
                          <p>Vous trouverez plus de détails sur les raisons de la demande de révision en consultant votre justificatif dans votre historique.</p>
                          <p>Les absences de cette période restent non justifiées.</p>
                          <p>Afin de re-justifier ces absences, il faudra re-déposer un justificatif.</p>"
        ],
        5 => [
            'sujet' => "Déverouillage de justificatif",
            'message' => "<p>Bonjour $destName,
                          <p>Après réétude d'un de vos justificatifs, les responsables pédagogiques ont décidé de le <strong>déverrouiller</strong>.</p>
                          <p>Ce justificatif n'est donc plus valable et les absences qu'il couvrait sont désormais considérées comme <strong>non justifiées</strong>.</p>
                          <p>Vous trouverez le justificatif dans votre historique.</p>
                          <p>Vous pouvez à présent re-déposer un nouveau justificatif pour ces mêmes absences afin de les re-justifier.</p>"
        ],
        6 => [
            'sujet' => "Alerte absences répétées non justifiées",
            'message' => "<p>Bonjour $destName,
                          <p>Vous avez plusieurs absences non justifiées.</p>
                          <p>Vous devez justifier ces absences. Dans le cas contraire, vous serez <strong>sanctionné</strong> (malus sur vos ressources)</p>
                          <p>Il vous reste 48h pour rester dans les temps. Passé ce délai, le responsable pédagogique sera susceptible de refuser vos justificatifs.</p>"
        ],
        7 => [
            'sujet' => "Alerte reste 48h pour être dans les temps",
            'message' => "<p>Bonjour $destName,
                          <p>Vous avez une ou plusieurs absences non justifiée(s).</p>
                          <p>Vous devez déposer un justificatif.</p>
                          <p>Il vous reste 48h pour rester dans les temps. Passé ce délai, le responsable pédagogique sera susceptible de refuser vos justificatifs.</p>"
        ],
        8 => [
            'sujet' => "Alerte reste 24h pour être dans les temps",
            'message' => "<p>Bonjour $destName,
                          <p>Vous avez une ou plusieurs absences non justifiée(s).</p>
                          <p>Vous devez déposer un justificatif.</p>
                          <p>Il vous reste 24h pour rester dans les temps. Passé ce délai, le responsable pédagogique sera susceptible de refuser vos justificatifs.</p>"
        ],
        9 => [
            'sujet' => "Alerte vous devez impérativement justifier",
            'message' => "<p>Bonjour $destName,
                          <p>Vous avez une ou plusieurs absences non justifiée(s).</p>
                          <p>Vous devez <strong>impérativement</strong> déposer un justificatif.</p>"
        ],
        10 => [
        'sujet' => "Réinitialisation de votre mot de passe",
        'message' => "<p>Bonjour $destName,</p>
                          <p>Une demande de réinitialisation de mot de passe a été effectuée pour votre compte.</p>
                          <p>Veuillez cliquer sur le lien ci-dessous pour créer un nouveau mot de passe (valable 1 heure) :</p>
                          <p><a href='$lienOptionnel'>Réinitialiser mon mot de passe</a></p>
                          <p>Si vous n'êtes pas à l'origine de cette demande, veuillez ignorer cet email.</p>"
    ]
    ];

    if (!isset($mails[$type])) {
        return false;
    }

    $mail = new PHPMailer(true);
    $mail->CharSet = 'UTF-8';
    $mail->Encoding = 'base64';

    try {
        $mail->isSMTP();
        $mail->Host       = 'pro.turbo-smtp.com';
        $mail->SMTPAuth   = true;

        $mail->Username   = '60929071f61eb85898a0';
        $mail->Password   = 'XIHBPYxfVOzjLRJZ2c3p';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 465;

        $mail->setFrom('baillonarthus7@gmail.com', 'Arthus');
        $mail->addAddress($destEmail, $destName);

        $mail->isHTML(true);
        $mail->Subject = $mails[$type]['sujet'];
        $mail->Body    = $mails[$type]['message'];
        $mail->AltBody = strip_tags($mails[$type]['message']);

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Erreur email : " . $mail->ErrorInfo);
        return false;
    }
}
