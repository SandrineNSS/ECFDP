<?php
    /**
     * Class pour la gestion de la donnée Utilisateur
     * permet les fonctions de création / modification / suppression en base
     * 
     * renvoi la liste des utilisateurs
     * renvoi l'existence ou non d'un utilisateur par son identifiant
     * 
     * Enregistre dans la session le fait qu'un utilisateur se connecte
     * 
     * Permet le controle de la demande de connexion
     * 
     * L'utilisation de l'algorithme SHA1 permet de sécuriser le mot de passe
     * @author
     *
     */
    class Utilisateur {
        // On enregistre les information de connexion du driver pdo
        private $user;
        
        /**
         * __construct est relatif au constructeur de class
         * quand on 
         * @param unknown $pdo 
         */
        public function __construct() {
        }
        
        /**
         * Liste des utilisateurs
         *
         * @param unknown $pdo
         * @return unknown
         */
        public static function getListe($pdo){
            $sql = "select * from personne ";
            return DbAccess::getRequeteSql($pdo, $sql);
        }
        
        
        /**
         * Fonction pour l'ajout d'utilisateur
         * @param unknown $identifiant
         * @param unknown $mot_de_passe
         * @param unknown $nom
         * @param unknown $prenom
         * @param unknown $type_personne
         */
        public static function ajoute($pdo,$email,$mot_de_passe,$nom,$prenom,$type_personne){
            $data = [
                'tidentifiant' => $email,
                'tmdp' => Utilisateur::pw_encode($mot_de_passe),
                'tnom' => $nom,
                'tprenom' => $prenom,
                'ttype_personne' => $type_personne,
            ];
            $sql = "INSERT INTO personne (email,mot_de_passe,nom,prenom,type_personne) VALUES (:tidentifiant, :tmdp, :tnom, :tprenom, :ttype_personne)";
            
            $stmt= $pdo->prepare($sql);
            return $stmt->execute($data);
        }

        /**
         * Méthode de recherche de l'existence d'un utilisateur 
         * 
         * @param unknown $pdo              Connecteur 
         * @param unknown $identifiant      Identifiant de saisie
         * @return boolean true / false
         */
        public static function isExiste($pdo, $email){
            $sql = "select * from personne where email = " . $pdo->quote($email);
            
            return is_array(DbAccess::canFind($pdo, $sql));
        }

        public static function modifie($pdo,$email,$mot_de_passe,$nom,$prenom,$type_personne){
            $data = [
                'tidentifiant' => $email,
                'tmdp' => $mot_de_passe,
                'tnom' => $nom,
                'tprenom' => $prenom,
                'ttype_personne' => $type_personne,
            ];
            $sql = "UPDATE personne SET mdp=:tmdp, nom=:tnom, prenom=:tprenom, type_personne=:ttype_personne WHERE email = :tidentifiant";
            
            $stmt= $pdo->prepare($sql);
            return $stmt->execute($data);
        }
        
        public static function supprime($pdo,$email) {
            $data = [
                'tidentifiant' => $email,
            ];
            $sql = "DELETE FROM personne WHERE email = :tidentifiant";
            $stmt= $pdo->prepare($sql);
            return $stmt->execute($data);
        }

        /**
         * Procesus de connex
         *
         * @param unknown $pdo
         * @param unknown $identifiant
         * @param unknown $motDePasse
         * @return boolean
         */
        public function verifieConnection($pdo,$email,$motDePasse) {
            $sql = 'select * from personne '.
                'where personne.email = '.$pdo->quote($email).' ';
            
            if ($reqUser = DbAccess::canFind($pdo,$sql) ) {
                if (
                    Utilisateur::pw_check($motDePasse, $reqUser['mot_de_passe'])
                    || $reqUser['mot_de_passe'] == $motDePasse // pour le momet on laisse car on a mis des mots de passe non chiffré
                ){
                    $this->user = $reqUser;
                    return true;
                }
                else
                    return false;
            }
            else
                return false;
        }
        
        /**
         * Renvoi tout les colonnes de la requetes de connexion utilisateur
         * @return array retourne un array de toutes les colonnes relatif à l'utilisateur connecté
         */
        public function getUser() {
            return $this->user;
        }

        /**
         * Renvoi le code type de l'utilisateur
         * @return unknown
         */
        public function getTypeUser(){
            return  $this->user['type_personne'];
        }

        /**
         * Renvoi true false pour savoir si l'utilisateur est admin
         * @return boolean
         */
        public function isAdmin(){
            return  $this->user['type_personne'] == 'A';
        }        
        
        
        /**
         * sécurisation mdp BCRYPT 
         */
        private static function pw_encode($password){

            return password_hash($password, PASSWORD_DEFAULT);
        }

        private static function pw_check($password, $stored_value){

            return password_verify($password, $stored_value);
        }
    }
?>
