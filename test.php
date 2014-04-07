
<?php
//Définition des paramètres de connexion
$dbhost = 'localhost';
$dbname = 'test';

//Connexion à la base mongoDB
$mongo = new Mong("mongodb://$dbhost");
$db = $mongo->$dbname;

// Création d'une nouvelle ressource cURL
$ch = curl_init();
$list = array( // Compléter la liste des villes necessaires (de préférence, la placer en dessous de code, que ça ne gène pas)
	"Paris",
	"Franconville",
	"Marseille"
	);

// Configuration de l'URL et d'autres options
for ($i=0; $i < sizeof($list); $i++) { 
	curl_setopt($ch, CURLOPT_URL, "http://api.openweathermap.org/data/2.5/weather?q=".$list[$i].",fr");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
	curl_setopt($ch, CURLOPT_HEADER, 0);
	// Récupération de l'URL et stockage de la variable en chaine de caractère
$result = curl_exec($ch);
$object = json_decode($result, true); //Transformation de la variable en array
print_r($object['coord']);

/*
**
** La connexion est faite, il ne reste plus qu'à enregistrer dans mongo les informations qui nous interessent !
**
*/


}

// Fermeture de la session cURL
curl_close($ch);
?>
