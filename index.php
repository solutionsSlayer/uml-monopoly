<?php

// Atelier 1: Singleton Pattern - Banque
class Banque {
    private static ?Banque $instance = null;
    private int $cash;
    
    private function __construct() {
        $this->cash = 0;
    }
    
    public static function getInstance(): Banque {
        if (self::$instance === null) {
            self::$instance = new Banque();
        }
        return self::$instance;
    }
    
    public function getCash(): int {
        return $this->cash;
    }
    
    public function setCash(int $cash): void {
        $this->cash = $cash;
    }
    
    // Prevent cloning of the instance
    private function __clone() {}
}

// Atelier 2: Factory Pattern (V1) - Properties
abstract class Propriete {
    /**
     * Prix de la propriété
     */
    protected int $prix;

    /**
     * Nom de la propriété
     */
    protected string $nom;

    /**
     * Propriétaire actuel de la propriété
     */
    protected string $proprietaire;

    /**
     * Indique si la propriété est hypothéquée
     */
    protected bool $estHypotheque;

    /**
     * Nombre de maisons construites sur la propriété
     */
    protected int $nbMaisons;

    /**
     * Montant du loyer de base sans maisons
     */
    protected int $loyerNu;    
    public function __construct(string $nom, int $prix, string $proprietaire = "Banque") {
        $this->nom = $nom;
        $this->prix = $prix;
        $this->proprietaire = $proprietaire;
        $this->estHypotheque = false;
        $this->nbMaisons = 0;
        $this->loyerNu = (int)($prix * 0.1); // Loyer nu = 10% du prix par défaut
    }
    
    public function getPrix(): int {
        return $this->prix;
    }
    
    public function getNom(): string {
        return $this->nom;
    }
    
    public function getProprietaire(): string {
        return $this->proprietaire;
    }
    
    public function setProprietaire(string $proprietaire): void {
        $this->proprietaire = $proprietaire;
    }
    
    public function estHypotheque(): bool {
        return $this->estHypotheque;
    }
    
    public function hypothequer(): void {
        $this->estHypotheque = true;
    }
    
    public function desHypothequer(): void {
        $this->estHypotheque = false;
    }
    
    public function getNbMaisons(): int {
        return $this->nbMaisons;
    }
    
    public function setNbMaisons(int $nbMaisons): void {
        $this->nbMaisons = $nbMaisons;
    }
    
    public function getLoyerNu(): int {
        return $this->loyerNu;
    }
    
    abstract public function afficher(): string;
}

class Terrain extends Propriete {
    public function afficher(): string {
        $status = $this->estHypotheque ? "hypothéqué" : "non hypothéqué";
        return "Terrain: {$this->nom} - Prix: {$this->prix}€ - Propriétaire: {$this->proprietaire} - Status: {$status} - Maisons: {$this->nbMaisons}";
    }
}

class Gare extends Propriete {
    public function afficher(): string {
        return "Gare: {$this->nom} - Prix: {$this->prix}€";
    }
}

class CompagnieEE extends Propriete {
    public function afficher(): string {
        return "Compagnie: {$this->nom} - Prix: {$this->prix}€";
    }
}

class ProprieteFactory {
    public function creer(string $type, string $nom, int $prix): Propriete {
        return match ($type) {
            'terrain' => new Terrain($nom, $prix),
            'gare' => new Gare($nom, $prix),
            'compagnie' => new CompagnieEE($nom, $prix),
            default => throw new Exception("Type de propriété inconnu"),
        };
    }
}

// Atelier 3: Iterator Pattern - Plateau
class Case_ {
    private int $numero;
    private string $nom;
    
    public function __construct(int $numero, string $nom) {
        $this->numero = $numero;
        $this->nom = $nom;
    }
    
    public function afficher(): string {
        return "Case {$this->numero}: {$this->nom}";
    }
}

class Plateau implements Iterator {
    private array $cases = [];
    private int $position = 0;
    
    public function ajouterCase(Case_ $case): void {
        $this->cases[] = $case;
    }
    
    public function current(): Case_ {
        return $this->cases[$this->position];
    }
    
    public function key(): int {
        return $this->position;
    }
    
    public function next(): void {
        $this->position++;
    }
    
    public function rewind(): void {
        $this->position = 0;
    }
    
    public function valid(): bool {
        return isset($this->cases[$this->position]);
    }
}

// Atelier 4: DAO Pattern - Joueur
class Joueur {
    private string $prenom;
    private int $cash;
    
    public function __construct(string $prenom, int $cash) {
        $this->prenom = $prenom;
        $this->cash = $cash;
    }
    
    public function getPrenom(): string {
        return $this->prenom;
    }
    
    public function getCash(): int {
        return $this->cash;
    }
    
    public function setCash(int $cash): void {
        $this->cash = $cash;
    }
}

interface JoueurDAO {
    public function getTousLesJoueurs(): array;
    public function addJoueur(Joueur $joueur): void;
    public function updateJoueur(Joueur $joueur): void;
    public function deleteJoueur(Joueur $joueur): void;
}

class JoueurDAOImpl implements JoueurDAO {
    private array $joueurs = [];
    
    public function __construct() {
        // Simulation de données préenregistrées
        $this->joueurs = [
            new Joueur("Alice", 1500),
            new Joueur("Bob", 1500),
            new Joueur("Charlie", 1500)
        ];
    }
    
    public function getTousLesJoueurs(): array {
        return $this->joueurs;
    }
    
    public function addJoueur(Joueur $joueur): void {
        $this->joueurs[] = $joueur;
    }
    
    public function updateJoueur(Joueur $joueur): void {
        foreach ($this->joueurs as $key => $j) {
            if ($j->getPrenom() === $joueur->getPrenom()) {
                $this->joueurs[$key] = $joueur;
                break;
            }
        }
    }
    
    public function deleteJoueur(Joueur $joueur): void {
        foreach ($this->joueurs as $key => $j) {
            if ($j->getPrenom() === $joueur->getPrenom()) {
                unset($this->joueurs[$key]);
                break;
            }
        }
        $this->joueurs = array_values($this->joueurs);
    }
}

// Atelier 5: MVC Pattern
class JoueurView {
    public function afficherFicheJoueur(string $prenom, int $cash): void {
        echo "Fiche joueur:\n";
        echo "Prénom: $prenom\n";
        echo "Cash: $cash €\n";
    }
}

class JoueurController {
    private Joueur $modele;
    private JoueurView $vue;
    
    public function __construct(Joueur $modele, JoueurView $vue) {
        $this->modele = $modele;
        $this->vue = $vue;
    }
    
    public function updateVue(): void {
        $this->vue->afficherFicheJoueur(
            $this->modele->getPrenom(),
            $this->modele->getCash()
        );
    }
    
    public function ajouterCash(int $montant): void {
        $this->modele->setCash($this->modele->getCash() + $montant);
    }
}

// Atelier 6: Chain of Responsibility Pattern
abstract class LoyerHandler {
    protected ?LoyerHandler $nextHandler = null;
    
    public function setNext(LoyerHandler $handler): LoyerHandler {
        $this->nextHandler = $handler;
        return $handler;
    }
    
    abstract public function calculerLoyer(Terrain $terrain, Joueur $joueur): int;
}

class BanqueHandler extends LoyerHandler {
    public function calculerLoyer(Terrain $terrain, Joueur $joueur): int {
        if ($terrain->getProprietaire() instanceof Banque) {
            return 0;
        }
        return $this->nextHandler?->calculerLoyer($terrain, $joueur) ?? 0;
    }
}

class ProprietaireHandler extends LoyerHandler {
    public function calculerLoyer(Terrain $terrain, Joueur $joueur): int {
        if ($terrain->getProprietaire() === $joueur) {
            return 0;
        }
        return $this->nextHandler?->calculerLoyer($terrain, $joueur) ?? 0;
    }
}

class HypothequeHandler extends LoyerHandler {
    public function calculerLoyer(Terrain $terrain, Joueur $joueur): int {
        if ($terrain->estHypotheque()) {
            return 0;
        }
        return $this->nextHandler?->calculerLoyer($terrain, $joueur) ?? 0;
    }
}

class MaisonsHandler extends LoyerHandler {
    public function calculerLoyer(Terrain $terrain, Joueur $joueur): int {
        $nbMaisons = $terrain->getNbMaisons();
        if ($nbMaisons > 0) {
            $grille = [50, 150, 450, 1000, 2000];
            return $grille[$nbMaisons - 1];
        }
        return $terrain->getLoyerNu();
    }
}

// Programme de test
function testSingleton(): void {
    echo "Test du Singleton (Banque):\n";
    $b1 = Banque::getInstance();
    $b1->setCash(1000);
    echo "Cash de b1: " . $b1->getCash() . "€\n";
    
    $b2 = Banque::getInstance();
    $b2->setCash(500);
    echo "Cash de b2: " . $b2->getCash() . "€\n";
    echo "Cash de b1: " . $b1->getCash() . "€\n";
}

function testFactory(): void {
    echo "\nTest de la Factory:\n";
    $factory = new ProprieteFactory();
    $rueDeLaPaix = $factory->creer('terrain', 'Rue de la Paix', 400);
    $rueDeCourcelles = $factory->creer('terrain', 'Rue de Courcelles', 100);
    $gareMontparnasse = $factory->creer('gare', 'Montparnasse', 200);
    
    echo $rueDeLaPaix->afficher() . "\n";
    echo $rueDeCourcelles->afficher() . "\n";
    echo $gareMontparnasse->afficher() . "\n";
}

function testIterator(): void {
    echo "\nTest de l'Iterator:\n";
    $plateau = new Plateau();
    $casesDepart = [
        ['Départ', 0], ['Boulevard de Belleville', 60],
        ['Caisse Communauté', 0], ['Rue Lecourbe', 60],
        ['Impôt sur le Revenu', 200], ['Gare Montparnasse', 200],
        ['Rue de Vaugirard', 100], ['Chance', 0],
        ['Rue de Courcelles', 100], ['Avenue de la République', 120]
    ];
    
    foreach ($casesDepart as $i => [$nom, $prix]) {
        $plateau->ajouterCase(new Case_($i, $nom));
    }
    
    foreach ($plateau as $case) {
        echo $case->afficher() . "\n";
    }
}

function testDAO(): void {
    echo "\nTest du DAO:\n";
    $dao = new JoueurDAOImpl();
    
    echo "Liste initiale des joueurs:\n";
    foreach ($dao->getTousLesJoueurs() as $joueur) {
        echo "{$joueur->getPrenom()}: {$joueur->getCash()}€\n";
    }
    
    echo "\nAjout de 100€ à chaque joueur:\n";
    foreach ($dao->getTousLesJoueurs() as $joueur) {
        $joueur->setCash($joueur->getCash() + 100);
        $dao->updateJoueur($joueur);
    }
    
    echo "\nSuppression d'un joueur:\n";
    $dao->deleteJoueur($dao->getTousLesJoueurs()[0]);
    
    echo "\nListe finale des joueurs:\n";
    foreach ($dao->getTousLesJoueurs() as $joueur) {
        echo "{$joueur->getPrenom()}: {$joueur->getCash()}€\n";
    }
}

function testChainOfResponsibility(): void {
    echo "\nTest de la chaîne de responsabilité:\n";
    
    $banqueHandler = new BanqueHandler();
    $proprietaireHandler = new ProprietaireHandler();
    $hypothequeHandler = new HypothequeHandler();
    $maisonsHandler = new MaisonsHandler();
    
    $banqueHandler->setNext($proprietaireHandler)
                  ->setNext($hypothequeHandler)
                  ->setNext($maisonsHandler);
    
    // Création des joueurs
    $banque = "Banque";
    $joueur = new Joueur("Alice", 1500);
    
    // Création et test du terrain
    $terrain = new Terrain("Rue de la Paix", 400, $banque);
    
    // Test 1: Terrain appartenant à la banque
    echo "Test 1 - Terrain appartenant à la banque:\n";
    echo "Loyer à payer: " . $banqueHandler->calculerLoyer($terrain, $joueur) . "€\n";
    
    // Test 2: Terrain appartenant au joueur
    $terrain->setProprietaire($joueur->getPrenom());
    echo "\nTest 2 - Terrain appartenant au joueur:\n";
    echo "Loyer à payer: " . $banqueHandler->calculerLoyer($terrain, $joueur) . "€\n";
    
    // Test 3: Terrain hypothéqué
    $terrain->setProprietaire("Bob");
    $terrain->hypothequer();
    echo "\nTest 3 - Terrain hypothéqué:\n";
    echo "Loyer à payer: " . $banqueHandler->calculerLoyer($terrain, $joueur) . "€\n";
    
    // Test 4: Terrain avec une maison
    $terrain->desHypothequer();
    $terrain->setNbMaisons(1);
    echo "\nTest 4 - Terrain avec une maison:\n";
    echo "Loyer à payer: " . $banqueHandler->calculerLoyer($terrain, $joueur) . "€\n";
}

function testMVC(): void {
    echo "\nTest du MVC:\n";
    $joueur = new Joueur("Alice", 1500);
    $vue = new JoueurView();
    $controller = new JoueurController($joueur, $vue);
    
    echo "État initial:\n";
    $controller->updateVue();
    
    echo "\nAprès ajout de 100€:\n";
    $controller->ajouterCash(100);
    $controller->updateVue();
}

echo "\nExécution du test de la chaîne de responsabilité avec hypothèque:\n";
testChainOfResponsibility();

testSingleton();
testFactory();
testIterator();
testDAO();
testMVC();