<?php 
    require_once '../class/classVehicule.php';
    class ClientDemande {
        // On enregistre les information de connexion du driver pdo
        private $demande;

        /**
         * TB CLTDEM 01
         * 
         * ATTENTION : l'array est volontairement mis dans un ordre d'action,
         * le processus de mise à jour de statut controle l'ordre de lequel les étapes de changement peuvent se réaliser 
         * par chronolohie ascendante de l'index de l'array (0,1,2,3,....) et non l'inverse
         */
        public static $statut = array(
                'INI' => 'Initial',
                'REF' => 'Refus',   
                'ENC' => 'Encours', // impact statut vehicule = RESEVER
                'VAL' => 'Validé',  // impact statut vehicule = VENDU
                'REJ' => 'Rejeté',   // impact statut vehicule = PUBLIER
            );

        /**
         * le verbe __construct est relatif au constructeur de class
         * quand on 
         * @param unknown $pdo
         */
        public function __construct() {
        }

        public static function getListeStatut(){
            return self::$statut;
        }

        /**
         * Liste des utilisateurs
         *
         * @param unknown $pdo
         * @return unknown
         */
        public static function getListe($pdo){
            $sql = "select client_demande.*,offre.description from client_demande left outer join offre on offre.idx_offre = client_demande.idx_offre";
            return DbAccess::getRequeteSql($pdo, $sql);
        }

        public static function getRaw($pdo,$idxContactClient){
            $sql = "select * from client_demande where idx_contact_client = $idxContactClient ";
            return DbAccess::canFind($pdo, $sql);
        }
        
        
        /**
         * Fonction pour l'ajout d'utilisateur
         * @param unknown $identifiant
         * @param unknown $mot_de_passe
         * @param unknown $nom
         * @param unknown $prenom
         * @param unknown $type_utilisateur
         */
        public static function ajoute($pdo,$nom,$prenom,$email,$phone,$adresse,$message,$idxVehicule){
        $data = [
            'nom' => $nom,
            'prenom' => $prenom,
            'email' => $email,
            'phone' => $phone,
            'adresse' => $adresse,
            'message' => $message,
            'idxVehicule' => $idxVehicule
        ];
        $sql = "INSERT INTO client_demande (nom,prenom,email,phone,adresse,message,idx_offre) VALUES (:nom, :prenom, :email, :phone, :adresse, :message , :idxVehicule)";
            
            $stmt= $pdo->prepare($sql);
            return $stmt->execute($data);
        }

        /**
         * Permet de modifier le statut de la demande 
         * qui peut déclencher la modification du statut du véhicule afférant à la demande
         */
        public static function modifierStatus($pdo,$idxContactClient,$statut) {
            if ($raw = ClientDemande::getRaw($pdo,$idxContactClient)){
                switch ($statut){
                    case 'INI':
                        $nouveauStatutDemande  = null;
                        $nouveauStatutVehicule = null;
                        break;
                    case 'REF':
                        $nouveauStatutDemande = $statut;
                        $nouveauStatutVehicule = null;
                        break;
                    case 'ENC':
                        $nouveauStatutDemande = $statut;
                        $nouveauStatutVehicule = 'R';
                        break;
                    case 'VAL':
                        $nouveauStatutDemande = $statut;
                        $nouveauStatutVehicule = 'V';
                        break;
                    case 'REJ':
                        $nouveauStatutDemande = $statut;
                        $nouveauStatutVehicule = 'P';
                        break;
                    default:
                        $nouveauStatutDemande  = null;
                        $nouveauStatutVehicule = null;
                        break;
                }
                if ($nouveauStatutDemande != null){
                    /**
                     * Conrtoler que la demande de statut est bien possible
                     */
                    $statutOrigine = $raw['status'];

                    /**
                     * On recherche la valeur de l'index de chaqune des clé d'index de l'array de statut
                     * ainsi l'on peut comparer l'ordre de la demande de mise à jour
                     */
                    $indexStatutOrigine =  array_search($statutOrigine, array_keys(ClientDemande::getListeStatut())); 
                    $indexStatutNouveau =  array_search($nouveauStatutDemande, array_keys(ClientDemande::getListeStatut())); 

                    /**
                     * Controle de la regle de gestion interdisant la mise à jour de statut de manière inversé
                     */
                    if ($indexStatutNouveau > $indexStatutOrigine){
                        if (DbAccess::transactionDebut($pdo)){
                            $sql = "update client_demande set status = '$nouveauStatutDemande' where idx_contact_client =  $idxContactClient";

                            $stmt= $pdo->prepare($sql);
                            if ($stmt->execute()){
                                if ($nouveauStatutVehicule != null)
                                    if (Vehicule::modifierStatus($pdo,$raw['idx_offre'],$nouveauStatutVehicule))
                                        return DbAccess::transactionValide($pdo);
                                    else{
                                        DbAccess::transactionDefait($pdo);
                                        return false;
                                    }
                                else{
                                    DbAccess::transactionValide($pdo);
                                    return true;
                                }                                
                            }
                            else{
                                DbAccess::transactionDefait($pdo);
                                return false;
                            }                                                        
                        }
                        else
                            return false;
                    }
                    else
                        return false;
                }
                else
                    return false;
            }
            return false;

            /** 'INI' => 'Arrivage',
                'REF' => 'Refus',   
                'ENC' => 'Encours', // impact statut vehicule = RESEVER
                'VAL' => 'Publié',  // impact statut vehicule = VENDU
                'REJ' => 'Rejet',   // impact statut vehicule = PUBLIER

             */
        }

        /**
          * TODO fonction à terminer dans le cas du suppression d'une demande
          * public static function supprime() {}
          */       
    }
?>