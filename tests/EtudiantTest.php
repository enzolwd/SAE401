<?php
use PHPUnit\Framework\TestCase;

class EtudiantTest extends TestCase {

    protected function setUp(): void {
        require_once __DIR__ . '/../MVP/Modele/Etudiant_Modele.php';
    }

    public function testRecupererTableauxEtudiant() {
        // préparation des fausses données

        $fausseAbsences = [
            ['matiere' => 'Maths', 'heure' => '08:00', 'statut' => 'non justifie'],
            ['matiere' => 'Anglais', 'heure' => '10:00', 'statut' => 'non justifie']
        ];

        $fausseJustificatifs = [
            ['idjustificatif' => 10, 'statut' => 'en attente']
        ];

        $stmtAbsences = $this->createMock(PDOStatement::class);
        $stmtAbsences->method('fetchAll')->willReturn($fausseAbsences);
        $stmtAbsences->method('execute')->willReturn(true);
        $stmtAbsences->method('bindParam')->willReturn(true);

        $stmtJustifs = $this->createMock(PDOStatement::class);
        $stmtJustifs->method('fetchAll')->willReturn($fausseJustificatifs);
        $stmtJustifs->method('execute')->willReturn(true);
        $stmtJustifs->method('bindParam')->willReturn(true);

        // le mock PDO doit renvoyer le bon Statement
        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturnOnConsecutiveCalls($stmtAbsences, $stmtJustifs);

        // on appelle la fonction
        $resultat = recupererTableauxEtudiant($pdo, 1, true, '2023-12-01');


        // on vérifie qu'on récupère bien un tableau de tableaux
        $this->assertCount(2, $resultat, "La fonction doit retourner [absences, justificatifs]");

        $absencesRecues = $resultat[0];
        $justifsRecus = $resultat[1];

        // test du contenu
        $this->assertEquals('Maths', $absencesRecues[0]['matiere']);
        $this->assertEquals(10, $justifsRecus[0]['idjustificatif']);
    }
}