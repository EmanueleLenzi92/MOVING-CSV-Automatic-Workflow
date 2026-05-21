<?php

ini_set('max_execution_time', 0);

// Base path of the project. All runtime paths are derived from here so the
// script can run both from CLI and through Apache without relying on cwd.
$baseDir = __DIR__;

// Lightweight file logger used to track the main execution steps without
// flooding the browser output.
function logMessage($logPath, $message) {
	$timestamp = date('Y-m-d H:i:s');
	file_put_contents($logPath, '[' . $timestamp . '] ' . $message . PHP_EOL, FILE_APPEND);
}

// Read a delimited text file while automatically handling semicolon-, comma-
// and tab-separated exports.
function readDelimitedRows($filePath) {
	$rows = array();
	$handle = fopen($filePath, 'r');
	if($handle === false){
		return $rows;
	}

	$delimiter = ',';
	$firstLine = fgets($handle);
	if($firstLine !== false){
		$commaCount = substr_count($firstLine, ',');
		$semicolonCount = substr_count($firstLine, ';');
		$tabCount = substr_count($firstLine, "\t");
		if($semicolonCount >= $commaCount && $semicolonCount >= $tabCount && $semicolonCount > 0){
			$delimiter = ';';
		} elseif($tabCount > $commaCount){
			$delimiter = "\t";
		}
		$parsedFirstLine = str_getcsv(rtrim($firstLine, "\r\n"), $delimiter);
		$rows[] = $parsedFirstLine;
	}

	while (($line = fgets($handle)) !== false) {
		$rows[] = str_getcsv(rtrim($line, "\r\n"), $delimiter);
	}

	fclose($handle);
	return $rows;
}


function extractEventCoordinatesFromGemma3Row($line, $fallbackLon, $fallbackLat) {
	$coordsColumn = isset($line[count($line) - 1]) ? trim($line[count($line) - 1]) : '';
	if($coordsColumn == ''){
		return array($fallbackLon, $fallbackLat);
	}

	$coordEntries = explode(',', $coordsColumn);
	for($i = 0; $i < count($coordEntries); $i++){
		$coordEntry = trim($coordEntries[$i]);
		if($coordEntry == '' || strtolower($coordEntry) == 'null'){
			continue;
		}

		$parts = preg_split('/\s+/', $coordEntry);
		if(count($parts) >= 2 && is_numeric($parts[0]) && is_numeric($parts[1])){
			$eventLat = $parts[0];
			$eventLon = $parts[1];
			return array($eventLon, $eventLat);
		}
	}

	return array($fallbackLon, $fallbackLat);
}


function coordinatesMatch($lonA, $latA, $lonB, $latB, $tolerance = 0.0001) {
	if(!is_numeric($lonA) || !is_numeric($latA) || !is_numeric($lonB) || !is_numeric($latB)){
		return false;
	}

	return abs(floatval($lonA) - floatval($lonB)) <= $tolerance
		&& abs(floatval($latA) - floatval($latB)) <= $tolerance;
}

// Return true when a narrative already has all final outputs on disk so the
// script can be restarted safely without reprocessing completed CSVs.
function hasExistingOutputs($storymapDir, $jsonDir, $owlDir, $csvKey) {
	$storymapMatches = glob($storymapDir . DIRECTORY_SEPARATOR . 'N*_' . $csvKey);
	$jsonMatches = glob($jsonDir . DIRECTORY_SEPARATOR . 'moving.52_N*_' . $csvKey . '.json');
	$owlMatches = glob($owlDir . DIRECTORY_SEPARATOR . 'moving.52_N*_' . $csvKey . '.owl');

	return !empty($storymapMatches) && !empty($jsonMatches) && !empty($owlMatches);
}

$mappa=[];
$userId= 52;
             
$listNations=[
"Q40",    //austria
"Q219",   //bulgaria
"Q34374",  //crete
"Q213",   //Czech Republic
"Q142",    //france
"Q183",    //germany
"Q41",     //grecee
"Q28",     //hungary
"Q38",     //italy
"Q221",     //north macedonia
"Q20",      //norway
"Q45",     //portugal
"Q218",      //romania
"Q403",      //serbia
"Q145",      //UK
"Q22",      //scotland
"Q214",    //slovakia
"Q29",     //spain
"Q39",      //switzerland
"Q43",       //turkey
"Q215",    // slovenia
"Q33"    // finland

];      




$csvCoordLau = array();
$csvCoordLauByCode = array();
$lauRows = readDelimitedRows($baseDir . DIRECTORY_SEPARATOR . "Lau.csv");
foreach($lauRows as $result){
    $csvCoordLau[] = $result;
    if(isset($result[1]) && trim($result[1]) != ""){
	    	$csvCoordLauByCode[trim($result[1])] = $result;
    }
}





// Runtime directories: input CSVs, generated storymaps, JSON for Triplify and
// OWL output. The log file is recreated at every run.
$path    = "input";
$inputDir = $baseDir . DIRECTORY_SEPARATOR . $path;
$storymapDir = $baseDir . DIRECTORY_SEPARATOR . 'stories2Storymap';
$jsonDir = $baseDir . DIRECTORY_SEPARATOR . 'json';
$owlDir = $baseDir . DIRECTORY_SEPARATOR . 'owl';
$triplifyJar = $baseDir . DIRECTORY_SEPARATOR . 'Triplify' . DIRECTORY_SEPARATOR . 'triplify.jar';
$logPath = $baseDir . DIRECTORY_SEPARATOR . 'scriptStories5OSM.log';
$javaCandidates = array(
	'C:\\Users\\Ema\\.p2\\pool\\plugins\\org.eclipse.justj.openjdk.hotspot.jre.full.win32.x86_64_21.0.8.v20250724-1412\\jre\\bin\\java.exe',
	'C:\\Users\\Ema\\.vscode\\extensions\\redhat.java-1.54.0-win32-x64\\jre\\21.0.10-win32-x86_64\\bin\\java.exe',
	'C:\\Users\\Ema\\Downloads\\Protege-5.6.7-win\\Protege-5.6.7\\jre\\bin\\java.exe',
	'C:\\Users\\Ema\\AppData\\Roaming\\ModrinthApp\\meta\\java_versions\\zulu21.38.21-ca-jre21.0.5-win_x64\\bin\\java.exe'
);

if (!file_exists($storymapDir)) {
	mkdir($storymapDir, 0777, true);
}
if (!file_exists($jsonDir)) {
	mkdir($jsonDir, 0777, true);
}
if (!file_exists($owlDir)) {
	mkdir($owlDir, 0777, true);
}

file_put_contents($logPath, "");
logMessage($logPath, 'Starting script on input directory: ' . $inputDir);

$files = scandir($inputDir);

$FinalJsonToWriteStorymap= array();
$FinalJsonToWriteStorymap['slides']= array();
$idNarraCounter = 1;

// Process one narrative CSV at a time. Each file becomes one storymap, one
// intermediate JSON and, if Java is available, one OWL file.
for($i=2; $i<sizeOf($files); $i++){
	$csvKey = trim(pathinfo($files[$i], PATHINFO_FILENAME));
	if(hasExistingOutputs($storymapDir, $jsonDir, $owlDir, $csvKey)){
		logMessage($logPath, 'Outputs already exist for ' . $csvKey . ', file skipped');
		continue;
	}
	if(!isset($csvCoordLauByCode[$csvKey])){
		echo "Missing LAU row for " . $csvKey . "</br>";
		logMessage($logPath, 'Missing LAU row for ' . $csvKey . ', file skipped');
		continue;
	}
	$lauRow = $csvCoordLauByCode[$csvKey];
	$idNarra = $idNarraCounter . "_" . $csvKey;
	$idNarraCounter++;
	logMessage($logPath, 'Processing CSV ' . $files[$i] . ' as narrative N' . $idNarra);

	
	$contaRighe=0;
	$entitiesFallback="";
	$linkEntitiesFallback="";
	$narrationTitle="";
	
	// apro il singolo csv
	$storyRows = readDelimitedRows($inputDir . DIRECTORY_SEPARATOR . $files[$i]);
	if(empty($storyRows)){
		echo "Unable to open file " . $files[$i] . "</br>";
		logMessage($logPath, 'Unable to open file ' . $files[$i]);
		continue;
	}
	
		
	//scorro le linee del csv
	foreach($storyRows as $line) {
		

		// escludi prima riga (titolo, ecc.)
		if($contaRighe != 0) {
			
			// esclude descrizioni vuote (non ci dovrebbero essere)
			if($line[1] != ""){
				
				// Keep the title from the first event and remember the first non-empty
				// entity set as fallback for later empty rows.
				if($contaRighe==1){
					$narrationTitle= $line[0];
				}

				if($entitiesFallback=="" && isset($line[2], $line[3]) && trim($line[2]) != "" && trim($line[3]) != ""){
					$entitiesFallback= $line[2];
					$linkEntitiesFallback= $line[3];
				}
				
				
				// If a row has no entities, reuse the first available entity set found
				// in the same CSV so downstream enrichment can still run.
				if((!isset($line[2]) || trim($line[2])=="") && $entitiesFallback != ""){
					$line[2] = $entitiesFallback;
				}
				if((!isset($line[3]) || trim($line[3])=="") && $linkEntitiesFallback != ""){
					$line[3] = $linkEntitiesFallback;
					logMessage($logPath, 'Row ' . $contaRighe . ' in ' . $files[$i] . ' had empty entities, fallback applied');
				}	
				
				
				// get all entities and entities link from csv line
				$entitiesLink = array();
				if(isset($line[3]) && trim($line[3]) != ""){
					$entitiesLinkWithoutEmptySpace= str_replace(' ', '', $line[3]);
					$entitiesLink= array_values(array_filter(explode(",",$entitiesLinkWithoutEmptySpace), function ($value) {
						return trim($value) != "";
					}));
				}
				
				$eneties= array();
				if(isset($line[2]) && trim($line[2]) != ""){
					$eneties= array_map('trim', explode(",",$line[2]));
					$eneties = array_values(array_filter($eneties, function ($value) {
						return $value != "";
					}));
				}
				
				
				
				// Resolve country metadata from the LAU lookup. The resulting Wikidata
				// country URI is later reused both in the storymap and in Triplify.
							if(isset($lauRow[2]) && $lauRow[2] != "" ){
								$country= trim($lauRow[2][0] . $lauRow[2][1]);
							} else {
								
								if(strpos($lauRow[1], "CH") !== false){
									$letters = "CH";
								} else {
									$letters= trim(explode("_",$lauRow[1])[2][0] . explode("_",$lauRow[1])[2][1]);
								}
								
								if($letters == "GR" ){
									$letters = "EL";
								} else if($letters == "SE") {
									$letters = "RS";
								} else if($letters=="SCA") {
									$letters = "NO";
								}
								
								$country= $letters;
							}

				$pointCoordinate= $lauRow[3];
				preg_match('!\(([^\)]+)\)!',$pointCoordinate,$CoordinatesWithoutParenthesis);
				$longCSVLau = isset($CoordinatesWithoutParenthesis[1]) ? explode(" ",$CoordinatesWithoutParenthesis[1])[0] : '';
				$latCSVLau = isset($CoordinatesWithoutParenthesis[1]) ? explode(" ",$CoordinatesWithoutParenthesis[1])[1] : '';
				list($eventLon, $eventLat) = extractEventCoordinatesFromGemma3Row($line, $longCSVLau, $latCSVLau);
				 
							if($country== "AT"){
								$country= "Austria";
								$wiki= "https://www.wikidata.org/wiki/Q40";
								$nationDescr="country in Central Europe";
							} else if($country== "BG"){
								$country= "Bulgaria";
								$wiki= "https://www.wikidata.org/wiki/Q219";
								$nationDescr="country in Southeast Europe";
							}  else if($country== "CZ"){
								$country= "Czech Republic";
								$wiki= "https://www.wikidata.org/wiki/Q213";
								$nationDescr="country in Central Europe";
							}  else if($country== "FI"){
								$country= "Finland";
								$wiki= "https://www.wikidata.org/wiki/Q33";
								$nationDescr="country in Northern Europe";
							}  else if($country== "FR"){
								$country= "France";
								$wiki= "https://www.wikidata.org/wiki/Q142";
								$nationDescr="country in Western Europe";
							}  else if($country== "DE"){
								$country= "Germany";
								$wiki= "https://www.wikidata.org/wiki/Q183";
								$nationDescr="country in Central Europe";
							}  else if($country== "EL"){
								$country= "Greece";
								$wiki= "https://www.wikidata.org/wiki/Q41";
								$nationDescr="country in Southeast Europe";
							}  else if($country== "HU"){
								$country= "Hungary";
								$wiki= "https://www.wikidata.org/wiki/Q28"; 
								$nationDescr="country in Central Europe";
							}  else if($country== "IT"){
								$country= "Italy";
								$wiki= "https://www.wikidata.org/wiki/Q38";
								$nationDescr="country in Southern Europe";
							} else if($country== "MK"){
								$country= "North Macedonia";
								$wiki= "https://www.wikidata.org/wiki/Q221";
								$nationDescr="country in southeastern Europe";
							} else if($country== "NO"){
								$country= "Norway";
								$wiki= "https://www.wikidata.org/wiki/Q20";
								$nationDescr="country in northern Europe";
							} else if($country== "PT"){
								$country= "Portugal";
								$wiki= "https://www.wikidata.org/wiki/Q45";
								$nationDescr="country in Southwestern Europe";
							} else if($country== "RO"){
								$country= "Romania";
								$wiki= "https://www.wikidata.org/wiki/Q218";
								$nationDescr="country in Central and Eastern Europe";
							} else if($country== "UK"){
								$country= "United Kingdom";
								$wiki= "https://www.wikidata.org/wiki/Q145";
								$nationDescr="country in north-west Europe";
							} else if($country== "RS"){
								$country= "Serbia";
								$wiki= "https://www.wikidata.org/wiki/Q403";
								$nationDescr="country in southeastern Europe";
							} else if($country== "SK"){
								$country= "Slovakia";
								$wiki= "https://www.wikidata.org/wiki/Q214";
								$nationDescr="country in Central Europe";
							} else if($country== "SL"){
								$country= "Slovenia";
								$wiki= "https://www.wikidata.org/wiki/Q215";
								$nationDescr="country in Central Europe";
							} else if($country== "ES"){
								$country= "Spain";
								$wiki= "https://www.wikidata.org/wiki/Q29";
								$nationDescr="country in southwestern Europe with territories in Africa";
							} else if($country== "CH"){
								$country= "Switzerland";
								$wiki= "https://www.wikidata.org/wiki/Q39";
								$nationDescr="country in Central Europe";
							} else if($country== "TR"){
								$country= "Turkey";
								$wiki= "https://www.wikidata.org/wiki/Q43";
								$nationDescr="transcontinental country straddling Western Asia and Southeastern Europe";
							}			
				
				
				
				
				
				// Create the narrative-level object once, using local identifiers instead
				// of the old Postgres-backed ids.
				if($contaRighe==1){
					$A1Object= array("id"=>$idNarra,"_id"=>"A1","name"=> $narrationTitle, "author"=> "moving.".$userId, "idNarra"=> $idNarra, "country" => array("id"=>$wiki,"name"=>$country, "place" => isset($lauRow[4]) ? $lauRow[4] : "", "description"=>$nationDescr), "CSVnumber" => $csvKey);
					$FinalJsonToWriteStorymap["A1"] = $A1Object;
				
				}
								
	
				
				// Build the current event: enrich every entity via Wikidata/QLever and
				// collect the props needed by the frontend and by Triplify.
				$arrProps= (object)[];
				$entityGiveCoordinatesEvent="";
				$arraySlideDescription= array("text" => $line[1] .= " <h5>Entities</h5> " ,"headline" => $line[0]);
				
				
				$selectedImage="";
				$mapColor="#ff9900";
				$eventType="valorisation event";
				if($line[0] == "Mountain landscape and reference chains"){
					$selectedImage= "https://tool.dlnarratives.eu/images/MOVING/".$lauRow[0]."/1.jpg";
				} else if($line[0] == "Key local assets"){
					$selectedImage= "https://tool.dlnarratives.eu/images/MOVING/".$lauRow[0]."/5.jpg";
				} else if($line[0] == "Challenges"){
					$selectedImage= "https://tool.dlnarratives.eu/images/MOVING/".$lauRow[0]."/4.jpg";
				} else if($line[0] == "Innovation"){
					$selectedImage= "https://tool.dlnarratives.eu/images/MOVING/".$lauRow[0]."/6.jpg";
				} else if($line[0] == "Geography and population"){
					$selectedImage= "https://tool.dlnarratives.eu/images/MOVING/".$lauRow[0]."/2.jpg";
				} else if($line[0] == "Tourism"){
					$selectedImage= "https://tool.dlnarratives.eu/images/MOVING/".$lauRow[0]."/3.jpg";
				} else if($line[0] == "Interest on this VC"){
					$selectedImage= "https://tool.dlnarratives.eu/images/interestVC.png";
				}
				$bastaCercareEntitaPerMappa= false;
				logMessage($logPath, 'Event ' . $contaRighe . ' (' . $line[0] . ') in ' . $files[$i] . ' has ' . sizeOf($entitiesLink) . ' entity links');
				for($j=0; $j<sizeOf($entitiesLink); $j++){
					
					
					
					// get wikidata id
					$idEnt= basename($entitiesLink[$j]);
					
					if($idEnt == "Q12054746"){
						$idEnt = "Q2869587";
					} else if($idEnt == "Q14907183"){
						$idEnt = "Q11714837";
					} else if($idEnt == "Q16968816"){
						$idEnt = "Q16189205";
					} else if($idEnt == "Q3773637"){
						$idEnt = "Q3045473";
					} else if($idEnt == "Q6576947"){
						$idEnt = "Q14888394";
					} else if($idEnt == "Q16886469"){
						$idEnt = "Q1422583";
					}
					
					// create props array to save in json of events
					//$arraySongleItem= array($idEnt => array("class"=> "","notes"=> "", "primary"=> array("text"=> "", "title"=> "", "author"=> "", "reference"=> ""), "secondary"=> array("text"=> "", "title"=> "", "author"=> "", "reference"=> "")));
					//array_push($arrProps,$arraySongleItem);
					$arrProps->$idEnt =array("class"=> "other","notes"=> "", "primary"=> array(array("text"=> "", "title"=> "", "author"=> "", "reference"=> "")), "secondary"=> array(array("text"=> "", "title"=> "", "author"=> "", "reference"=> "")));
					
					// Fetch labels, descriptions, coordinates and type information from
					// Wikidata for the current entity.
					$query="PREFIX wd: <http://www.wikidata.org/entity/>
					SELECT DISTINCT ?uri ?coordinates ?type ?itName ?enName ?itDesc ?enDesc ?image ?imgMappa ?birth ?death ?foundation ?foundation2 ?completion ?occupation ?position
					WHERE {
					VALUES ?uri {wd:".$idEnt."}
					OPTIONAL {?uri wdt:P31 ?class.
					}OPTIONAL {?class wdt:P279* ?type.
					 VALUES ?type {
					 wd:Q15222213 wd:Q17334923 wd:Q43229 wd:Q8436 wd:Q488383 wd:Q7184903 wd:Q386724 wd:Q234460 wd:Q5 wd:Q186081 wd:Q1190554 wd:Q35120 wd:Q15474042 wd:Q4167836 wd:Q41176 wd:Q8205328 wd:Q5127848 wd:Q27096213
					}}OPTIONAL { ?uri wdt:P18 ?image. }
					OPTIONAL { ?uri wdt:P569 ?birth. }
					OPTIONAL { ?uri wdt:P570 ?death. }
					OPTIONAL { ?uri wdt:P571 ?foundation. }
					OPTIONAL { ?uri wdt:P580 ?foundation2. }
					OPTIONAL { ?uri wdt:P1619 ?completion. }
					OPTIONAL { ?uri wdt:P106 ?occupation. }
					OPTIONAL { ?uri wdt:P39 ?position. }
					OPTIONAL { ?uri rdfs:label ?itName filter (lang(?itName) = 'it'). }
					OPTIONAL { ?uri rdfs:label ?enName filter (lang(?enName) = 'en'). }
					OPTIONAL { ?uri schema:description ?itDesc filter (lang(?itDesc) = 'it'). }
					OPTIONAL { ?uri schema:description ?enDesc filter (lang(?enDesc) = 'en'). }
					OPTIONAL { ?uri wdt:P242 ?imgMappa. }
					OPTIONAL { ?uri wdt:P625 ?coordinates. }
					} limit 50000 ";
					
					$queryEncoded= urlencode($query);
					
					
					$url = "https://query.wikidata.org/sparql?format=json&query=".$queryEncoded;
					
					// curl for result by wikidata
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					$ua = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';
					curl_setopt($ch, CURLOPT_USERAGENT, $ua);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
					$output = json_decode(curl_exec($ch));
					curl_close($ch); 
					
					
					// Add the narrative to the global map only once, typically when the
					// first event mentions a country entity.
					if($contaRighe==1){	
						
						//se un entità è nella lista delle nazioni
						if(in_array($idEnt, $listNations) && !$bastaCercareEntitaPerMappa){
							$bastaCercareEntitaPerMappa=true;
							$elemArray=[];
							
							$elemArray["Title"] = $narrationTitle;
							$elemArray["Id"] = "N".$idNarra;
							$elemArray["CSVnumber"] = $csvKey;
							
							$pointCoordinate= $lauRow[3];
							preg_match('!\(([^\)]+)\)!',$pointCoordinate,$CoordinatesWithoutParenthesis);
							//var_dump($CoordinatesWithoutParenthesis);
							$elemArray["Latitude"]= explode(" ",$CoordinatesWithoutParenthesis[1])[1];
							$elemArray["Longitude"]= explode(" ",$CoordinatesWithoutParenthesis[1])[0];	
							$elemArray["Country"] = $country;
							$elemArray["Wiki"] = $wiki;	
							
							$elemArray['Link']= "https://tool.dlnarratives.eu/storymaps/" . "moving.".$userId ."/N".$idNarra."/" ;
							$elemArray['Link2']= "https://tool.dlnarratives.eu/horizontalstorymap/?id=N" . $idNarra ."&user=moving." . $userId;
							$elemArray['Link3']= "https://dlnarratives.moving.d4science.org/horizontalstorymap/?id=N" . $idNarra ."&user=moving." . $userId;
							//$elemArray['Link']= "https://tool.dlnarratives.eu/storymaps/prova_auto/" . $files[$i];
							array_push($mappa, $elemArray);
						}
					}
					

					// Prepare the normalized entity object that will be reused both by the
					// event JSON and by the Triplify input JSON.
					$singleEntity= array("_id"=> $idEnt, "_rev"=> "", "itName"=>"", "enName"=>"","itDesc"=>"","enDesc"=>"","image"=>"","coordinatesPoint"=>"","coordinatesPolygon"=>"","type"=>array(),"role"=>array());
					if(!empty($output->results->bindings)){
						
						$data = $output->results->bindings;
						
						//loop in wikidata results and save data in my json
						
						//for($k=0; $k<sizeOf($data); $k++){
							
							if( isset($data[0]->itName)){ 
								$singleEntity["itName"] = $data[0]->itName->value;
							}
							if( isset($data[0]->enName)){ 
								$singleEntity["enName"] = $data[0]->enName->value;
							}
							if( isset( $data[0]->itDesc)){ 
								$singleEntity["itDesc"] = $data[0]->itDesc->value;
							}
							if( isset( $data[0]->enDesc)){ 
								$singleEntity["enDesc"] = $data[0]->enDesc->value;
							}
							if( isset( $data[0]->coordinates)){ 
								$singleEntity["coordinatesPoint"] = $data[0]->coordinates->value;
							}
							
							// find what entity gives its coordinates at the event
							if($singleEntity["coordinatesPoint"] != ""){
								
								preg_match('#\((.*?)\)#', $singleEntity["coordinatesPoint"], $match);
								$pointsLatLng= explode(" ",$match[1]);
								
								if(coordinatesMatch($pointsLatLng[0], $pointsLatLng[1], $eventLon, $eventLat)){
									echo $singleEntity["enName"] . "</br>";
									$entityGiveCoordinatesEvent=$idEnt;
									
								} else {
									//$entityGiveCoordinatesEvent="";
								}
	
							}
							
							// Try to recover a polygon/multipolygon from QLever OSM using the
							// Wikidata id as bridge key.
							if($singleEntity["coordinatesPoint"] != "" ){
								$osmQuery= "PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
											PREFIX geo: <http://www.opengis.net/ont/geosparql#>
											PREFIX osm: <https://www.openstreetmap.org/>
											PREFIX wd: <http://www.wikidata.org/entity/>
											PREFIX osm2rdfkey: <https://osm2rdf.cs.uni-freiburg.de/rdf/key#>

											SELECT ?osm_id ?wkt WHERE {
											  ?osm_id osm2rdfkey:wikidata wd:".$idEnt." .
											  ?osm_id rdf:type osm:relation .
											  ?osm_id geo:hasGeometry/geo:asWKT ?wkt .
											  FILTER(
												STRSTARTS(STR(?wkt), \"POLYGON\")
												|| STRSTARTS(STR(?wkt), \"MULTIPOLYGON\")
											  )
											}
											LIMIT 1";

								$url3 = "https://qlever.dev/api/osm-planet";

								$ch3 = curl_init();
								$ua3 = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';
								curl_setopt($ch3, CURLOPT_URL, $url3);
								curl_setopt($ch3, CURLOPT_USERAGENT, $ua3);
								curl_setopt($ch3, CURLOPT_RETURNTRANSFER, 1);
								curl_setopt($ch3, CURLOPT_POST, 1);
								curl_setopt($ch3, CURLOPT_POSTFIELDS, http_build_query(array("query" => $osmQuery)));
								curl_setopt($ch3, CURLOPT_HTTPHEADER, array('Accept: application/sparql-results+json'));
								$output3Raw = curl_exec($ch3);
								curl_close($ch3);
								$output3 = json_decode($output3Raw);

								if(isset($output3->results->bindings) && !empty($output3->results->bindings) && isset($output3->results->bindings[0]->wkt->value)){
									$singleEntity["coordinatesPolygon"] = $output3->results->bindings[0]->wkt->value;
								}
							}
							
							
							// manage the image
								if($line[0] == "Interest on this VC"){
									$selectedImage= "https://tool.dlnarratives.eu/images/interestVC.png";
									
									if( isset( $data[0]->image)){ 
										$singleEntity["image"] =  $data[0]->image->value;
									} else {
										$singleEntity["image"] = "";
									}
									
									$mapColor="#ff9900";
									$eventType="valorisation event";
								} else if($line[0] == "Mountain landscape and reference chains"){
									$selectedImage= "https://tool.dlnarratives.eu/images/MOVING/".$lauRow[0]."/1.jpg";
									
									if( isset( $data[0]->image)){ 
										$singleEntity["image"] =  $data[0]->image->value;
									} else {
										$singleEntity["image"] = "";
									}
									
									$mapColor="#ff9900";
									$eventType="valorisation event";
								} else if($line[0] == "Key local assets"){
									$selectedImage= "https://tool.dlnarratives.eu/images/MOVING/".$lauRow[0]."/5.jpg";
									if( isset( $data[0]->image)){ 
										$singleEntity["image"] =  $data[0]->image->value;
									} else {
										$singleEntity["image"] = "";
									}
									$mapColor="#ff9900";
									$eventType="valorisation event";
								} else if($line[0] == "Challenges"){
									$selectedImage= "https://tool.dlnarratives.eu/images/MOVING/".$lauRow[0]."/4.jpg";
									if( isset( $data[0]->image)){ 
										$singleEntity["image"] =  $data[0]->image->value;
									} else {
										$singleEntity["image"] = "";
									}
									$mapColor="#ff9900";
									$eventType="valorisation event";
								} else if($line[0] == "Innovation"){
									$selectedImage= "https://tool.dlnarratives.eu/images/MOVING/".$lauRow[0]."/6.jpg";
									if( isset( $data[0]->image)){ 
										$singleEntity["image"] =  $data[0]->image->value;
									} else {
										$singleEntity["image"] = "";
									}
									$mapColor="#ff9900";
									$eventType="valorisation event";
								} else if($line[0] == "Geography and population"){
									$selectedImage= "https://tool.dlnarratives.eu/images/MOVING/".$lauRow[0]."/2.jpg";
									if( isset( $data[0]->image)){ 
										$singleEntity["image"] =  $data[0]->image->value;
									} else {
										$singleEntity["image"] = "";
									}
									$mapColor="#ff9900";
									$eventType="valorisation event";
								} else if($line[0] == "Income and gross value added"){
									$selectedImage= "";
									if( isset( $data[0]->image)){ 
										$singleEntity["image"] =  $data[0]->image->value;
									} else {
										$singleEntity["image"] = "";
									}
									$mapColor="#ff9900";
									$eventType="valorisation event";
								} else if($line[0] == "Tourism"){
									$selectedImage= "https://tool.dlnarratives.eu/images/MOVING/".$lauRow[0]."/3.jpg";
									if( isset( $data[0]->image)){ 
										$singleEntity["image"] =  $data[0]->image->value;
									} else {
										$singleEntity["image"] = "";
									}
									$mapColor="#ff9900";
									$eventType="valorisation event";
								} else if($line[0] == "Employment"){
									$selectedImage= "";
									if( isset( $data[0]->image)){ 
										$singleEntity["image"] =  $data[0]->image->value;
									} else {
										$singleEntity["image"] = "";
									}
									$mapColor="#ff9900";
									$eventType="valorisation event";
								} else if($line[0] == "Concluding remarks"){
									$selectedImage= "";
									if( isset( $data[0]->image)){ 
										$singleEntity["image"] =  $data[0]->image->value;
									} else {
										$singleEntity["image"] = "";
									}
									$mapColor="#ff9900";
									$eventType="valorisation event";
								} else {
									
/* 									if(isset( $data[0]->imgMappa) && $j == 0){
										$singleEntity["image"] = $data[0]->imgMappa->value;
										$selectedImage= $data[0]->imgMappa->value;
									} */
									$eventType="natural event";
									$mapColor=" #2eb82e";
									
 /* 									if( in_array($idEnt, $listNations)){
										
										if(isset( $data[0]->imgMappa) && $stopSearchImage==false){
											$stopSearchImage=true;
											$imgName= substr($data[0]->imgMappa->value, strrpos($data[0]->imgMappa->value, '/') + 1);
											$imgUrl= "https://commons.wikimedia.org/w/index.php?title=Special:Redirect/file&wpvalue=".$imgName."&width=700&type=.jpg";	
											if( isset( $data[0]->image)){ 
												$singleEntity["image"] =  $data[0]->image->value;
											} else {
												$singleEntity["image"] = "";
											}
											$selectedImage= $imgUrl;
										}
									}  */
									
									//Immagine primo evento: solo una volta per la prima entità
									if($j==0){
										
										$wikiDataRegionId= basename($wiki) ;
										// get data entities from wikidata
										$query2="PREFIX wd: <http://www.wikidata.org/entity/>
										SELECT DISTINCT ?imgMappa
										WHERE {
										VALUES ?uri {wd:".$wikiDataRegionId."}
										OPTIONAL {?uri wdt:P31 ?class.
										}OPTIONAL {?class wdt:P279* ?type.
										 VALUES ?type {
										 wd:Q15222213 wd:Q17334923 wd:Q43229 wd:Q8436 wd:Q488383 wd:Q7184903 wd:Q386724 wd:Q234460 wd:Q5 wd:Q186081 wd:Q1190554 wd:Q35120 wd:Q15474042 wd:Q4167836 wd:Q41176 wd:Q8205328 wd:Q5127848 wd:Q27096213
										}}OPTIONAL { ?uri wdt:P18 ?image. }

										OPTIONAL { ?uri wdt:P242 ?imgMappa. }

										} limit 50000 ";
										
										$queryEncoded2= urlencode($query2);
										
										
										$url2 = "https://query.wikidata.org/sparql?format=json&query=".$queryEncoded2;
										
										// curl for result by wikidata
										$ch2 = curl_init();
										curl_setopt($ch2, CURLOPT_URL, $url2);
										$ua2 = 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US) AppleWebKit/525.13 (KHTML, like Gecko) Chrome/0.A.B.C Safari/525.13';
										curl_setopt($ch2, CURLOPT_USERAGENT, $ua2);
										curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
										$output2 = json_decode(curl_exec($ch2));
										curl_close($ch2); 

									$data2 = isset($output2->results->bindings) ? $output2->results->bindings : array();	
									if(!empty($data2) && isset($data2[0]->imgMappa->value)){
										$imgName= substr($data2[0]->imgMappa->value, strrpos($data2[0]->imgMappa->value, '/') + 1);
										$imgUrl= "https://commons.wikimedia.org/w/index.php?title=Special:Redirect/file&wpvalue=".$imgName."&width=700&type=.jpg";	
										$singleEntity["image"] = $imgUrl;
										$selectedImage= $imgUrl;
									}									
									
									
								}
								
								}	

							if( isset( $data[0]->type)){ 
								for($h=0; $h<sizeOf($data); $h++){
									array_push($singleEntity["type"], basename($data[$h]->type->value));
								}

								if(in_array("Q27096213", $singleEntity["type"]) || in_array("Q17334923", $singleEntity["type"])){
									$arrProps->$idEnt["class"] = "place";
								} else if(in_array("Q5", $singleEntity["type"])){
									$arrProps->$idEnt["class"] = "person";
								}  else if(in_array("Q43229", $singleEntity["type"])){
									$arrProps->$idEnt["class"] = "organization";
								} else if(in_array("Q234460", $singleEntity["type"]) || in_array("Q386724", $singleEntity["type"])){
									$arrProps->$idEnt["class"] = "work";
								} else if(in_array("Q7184903", $singleEntity["type"]) || in_array("Q4026292", $singleEntity["type"]) || in_array("Q5127848", $singleEntity["type"])){
									$arrProps->$idEnt["class"] = "concept";
								} else if(in_array("Q41176", $singleEntity["type"]) || in_array("Q8205328", $singleEntity["type"]) || in_array("Q488383", $singleEntity["type"]) || in_array("Q15222213", $singleEntity["type"]) ){
									$arrProps->$idEnt["class"] = "object";
								} else {
									array_push($singleEntity["type"], "other");
									$arrProps->$idEnt["class"] = "other";								
								}


							} 							
						//}
					
					};
					
					// Save the entity once in the narrative-wide item collection. Repeated
					// appearances of the same QID across events are deduplicated here.
	 					$singleEntityJSON = json_encode($singleEntity);
					
					$FinalJsonToWriteStorymap["items"][$idEnt] =  $singleEntity;
					
					$entityLabel = isset($eneties[$j]) ? $eneties[$j] : ($singleEntity["enName"] != "" ? $singleEntity["enName"] : $idEnt);
					$arraySlideDescription["text"] .=  "<span class='tl-entities'><a onmouseover='$(this).tooltip(); $(this).tooltip(\"show\")' data-toggle='tooltip' title='".$singleEntity["enDesc"]."' target='_blank' href='".$entitiesLink[$j]."'>".$entityLabel."</a></span>";
					if($j < (sizeOf($entitiesLink)-1) ){
							$arraySlideDescription["text"] .= " • "; 
					}
				
				}

				if($contaRighe==1 && !$bastaCercareEntitaPerMappa){
					$elemArray=[];
					$elemArray["Title"] = $narrationTitle;
					$elemArray["Id"] = "N".$idNarra;
					$elemArray["CSVnumber"] = $csvKey;
					$pointCoordinate= $lauRow[3];
					preg_match('!\(([^\)]+)\)!',$pointCoordinate,$CoordinatesWithoutParenthesis);
					$elemArray["Latitude"]= explode(" ",$CoordinatesWithoutParenthesis[1])[1];
					$elemArray["Longitude"]= explode(" ",$CoordinatesWithoutParenthesis[1])[0];
					$elemArray["Country"] = $country;
					$elemArray["Wiki"] = $wiki;
					$elemArray['Link']= "https://tool.dlnarratives.eu/storymaps/" . "moving.".$userId ."/N".$idNarra."/" ;
					$elemArray['Link2']= "https://tool.dlnarratives.eu/horizontalstorymap/?id=N" . $idNarra ."&user=moving." . $userId;
					$elemArray['Link3']= "https://dlnarratives.moving.d4science.org/horizontalstorymap/?id=N" . $idNarra ."&user=moving." . $userId;
					array_push($mappa, $elemArray);
				}
				
				// Choose map zoom/polygon priority: LAU polygon first, otherwise entity
				// polygon/point if an entity matches the event coordinates.

				$polygon="";
				if(coordinatesMatch($eventLon, $eventLat, $longCSVLau, $latCSVLau)){
					
					$zoom= 10;
					
					// create polygon of LAU
					if(isset($lauRow[4]) && $lauRow[4] != ""){
						$polygon= $lauRow[4];
					}
					
				} else if ($entityGiveCoordinatesEvent != ""){
				
					if($FinalJsonToWriteStorymap["items"][$entityGiveCoordinatesEvent]["coordinatesPolygon"] != "") {
						$polygon= $FinalJsonToWriteStorymap["items"][$entityGiveCoordinatesEvent]["coordinatesPolygon"];
						$zoom=5;
					} else if($FinalJsonToWriteStorymap["items"][$entityGiveCoordinatesEvent]["coordinatesPoint"] != ""){
						$zoom= 13;
					}
				
				} else {
					$zoom= 13;
				}
				
				
				
				// Final event object consumed by the HTML storymap and reused in the
				// Triplify input JSON.

				$jsonObject = array(
					"_id" => "ev".$contaRighe,
					"text" => $arraySlideDescription,
					"location" => array(
						"name"=>"","lat"=> floatval($eventLat),"lon"=>floatval($eventLon),"zoom"=>$zoom,"line"=>true
					),
					"media" => array("url"=>$selectedImage),
					"date"=> "",					
					"title" => $line[0],
					"latitud" => $eventLat,
					"start"=>"",
					"end"=>"",
					"objurl" =>"",
					"end_date"=> array("year"=>"null","month"=>"","day"=>""),
					"start_date"=> array("year"=>"null","month"=>"","day"=>""),
					"type"=>"no type",
					"longitud" => $eventLon,
					"unique_id" => "slide-ev".$contaRighe,
					"eventMedia" => $selectedImage,
					"eventMediaCaption" => "",
					"notes" => "",
					"description" => str_replace("<h5>Entities</h5>","",$line[1]),
					"position" => $contaRighe,
					"props" => $arrProps,
					"mapMarkerColor"=> $mapColor,
					"type" => $eventType,
					"polygon" => $polygon
				);
				
				//echo $line[0] .": " . $selectedImage . "</br>";
				
				

				
				$myJSON = json_encode($jsonObject);
					

				
				$idEven= "ev".$contaRighe;
				$FinalJsonToWriteStorymap["events"][$idEven] = $jsonObject;
				
				

				
				array_push($FinalJsonToWriteStorymap['slides'], $jsonObject);
				
				//var_dump($FinalJsonToWriteStorymap);
				
				

			
			

		
			}
		
		}
		
		
		
		$contaRighe++;
		
	
	}
	
	// Persist the storymap assets for the current narrative.
	
	$storymapNarrativeDir = $storymapDir . DIRECTORY_SEPARATOR . 'N'.$idNarra;
	if (!file_exists($storymapNarrativeDir)) {
		mkdir($storymapNarrativeDir, 0777, true);
		chmod($storymapNarrativeDir, 0777);
	}

	$fp = fopen($storymapNarrativeDir . DIRECTORY_SEPARATOR . 'slide.json', 'w');
	fwrite($fp, json_encode($FinalJsonToWriteStorymap));
	fclose($fp);
	chmod($storymapNarrativeDir . DIRECTORY_SEPARATOR . 'slide.json', 0777);
	logMessage($logPath, 'Wrote slide.json for N' . $idNarra);
	

	$html= '<html>
	<head>
		<meta charset="UTF-8">
		<title>Narrative - '. $narrationTitle .'</title>
		<link rel="stylesheet" type="text/css" href="../../../lib/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="../../../lib/narra.css" />
		<script src="../../../lib/jquery-3.2.1.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="../../../lib/jquery-ui.min.js" type="text/javascript" charset="utf-8"></script>
		<script src="../../../lib/bootstrap.min.js" type="text/javascript" charset="utf-8"></script>
		
		<script src="../../../lib/typeahead.bundle.min.js" type="text/javascript" charset="utf-8"></script>
		

		<script src="../../lib/visualization.js" type="text/javascript" charset="utf-8"></script>

		<link rel="stylesheet" href="https://cdn.knightlab.com/libs/storymapjs/0.8.6/css/storymap.css">
		<script type="text/javascript" src="../../../lib/storymap.js"></script>  
		<link rel="stylesheet" type="text/css" href="../../../lib/timeline.css" />
		<script src="../../../lib/timeline-min.js" type="text/javascript" charset="utf-8"></script>
		
		<link rel="stylesheet" type="text/css" href="../../lib/narrativeVisualization.css" />

	</head>

	<body>
		<div id="menu">
			<div id="titleTable">
			<h1>'. $narrationTitle .'</h1>
			</div>
		
			<div class="otherVisual">
			  <button class="dropbtn">Other Visualizations</button>
			  <div class="otherVisual-content">
				<a href="?visualization=map">Storymap</a>
				<a href="?visualization=timeline">Timeline</a>
				
			  </div>
			</div>
			
			<a href="../../../Search/?user=moving.52&id=N'.$idNarra.'&dataset=moving">
			<div class="otherNarratives">
			  <button class="dropbtn">Search</button>

			</div>
			</a>
			
		</div>
		
		<div id="mapdiv"></div>

		<!-- <script src="../../lib/LoadJsonSlidesAndBugFixSlide.js" type="text/javascript" charset="utf-8"></script>-->
		
		
	</body>
	</html>
	';
	
	// menu grafo pagina storymap
	//<a href="../../../Entity_With_Events/?user=moving.52&idNarra=N'.$idNarra.'&dataset=moving">Entity With Related Events</a>
	
	$fpNcsv = fopen($storymapNarrativeDir . DIRECTORY_SEPARATOR . $files[$i].'.txt', 'w');
	fwrite($fpNcsv, "");
	fclose($fpNcsv);	
	
	$fp1 = fopen($storymapNarrativeDir . DIRECTORY_SEPARATOR . 'index.html', 'w');
	fwrite($fp1, $html);
	fclose($fp1);
	chmod($storymapNarrativeDir . DIRECTORY_SEPARATOR . 'index.html', 0777);	
	
	
	

	// Build the compact JSON consumed by Triplify and, if Java is available,
	// transform it into an OWL file.
	$results = array("entities"=> $FinalJsonToWriteStorymap["items"], "narra"=> $FinalJsonToWriteStorymap["A1"], "events"=> $FinalJsonToWriteStorymap["events"]);
	$dataJsonToTriplify = json_encode($results);
	$jsonOutputPath = $jsonDir . DIRECTORY_SEPARATOR . "moving.52_N" . $idNarra . ".json";
	$myfile1 = fopen($jsonOutputPath, "w+") or die("Unable to open file!");
	fwrite($myfile1, $dataJsonToTriplify);
	fclose($myfile1);
	logMessage($logPath, 'Wrote Triplify JSON ' . $jsonOutputPath);

	
	$javaBin = "";
	foreach($javaCandidates as $javaCandidate){
		if(file_exists($javaCandidate)){
			$javaBin = $javaCandidate;
			break;
		}
	}
	if($javaBin == ""){
		$javaCheckOutput = array();
		$javaCheckStatus = 0;
		exec('where java 2>NUL', $javaCheckOutput, $javaCheckStatus);
		if($javaCheckStatus === 0 && !empty($javaCheckOutput)){
			$javaBin = $javaCheckOutput[0];
		}
	}
	if($javaBin != ""){
		$owlOutputPath = $owlDir . DIRECTORY_SEPARATOR . "moving.52_N".$idNarra.".owl";
		$cmd = '"' . $javaBin . '" -jar "' . $triplifyJar . '" "' . $jsonOutputPath . '" "' . $owlOutputPath . '" 2>&1';
		$output=null;
		$retval=null;
		$previousCwd = getcwd();
		logMessage($logPath, 'Running Triplify for N' . $idNarra);
		chdir($baseDir);
		exec($cmd,$output,$retval);
		if($previousCwd !== false){
			chdir($previousCwd);
		}
		print_r($output);
		logMessage($logPath, 'Triplify finished for N' . $idNarra . ' with exit code ' . $retval);
	} else {
		echo "Java not found in PATH. Skipping Triplify for N" . $idNarra . "</br>";
		logMessage($logPath, 'Java not found, Triplify skipped for N' . $idNarra);
	} 
	

	// svuoto l'array SLIDE e items per le nuove storie
	$FinalJsonToWriteStorymap['slides']= array();
	unset($FinalJsonToWriteStorymap["items"]);
	unset($FinalJsonToWriteStorymap["events"]);
	
	
	
	

	


}


// Write the global map index containing one entry per processed narrative.
$fp = fopen($storymapDir . DIRECTORY_SEPARATOR . 'map.json', 'w');
fwrite($fp, json_encode($mappa));
fclose($fp);
logMessage($logPath, 'Wrote global map.json with ' . sizeOf($mappa) . ' narratives');
print_r ($mappa);

?>
