<?php
require_once("TableBase.php");
/**
 * Class livre
 */
class TableLivre extends TableBase
{
	/**
	 * @return string
	 */
	public function getTableName()
    {
        return "livres";
    }

	/**
	 * @return string
	 */
	public function getPrimaryKey()
    {
        return "id";
    }

	/**
	 * Ajout d'un livre lors d'un envoye au serveur.
	 * @param $titre
	 * @param $auteur
	 * @param $annee
	 * @param $isbn
	 * @param $editeur
	 * @param $evaluation
	 * @return bool
	 */
	public function ajouter($titre,$auteur,$annee,$isbn,$editeur,$evaluation)
    {
		$sql = "INSERT INTO livres(titre, auteur, annee, isbn, editeur, evaluation) VALUES (:titre,:auteur, :annee,:isbn,:editeur,:evaluation)";

        try
        {
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(":titre", $titre);
            $stmt->bindParam(":auteur", $auteur);
            $stmt->bindParam(":annee", $annee);
            $stmt->bindParam(":isbn", $isbn);
            $stmt->bindParam(":editeur", $editeur);
            $stmt->bindParam(":evaluation", $evaluation);
            $stmt->execute();
            return true;
        }
        catch(PDOException $e)
        {
            trigger_error("La requête SQL a donné une erreur : " . $e->getMessage());
        }
    }

	/**
	 * Modification d'un livre selon son id
	 * @param $id
	 * @param $titre
	 * @param $auteur
	 * @param $annee
	 * @param $isbn
	 * @param $editeur
	 * @param $evaluation
	 * @return bool
	 */
	public function modifier($id,$titre,$auteur,$annee,$isbn,$editeur,$evaluation)
    {
	    {
		    $sql = "UPDATE livres SET titre=:titre, auteur=:auteur, annee=:annee,isbn=:isbn,editeur=:editeur,evaluation=:evaluation where id=:id";

		    try
		    {
			    $stmt = $this->db->prepare($sql);
			    $stmt->bindParam(":id", $id);
			    $stmt->bindParam(":titre", $titre);
			    $stmt->bindParam(":auteur", $auteur);
			    $stmt->bindParam(":annee", $annee);
			    $stmt->bindParam(":isbn", $isbn);
			    $stmt->bindParam(":editeur", $editeur);
			    $stmt->bindParam(":evaluation", $evaluation);
			    $stmt->execute();
			    return true;
		    }
		    catch(PDOException $e)
		    {
			    trigger_error("La requête SQL a donné une erreur : " . $e->getMessage());
		    }
	    }
    }
}

?>
