<?php
//entêtes de réponse requise
header("Access-Control-Allow-Origin: *");
//spécifier que la réponse est en JSON
header("Content-Type: application/json; charset=UTF-8");
//connexion à la BD
require_once("../CRUD/TableLivre.php");
$dbLivres = new TableLivre();
//structure de contrôle reliée à la METHOD de la requête
switch($_SERVER["REQUEST_METHOD"])
{
    case "GET":
            //lecture de tous les livres
            $livres = $dbLivres->lireTous();
            $json = json_encode($livres);
            echo $json;
            http_response_code(200);
        break;
    case "POST":
        //obtenir le corps de la requête
        $data = file_get_contents("php://input");
        $livre = json_decode($data);
        /*Lorsque le id du livre est envoyé nous voulons supprimer
        de la base de donnée et reafficher le contenu*/
	    //création d'un livre
	    //si le livre est bel et bien du bon format (avec tous les attributs nécessaires)
	    if($livre->action == 'ajouterLivre') {
		    if ( isset( $livre->titre, $livre->auteur, $livre->annee, $livre->isbn, $livre->editeur, $livre->evaluation ) ) {
			    //insérer l'article dans la base de données
			    $retour = $dbLivres->ajouter( $livre->titre, $livre->auteur, $livre->annee, $livre->isbn, $livre->editeur, $livre->evaluation );
			    //si l'ajout a fonctionné, retourner un code 201 et le JSON inséré - CREATED
			    if ( $retour ) {
				    http_response_code( 201 );
				    echo json_encode( $retour );
			    } else {
				    //l'insertion n'a pas fonctionné - le livre existait déjà ou une clé étrangère n'est pas respectée - CONFLICT
				    http_response_code( 409 );
			    }
		    } else {
			    //le JSON envoyé en paramètres ne contient pas les attributs nécessaires - BAD REQUEST
			    http_response_code( 400 );
		    }
	    }
	    //Rechercher l'action modifier pour completer UPDATE
	    //Confirmer qu'on a bel et bien tous les informartions nécessaires,
	    if($livre->action == 'modifierleLivre'){
		    if(isset($livre->id,$livre->titre,$livre->auteur,$livre->annee,$livre->isbn,$livre->editeur,$livre->evaluation))
		    {
			    $retour = $dbLivres->modifier($livre->id,$livre->titre, $livre->auteur,$livre->annee,$livre->isbn,$livre->editeur,$livre->evaluation);
			    if($retour){
				    http_response_code(204);
				    $resultat = $dbLivres->lireTous();
				    echo json_encode( $resultat );
			    }
		    }
	    }
	    //Rechercher l'action modifier pour completer UPDATE
	    //Confirmer qu'on a recu l'id de l'élément a supprimer.
        if($livre->action == 'livreSupprime'){
	        if ( isset( $livre->id ) ) {
		        $suppression = $dbLivres->supprimer( $livre->id );
		        if ( $suppression ) {
			        http_response_code( 204 );
			        $resultat = $dbLivres->lireTous();
			        echo json_encode( $resultat );
		        }
	        }
        }
        /*Rechercher un livre selon l'id envoyer et l'action en paramèetre*/
        //Pour afficher les valeurs dans les inputs lors d'une modification futur.
	    if($livre->action== 'leLivre'){
		    $livre = $dbLivres->lire($livre->id);
		    if($livre)
		    {
			    echo json_encode($livre);
		    }
		    else
		    {
			    http_response_code(404);
		    }
        }
        break;
}
?>
