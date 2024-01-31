<?php
// bib_connect.php
$host = '127.0.0.1'; // L'adresse du serveur de base de données, souvent 'localhost'
$db   = 'hosting_ecf'; //  nom base de données
$user = 'sandrine'; // Le nom d'utilisateur pour se connecter à la base de données
$pass = 'Sandrine73&sql'; // Le mot de passe associé à l'utilisateur de la base de données
$charset = 'utf8mb4';
$port = "3306";

$options = [
    \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
    \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
    \PDO::ATTR_EMULATE_PREPARES   => false,
];
/**
 * Init database connection
 */
$dsn = "mysql:host=$host;dbname=$db;charset=$charset;port=$port";
$pdo = new \PDO($dsn, $user, $pass, $options);

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

//PDO::ATTR_ERRMODE: Paramètre de configuration définit comment PDO doit rapporter les erreurs. En le réglant sur PDO::ERRMODE_EXCEPTION, j'indiquez à PDO de lancer une exception chaque fois qu'une erreur survient.
//PDO::ATTR_DEFAULT_FETCH_MODE: Paramètre détermine comment PDO retournera les résultats de la base de données. En utilisant PDO::FETCH_ASSOC, je demande à PDO de retourner les résultats sous forme de tableau associatif, où les clés sont les noms des colonnes.
//PDO::ATTR_EMULATE_PREPARES: Paramètre indique à PDO s'il doit émuler les requêtes préparées ou non. En le définissant sur false, je désactive l'émulation des requêtes préparées et utilise la fonctionnalité native du serveur de base de données. C'est svt recommandé pour la sécurité (cela peut aider à prévenir les injections SQL) et pour les performances.
?>
