<?php 
class Vehicule{
    
    public function __construct(){
        
    }
    
    /**
     *  TB VEHIC 01
     */    
    public static $statut = array(
        ' ' => 'Arrivage',
        'V' => 'Vendu',
        'P' => 'Publié',
        'R' => 'Réserver',
    );
    
    public static function getListeStatut(){
        return self::$statut;
    }
    /**
     * Methode liste des vehicules
     */
    public function getListe($pdo){
        $sql = "select * from vehicule ".
                "left outer join marque on marque.code = vehicule.code_marque";
        return DbAccess::getRequeteSql($pdo, $sql);
        
    }
    
    /**
     * 
     * @param unknown $pdo
     * @param unknown $identifiant
     * @param unknown $mot_de_passe
     * @param unknown $nom
     * @param unknown $prenom
     * @param unknown $type_utilisateur
     */
    public static function ajoute($pdo,$codeMarque,$description,$prix,$anneeCirculation,$km,$nomFichierImage){
        $data = [
            'tcodeMarque' => $codeMarque,
            'tdescription' => $description,
            'tprix' => $prix,
            'tannee' => $anneeCirculation,
            'tkm' => $km,
            'urlimage' => $nomFichierImage
        ];
        $sql = "INSERT INTO vehicule (code_marque,description,prix,annee_circulation,km,url_img) VALUES (:tcodeMarque, :tdescription, :tprix, :tannee, :tkm , :urlimage)";
        
        
        $stmt= $pdo->prepare($sql);
        $stmt->execute($data);
        
        return true;
        
        
    }

    /**
     * Process pour faire la mise du statut du véhicule
     *   
     */
    public static function modifierStatus($pdo, $idxVehicule, $status) {
        $data = [
                ':idx_vehicule' => $idxVehicule, 
                ':status' => $status
            ];
        $sql = "UPDATE vehicule SET status = :status WHERE idx_vehicule = :idx_vehicule";
        $stmt = $pdo->prepare($sql);
        return $stmt->execute($data);

    }
    
    private static function getSqlVisibility(){
        return "status IN('P','A') ";
    }

    public static function requeteMarqueVehiculeVisible($pdo){
        $sql = "select * from marque ".
            "where exists(select * from vehicule ".
                          "where vehicule.code_marque = marque.code and ".self::getSqlVisibility().
                        ")";
        return DbAccess::getRequeteSql($pdo,$sql);
        
    }

    public static function requeteVehiculePrix($pdo){
        $sql = "select * from vehicule where prix < 20000 and ".self::getSqlVisibility();
        return DbAccess::getRequeteSql($pdo,$sql);
        
    }
    
    public static function requeteVehiculeKm($pdo){
        $sql = "select * from vehicule where km < 20000 and ".self::getSqlVisibility();
        return DbAccess::getRequeteSql($pdo,$sql);
        
    }

    public static function requeteVehiculeMarque($pdo,$codeMarque){
        $sql = "select * from vehicule where code_marque = ".$pdo->quote($codeMarque)." and ".self::getSqlVisibility();
        return DbAccess::getRequeteSql($pdo,$sql);
        
    }

}
?>