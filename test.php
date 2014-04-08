
<?php
//Définition des paramètres de connexion
$dbhost = 'localhost';
$dbname = 'test';

//Connexion à la base mongoDB
$mongo = new Mong("mongodb://$dbhost");
$db = $mongo->$dbname;

// Création d'une nouvelle ressource cURL
  $ch = curl_init();
  $list = generateList();

// Configuration de l'URL et d'autres options
foreach ($list as $dept) {
	

	foreach ($dept as $ville) {
		
		curl_setopt($ch, CURLOPT_URL, "http://api.openweathermap.org/data/2.5/weather?q=".$ville.",fr");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); 
		curl_setopt($ch, CURLOPT_HEADER, 0);


		// Récupération de l'URL et stockage de la variable en chaine de caractère
		$result = curl_exec($ch);
		$object = json_decode($result, true); //Transformation de la variable en array
		print_r($object);
	 }

/*
**
** La connexion est faite, il ne reste plus qu'à enregistrer dans mongo les informations qui nous interessent !
**
*/
}

// Fermeture de la session cURL
curl_close($ch);

generateList();

function generateList() {
	$fp = fopen("Departements/liste.txt", "r");
	$ligne = 0;
	$list_temp = array();
	$list = array();

	//lecture de la liste des départements
	while (!feof($fp)) { //on parcours toutes les lignes
		array_push($list_temp,fgets($fp,4096));
	}

	for ($i=0; $i < sizeof($list_temp); $i++) { // Pour chaque département
		$Dept = explode("\n", $list_temp[$i])[0]; //On récupère le nom du département
		$link = "Departements/Villes/".$Dept.".txt";
		$fp = fopen($link, "r"); //On lit la liste des villes
		$list[$Dept] = array();
		while(!feof($fp)) {
			array_push($list[$Dept],fgets($fp,4096)); // On lie la ville à son département
		}
	}

	return $list;
}

?>
