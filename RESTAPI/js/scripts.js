/**
 * Gestion d'évènement d'une bibliothèque
 * Communication à travers PHP  avec ajax/jquery
 * @desc 4 évènements
 *          1-Afficher les livres
 *               @return json
 *          2-Ajouter un livre
 *          3-Supprimer un livre
 *          4-Modifier un livre
 *               @return json
 * @required rest_livres.php
 * @param string #id
 * @param string #titre
 * @param string #auteur
 * @param string #isbn
 * @param string #editeur
 * @param string #evaluation
 * @global À noter,j'envoye une variable action dans mes requêtes
 *          pour ne pas provoquer d'erreur dans le fichier rest_livres.php et structurer.
 */

$(document).ready(function() {
    var submit = $("#submit");
    var boutonListeLivre = $("#voirLivres");
    /**Lorsque qu'on soumet la forme en click enclencher un événement**/
    submit.click( function(event) {
        //éviter le chargement de page lors d'un envoye
        event.preventDefault();
        //Récupérer les valeurs et l'ajouter dans un objet Javascript ce qui va permettre
        // la conversion en JSON avec la fonction stringify lors de l'envoye au serveur.
        var leTitre = $("#titre").val();
        var leAuteur = $("#auteur").val();
        var Lannee = $("#annee").val();
        var anneeLivre = parseInt(Lannee);
        var leISBN = $("#isbn").val();
        var leEditeur = $("#editeur").val();
        var laEvaluation = $("#evaluation").val();
        var action = "ajouterLivre";
        var payload = {
                titre: leTitre,
                auteur: leAuteur,
                annee: anneeLivre,
                isbn: leISBN,
                editeur: leEditeur,
                evaluation: laEvaluation,
                action:action
        };
        if(leTitre !== "" && leAuteur !==""&& Lannee !=="" && leISBN !=="" && leEditeur !=="" && laEvaluation !==""){
            $.ajax({
                "url": "js/rest_livres.php",
                "type": "POST",
                "contentType": "application/json",
                "dataType": "json",
                "data":JSON.stringify(payload),
                "success": function (data) {
                    if(data){
                        toastr.success('Vous avez ajouté un livre!');
                    }
                }
            });
        }
    });
/**Affichage des livre dans ma base de donner**/
     boutonListeLivre.click( function() {
         /**Envoyer une demande GET qui va directement lire la base de donner selon,
          *  le chemin donné dans mon rest_livres .php
          */
         var mesResultats = $("#resultat");
              $.ajax({
                "type": "GET",
                "url": "js/rest_livres.php",
                "success": function( data ) {
                  if(data){
                         afficherListe(data);
                      toastr.success('Affichage de la librairie!');
                  }
                }
              });
              //Fonction d'affichage de mes données livres
          function afficherListe(xhr){
              var lesLivres = [];
              lesLivres +="<table class='lesResultats '>\n" +
                  "              <thead>\n" +
                  "                <tr>\n" +
                  "                      <th>Titre</th>\n" +
                  "                      <th>Auteur</th>\n" +
                  "                      <th>Annee</th>\n" +
                  "                      <th>ISBN</th>\n" +
                  "                      <th>Editeur</th>\n" +
                  "                      <th>Evaluation</th>\n" +
                  "                  </tr>\n" +
                  "                </thead>\n" +
                  "              <tbody>\n";
              for(let i = 0; i<  xhr.length; i++){
                  lesLivres += "<tr><td>"+xhr[i].titre+"</td>";
                  lesLivres += "<td>"+xhr[i].auteur+"</td>";
                  lesLivres += "<td>"+xhr[i].annee+"</td>";
                  lesLivres += "<td>"+xhr[i].isbn+"</td>";
                  lesLivres += "<td>"+xhr[i].editeur+"</td>";
                  lesLivres += "<td>"+xhr[i].evaluation+"</td>";
                  lesLivres += "<td><button type='button' class='supprimerLivre' data-id='"+xhr[i].id+"'>X</button></td>";
                  lesLivres += "<td><button type='button' class='modifierLivre' data-id='"+xhr[i].id+"'>Modifier</button></td></tr>";
              }
              lesLivres += "</tbody></table>";
              //Affichage
              mesResultats.html(lesLivres);
          }
          //Chercher le id lorsque l'utilisateur veut supprimer un livre
         //Ensuite envoyer l'id avec l'action pour confirmer la supppression
         $(document).on("click", ".supprimerLivre", function(){
             var id = $(this).attr("data-id");
             var retourLivre = $(this).parents("tr");
             var action = 'livreSupprime';
             var leLivre = {id:id,action:action};
             $.ajax({
                 "type": "POST",
                 "url": "js/rest_livres.php",
                 "contentType": "application/json",
                 "dataType": "json",
                 "data":JSON.stringify(leLivre),
                 "success" : function(retour) {
                     retourLivre.remove();
                     toastr.success('Vous avez supprimer un livre!');
                 }
             });
         });
         //Lorsque l'utilisateur veut modifier un livre, on ira chercher le livre
         //spécifié par son id et on retourne l'intégral du livre dans les inputs.
         $(document).on("click", ".modifierLivre", function(){
             var id = $(this).attr("data-id");
             var action = 'leLivre';
             var leLivre = {id:id,action:action};
             $.ajax({
                 "type": "POST",
                 "url": "js/rest_livres.php",
                 "contentType": "application/json",
                 "data":JSON.stringify(leLivre),
                 "dataType": "json",
                 "success" : function(data) {
                     $("#livreID").val(data.id);
                     $("#titre").val(data.titre);
                     $("#auteur").val(data.auteur);
                     $("#annee").val(data.annee);
                     $("#isbn").val(data.isbn);
                     $("#editeur").val(data.editeur);
                     $("#evaluation").val(data.evaluation);
                 }
             });
         });
         //répétition de code...
         //lorsqu'il voudra modifié le livre, on récupère les valeurs dans les champs.
         //et on lui envoye les valeurs au complet.
         $(document).on("click", "#modifier", function(event){
             //éviter le chargement de page lors d'un envoye
             event.preventDefault();
             var id = $("#livreID").val();
             var leTitre = $("#titre").val();
             var leAuteur = $("#auteur").val();
             var Lannee = $("#annee").val();
             var anneeLivre = parseInt(Lannee);
             var leISBN = $("#isbn").val();
             var leEditeur = $("#editeur").val();
             var laEvaluation = $("#evaluation").val();
             var action = "modifierleLivre";
             var payload = {
                 id:id,
                 titre: leTitre,
                 auteur: leAuteur,
                 annee: anneeLivre,
                 isbn: leISBN,
                 editeur: leEditeur,
                 evaluation: laEvaluation,
                 action:action
             };
             if(id !== ""&& leTitre !== "" && leAuteur !==""&& Lannee !=="" && leISBN !=="" && leEditeur !=="" && laEvaluation !==""){
                 $.ajax({
                     "url": "js/rest_livres.php",
                     "type": "POST",
                     "contentType": "application/json",
                     "dataType": "json",
                     "data":JSON.stringify(payload),
                     "success": function (data) {
                         if(data){
                             toastr.success('Vous avez modifier le livre!');
                         }
                     }
                 });
             }
         });
    });

});
