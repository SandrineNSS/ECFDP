<?php
/**
 * Renvoi un tableau de donnée de resultat d'element dans une query
 * $pdo : instance de connexion MYSQL
 * $sql : la requete sql envoyée à la base de donnée demo
 * 
 */
// fabrication d'une class pour acceder au données de la base 
class DbAccess{
  public static function transactionDebut($pdo){
    $sql = "START TRANSACTION";
    $stmt= $pdo->prepare($sql);
    return $stmt->execute();
  }
  
  public static function transactionValide($pdo){
    $sql = "COMMIT";
    $stmt= $pdo->prepare($sql);
    return $stmt->execute();
  }

  public static function transactionDefait($pdo){
    $sql = "ROLLBACK";
    $stmt= $pdo->prepare($sql);
    return $stmt->execute();
  }

  public static function getRequeteSql($pdo,$sql){ // Fonction STATIC unique
    try {
        
        // https://phpdelusions.net/pdo_examples/select
        
        // comme la méthode n'est pas static alors on utilise la syntase <variable de l'objet instancié>-><Nom de la méthode>
        return $pdo->query($sql)->fetchAll();
        // and somewhere later:;
    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
  }

  /**
   * $pdo->query
   * $sql = "SELECT
   * Renvoi de manière unitaire un et un seul element d'une ligne de tb*tableau
   */
  public static function canFind($pdo,$sql){ //Fonction STATIC unique
    try {
        
        // https://phpdelusions.net/pdo_examples/select
        
        $resultat = $pdo->query($sql)->fetchAll(); // renvoi sous format tableau un resultat d'une requete
        if ($resultat){
          return $resultat[0]; // 0 car premier élement du tableau de donnéees
        }
        else
          return null;

    } catch (\PDOException $e) {
        throw new \PDOException($e->getMessage(), (int)$e->getCode());
    }
  }
  
}
?>
