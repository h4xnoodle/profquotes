<?php

// Parse a quote
// Input: string
// Output: associative array of quote,prof,course
function parseQuote($string,$style='new') {
	switch($style){
		case 'old':
			$pattern = "/[\"|``|'']((.+))[\"|\'\'|``]\s*([\w\s\.-]+)[,]?\s*([A-Za-z&]+\s{0,1}[\w0-9]*)/";
			break;
		case 'new':
			$pattern = "/\s*((.+[\n])+)([\w\s\.-]+)[,]?\s*([A-Za-z&]+\s{0,1}[\w0-9]*)/";
			break;
		default: ;
	}
	
	$matches = array();
	
	preg_match($pattern, $string, $matches);
	
	//Get rid of single quotes
	if(substr($matches[1],0,1) == "`" || substr($matches[1],0,1) == "'")
		$matches[1] = substr($matches[1],1,-1);
		
	$quote['quote'] = rtrim($matches[1]);
	$quote['prof'] = $matches[3]; // 3 instead of 2 since double brackets in new, adjusted in old
	$quote['course'] = preg_replace("/([A-Z&]+)\s?([\d]{3})/", "\${1} \${2}", strtoupper($matches[4])); //make course follow [A-Za-z&]+\s[0-9]{3} format
	$quote['term'] = $_POST['term'];
	$quote['year'] = $_POST['year'];
	
	return $quote;
}

function parseQuotes($quotes,$verified=0) {
	global $Q;
	$style = ($_POST['style']) ? $_POST['style'] : 'new';

	// Because preg_split gets rid of my course numbers if I split by them...
	$quotes = preg_replace("/([A-Za-z&]+\s?[\d]{3}\s{2,})/", "\${1};;", $quotes);
	$quotes = preg_split("/[;]{2}/", $quotes);
	
	$submitSuccess = true;

	if(is_array($quotes)){
		foreach($quotes as $quote) {
			if(strlen($quote) < 2) continue; //sometimes a space is considered a whole quote
			
			$quote = parseQuote($quote,$style);
			
			if(!$verified) {
				global $i;
				verify($quote);
				$i++;
			}
			else {
				$quote['score'] = $_POST['score'];
				if(!$Q->insertQuote($quote))
					$submitSuccess = false;
				if(!$submitSuccess) 
					$Q->errorla("Could not submit quote ".print_r($quote));
			}
		}
	}
	else
		echo '$quotes isn\'t an array...';
		
	if($verified && $submitSuccess) {
		echo "All quotes successfully submitted.";
		$_POST['quotes']= "";
	}
}
// lol
function defTerm() {
	if($_POST['term'])
		echo "<option>".$_POST['term']."</option><option>Fall</option><option>Spring</option><option>Winter</option>";
	else {
		switch(date('n')) {
			case 1: case 2: case 3: case 4:
				echo "<option>Winter</option><option>Spring</option><option>Fall</option>";
				break;
			case 5: case 6: case 7: case 8:
				echo "<option>Spring</option><option>Fall</option><option>Winter</option>";
				break;
			case 9: case 10: case 11: case 12:
				echo "<option>Fall</option><option>Winter</option><option>Spring</option>";
				break;
			default: ;
		}
	}
}

function verify($quote) {
	global $i;
	echo "<tr ";
	if($i % 2)
		echo "style='background-color:#eee;'";
	echo "><td>".$quote['quote']."</td>";
	echo "<td>".$quote['prof']."</td>";
	echo "<td ";
	if(!preg_match("/[A-Za-z&]{2,}\s?[0-9]{3,}/",$quote['course'])) // If course is malformed, likely parsing didn't go well
		echo "style='background-color:red;'";
	echo ">".$quote['course']."</td>";
	echo "<td>".$quote['term']." ".$quote['year']."</td></tr>\n";
}

// Last added - for term/year so I don't add stuff twice
function lastAdded() {
	global $Q;
	$quote = $Q->getLastAdded();
	echo "<p>Last added: <b>".$quote['term']."/".$quote['year']."</b> : ".$quote['quote']."</p>";
}
?>
