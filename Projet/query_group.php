<?php

	$m = new MongoClient();
	$db = $m->selectDB('test');
	$collection = new MongoCollection($db,'test');
	$json = '{"name":"flare","children":[';
	$result = array();

    $tempMax = 0;
	for ($i=1; $i < 96; $i++) { 

		

		echo($i); //show current departement in terminal
		$tempMoyenne = 0;
		$tempMin = null;
		$deptquery = array('dpt' =>$i);
		$cursor = $collection->find($deptquery);
		$j = 0;

		foreach ($cursor as $doc) {
			$ville = $doc['ville'];
			$temp = $doc['main']['temp'];
			if($temp != null) {
				$tempMoyenne = $tempMoyenne + $temp;
				$j = $j + 1;
			}
		}
		$tempMoyenne = $tempMoyenne/$j;

		if($tempMin == null || $tempMin > $tempMoyenne) {
			$tempMin = $tempMoyenne;
		}

		if($tempMax < $tempMoyenne) {
			$tempMax = $tempMoyenne;
		}

		array_push($result, $tempMoyenne);

		
	}

	foreach($result as $temp) {
		$value = ($tempMax-$temp)*1000;
		$dept = array_keys($result,$temp)[0] + 1;
		$json = $json.'{"name" : "'.$dept.'", "size":'.$value.'},';
	}
	$json = substr($json,0,-1);
	$json = $json.']}';

	$h = fopen("flare.json","w");
	fwrite($h,$json);
	fclose($h);

?>