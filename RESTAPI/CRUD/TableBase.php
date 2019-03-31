<?php
/**
 * Class livre
 * @author   Guillaume Harvey (Je lui donne le mérite, il nous a donné un bon squellete.
 */
    abstract class TableBase
    {
        protected $db;
        //méthode abstraite getTableName() qui va me retourner le nom de la table
        //les classes qui héritent de TableBase sont donc obligées de redéfinir cette méthode
	    /**
	     * @return mixed
	     */
	    abstract function getTableName();
        //même chose pour la clé primaire

	    /**
	     * @return mixed
	     */
	    abstract function getPrimaryKey();

	    /**
	     * TableBase constructor.
	     */
	    public function __construct()
        {
            //dans le constructeur on crée la connexion à la BD qui sera utilisée par toutes les méthodes de l'objet
            try
            {
                //pour l'encodage utf8
                $options = array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                );
                //connexion à la base de données
                $this->db = new PDO("mysql:host=localhost;dbname=bibliotheque", "root", "", $options);
                //forcer PDO à générer des exceptions pour les requêtes SQL
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            }
            catch(PDOException $e)
            {
                trigger_error("Erreur lors de la connexion : " . $e->getMessage());
            }
        }

	    /**
	     * @return array
	     */
	    public function lireTous()
        {
            try
            {
                $sql = "SELECT * FROM " . $this->getTableName();
                $resultats = $this->db->query($sql);
                return $resultats->fetchAll(PDO::FETCH_ASSOC);
            }
            catch(PDOException $e)
            {
                trigger_error("La requête SQL a donné une erreur : " . $e->getMessage());
            }
        }

	    /**
	     * @param $id
	     *
	     * @return mixed
	     */
	    public function lire($id)
        {
            $sql = "SELECT * FROM " . $this->getTableName() . " WHERE " . $this->getPrimaryKey() . "=:id";

            try
            {
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":id", $id);
                $stmt->execute();
                //retourner la seule rangée (puisqu'on filtre par la clé primaire)
                return $stmt->fetch(PDO::FETCH_ASSOC);
            }
            catch(PDOException $e)
            {
                trigger_error("La requête SQL a donné une erreur : " . $e->getMessage());
            }
        }

	    /**
	     * @param $id
	     *
	     * @return int
	     */
	    public function supprimer($id)
        {
            $sql = "DELETE FROM " . $this->getTableName() . " WHERE " . $this->getPrimaryKey() . "=:id";

            try
            {
                $stmt = $this->db->prepare($sql);
                $stmt->bindParam(":id", $id);
                $stmt->execute();
                //retourner le nombre de rangées supprimées
                return $stmt->rowCount();
            }
            catch(PDOException $e)
            {
                trigger_error("La requête SQL a donné une erreur : " . $e->getMessage());
            }
        }
        /* POUR UTILISATION FUTURE, une fonction qui permet d'envoyer n'importe quelle requête */
	    /**
	     * @param $sql
	     * @param array $data
	     *
	     * @return bool|PDOStatement
	     */
	    public function requete($sql, $data = array())
        {
            try
            {
                $stmt = $this->db->prepare($sql);
                $stmt->execute($data);
                return $stmt;
            }
            catch(PDOException $e)
            {
                trigger_error("La requête SQL a donné une erreur : " . $e->getMessage());
            }
        }
    }

?>
