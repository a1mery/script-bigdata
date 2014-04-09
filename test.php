
<?php
//Définition des paramètres de connexion
$dbhost = 'localhost';
$dbname = 'test';

//Connexion à la base mongoDB
$mongo = new Mongo("mongodb://$dbhost");
$connection = new MongoClient();
$collection = $connection->selectCollection('test','test');
$db = $mongo->$dbname;
$collection->drop();

// Création d'une nouvelle ressource cURL
  $ch = curl_init();
  $list = generateList();

// Configuration de l'URL et d'autres options
foreach ($list as $dept) {

	print_r(array_keys($list,$dept)[0]);

	foreach ($dept as $ville) {
		print_r($ville);
		curl_setopt($ch, CURLOPT_URL, "http://api.openweathermap.org/data/2.5/weather?q=".$ville.",fr");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);


		// Récupération de l'URL et stockage de la variable en chaine de caractère
		try {
			$result = curl_exec($ch);
			$object = json_decode($result, true); //Transformation de la variable en array
			$insert = array();
			$villeName = explode("\r",$ville)[0];
			if($object != null) {
				if(array_key_exists('weather', $object)) {
					$insert['ville'] = $villeName;
					$insert['dpt'] = array_keys($list,$dept)[0];
					$insert['time'] = time();
					$insert['weather'] = $object['weather'];
					$insert['main'] = $object['main'];
					$insert['wind'] = $object['wind'];
					$collection->insert($insert);
				}
			}


		} catch (Exception $e) {
			echo("Erreur détectée");
			echo(explode("\r",$ville)[0]);
		}

	 }

/*
**
** La connexion est faite, il ne reste plus qu'à enregistrer dans mongo les informations qui nous interessent !
**
*/
}

// Fermeture de la session cURL
curl_close($ch);

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
		$open = fopen($link, "r"); //On lit la liste des villes
		$list[$Dept] = array();
		while(!feof($open)) {
			array_push($list[$Dept],fgets($open,4096)); // On lie la ville à son département
		}
	}

	return $list;
}

?>
