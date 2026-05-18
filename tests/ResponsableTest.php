<?php
use PHPUnit\Framework\TestCase;

class ResponsableTest extends TestCase {

    protected function setUp(): void {
        require_once __DIR__ . '/../MVP/Modele/Responsable_Modele.php';
    }

    public function testRecupererDetailsJustificatifAttente() {
        // mock
        $fakeDetails = [
            'idjustificatif' => 42,
            'nom' => 'Baillon',
            'prenom' => 'Arthus',
            'date_depot' => '2023-10-01',
            'motif' => 'Maladie'
        ];

        // mock sql
        $pdoStatement = $this->createMock(PDOStatement::class);
        $pdoStatement->method('fetch')->willReturn($fakeDetails);
        $pdoStatement->method('execute')->willReturn(true);
        $pdoStatement->method('bindParam')->willReturn(true);

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($pdoStatement);

        // on teste avec l'ID 42
        $resultat = recupererDetailsJustificatifAttente($pdo, 42);

        $this->assertEquals('Baillon', $resultat['nom']);
        $this->assertEquals('Maladie', $resultat['motif']);
    }
}