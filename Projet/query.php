<?php

	$m = new MongoClient();
	$db = $m->selectDB('test');
	$collection = new MongoCollection($db,'test');
	$json = '{"name":"flare","children":[';

	for ($i=1; $i < 96; $i++) { 

		

		echo($i);
		$deptquery = array('dpt' =>$i);
		$cursor = $collection->find($deptquery);
		$json = $json.'{"name" : "'.$i.'","children": [';
		foreach ($cursor as $doc) {
			$ville = $doc['ville'];
			$temp = $doc['main']['temp'];
			if(sizeof($temp) != 0) {
				$json = $json.'{"name" : "'.$ville.'", "size":'.$temp.'},';
			}
		}
		$json = substr($json,0,-1);
		$json = $json.']},';
	}
	$json = substr($json,0,-1);
	$json = $json.']}';

	$h = fopen("flare.json","w");
	fwrite($h,$json);
	fclose($h);

?>