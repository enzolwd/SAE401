<?php
use PHPUnit\Framework\TestCase;

class ConnexionTest extends TestCase {

    protected function setUp(): void {
        require_once __DIR__ . '/../MVP/Modele/Connexion_Modele.php';
    }

    public function testAuthentificationComplete() {
        // on définit un mot de passe et on génère son hash pour le test
        $monMotDePasseEnClair = "Secret123";
        $vraiHashValide = password_hash($monMotDePasseEnClair, PASSWORD_DEFAULT);

        // mock SQL (On fait croire que la BDD renvoie ce hash valide)
        $pdoStatement = $this->createMock(PDOStatement::class);
        $pdoStatement->method('fetchColumn')->willReturnOnConsecutiveCalls(
            $vraiHashValide,     // La BDD renvoie le hash correspondant à "Secret123"
            'Etudiant',          // rôle
            1,                   // ID
            0,                   // tentatives
            null,                // blocage
            null                 // dernière tentative
        );

        $pdoStatement->method('execute')->willReturn(true);
        $pdoStatement->method('bindParam')->willReturn(true);

        $pdo = $this->createMock(PDO::class);
        $pdo->method('prepare')->willReturn($pdoStatement);

        // on récupère les infos
        $utilisateur = trouverUtilisateurParNom($pdo, 'Toto');

        // on teste la vérification du mot de passe
        $connexionReussie = password_verify($monMotDePasseEnClair, $utilisateur['hash']);
        $connexionEchouee = password_verify("MauvaisMotDePasse", $utilisateur['hash']);

        $this->assertTrue($connexionReussie, "L'authentification devrait réussir avec le bon mot de passe.");
        $this->assertFalse($connexionEchouee, "L'authentification doit échouer avec un mauvais mot de passe.");
        $this->assertEquals('Etudiant', $utilisateur['role']);
    }
}