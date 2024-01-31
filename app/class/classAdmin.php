<?php
/**
 * Gestion des comptes administrateurs.
 *
 * Cette classe fournit des méthodes pour les opérations CRUD sur la table 'administrateur'.
 * Elle nécessite une instance de PDO pour interagir avec la base de données.
 */
class Admin {
    /**
     * @var PDO Instance de la connexion à la base de données.
     */
    private $pdo;

    /**
     * Constructeur de la classe Admin.
     *
     * @param PDO $pdo Une instance de PDO pour la connexion à la base de données.
     */
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Ajoute un nouvel administrateur dans la base de données.
     *
     * @param string $email email unique pour l'administrateur.
     * @param string $motDePasse Mot de passe pour l'administrateur (sera haché).
     * @param string $nom Nom de l'administrateur.
     * @param string $prenom Prénom de l'administrateur.
     * @return bool Renvoie true si l'ajout est réussi, false autrement.
     */
    public function ajouterAdmin($email, $motDePasse, $nom, $prenom) {
        $motDePasseHache = password_hash($motDePasse, PASSWORD_DEFAULT);
        $sql = "INSERT INTO personne (email, mdp, nom, prenom) VALUES (?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$email, $motDePasseHache, $nom, $prenom]);
    }

    public function modifierAdmin($email, $motDePasse, $nom, $prenom) {
    // Hash du mot de passe pour le stocker en sécurité dans la base de données.
    $motDePasseHache = password_hash($motDePasse, PASSWORD_DEFAULT);
    
    // Requête SQL pour mettre à jour un administrateur existant.
    $sql = "UPDATE personne  SET mdp = ?, nom = ?, prenom = ? WHERE email = ?";
    
    // Préparation de la requête pour éviter les injections SQL.
    $stmt = $this->pdo->prepare($sql);
    
    // Exécution de la requête avec les valeurs passées en paramètre.
    // L'ordre des paramètres ici doit correspondre à l'ordre dans la requête SQL.
    return $stmt->execute([$motDePasseHache, $nom, $prenom, $email]);
    }

    public function supAdmin($email, $motDePasse, $nom, $prenom) {
        $sql = "DELETE FROM personne  WHERE email =?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$email]);
    }
    
    /**
     * Vérifie les identifiants de connexion d'un administrateur.
     *
     * @param string $identifiant Identifiant de l'administrateur.
     * @param string $motDePasse Mot de passe de l'administrateur.
     * @return bool Renvoie true si les identifiants sont corrects, false autrement.
     */
    public function verifierIdentifiants($email, $motDePasse) {
        $sql = "SELECT mdp FROM personne  WHERE email = ?";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$email]);
        $admin = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($admin && password_verify($motDePasse, $admin['mdp'])) {
            return true;
        }

        return false;
    }
}
