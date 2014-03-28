
<?php
// Création d'une nouvelle ressource cURL
$ch = curl_init();
$list = array(
	"Paris",
	"Franconville",
	"Marseille"
	);

// Configuration de l'URL et d'autres options
for ($i=0; $i < sizeof($list); $i++) { 
	curl_setopt($ch, CURLOPT_URL, "http://api.openweathermap.org/data/2.5/weather?q=".$list[$i].",fr");
	curl_setopt($ch, CURLOPT_HEADER, 0);
	// Récupération de l'URL et affichage sur le naviguateur
curl_exec($ch);
}
// Fermeture de la session cURL
curl_close($ch);
?>
