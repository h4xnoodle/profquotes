<?php

require('database.class.php');

//define("MAINTENANCE", 1);

// All quote methods
class Quotes extends Database {

	// Make a database connection
	function __construct() {
		parent::__construct();
	}
	
	// Insert a quote into the DB
	function insertQuote($quote) {
		// Check for duplicates before insertion? Check against LIKE quote, prof+term+year?
		
		$query = "INSERT INTO $this->table (quote,prof,course,term,year)
			VALUES('".addslashes($quote[quote])."','".$quote[prof]."','".$quote[course]."',
					'".$quote[term]."','".$quote[year]."')";
		
		if(!$this->myQuery($query))
			$this->errorla("Could not insert quote");
			
		return true;
	}
	
	// Return a single quote in associative array
	function getQuote($id) {
		$query = "SELECT * FROM ".$this->table." WHERE id=$id LIMIT 1";
		$result = mysql_fetch_assoc($this->myQuery($query));
		
		if(!$result)
			return false;
		
		return $result;
	}
	
	// Return some quotes based on search criteria (array)
	// prof, sort, rand, exact, limit
	function getQuotes($search) {
		// Set some defaults unless they're in $search
		$search['sort'] = ($search['sort']) ? $search['sort'] : "term";
		$search['rand'] = ($search['rand']) ? true : false;
		$search['limit'] = (intval($search['limit'])) ? $search['limit'] : 0;

		// Build query
		$query = "SELECT * FROM ".$this->table;		

		if($search['prof'] == "")
			return false;
		else if($search['exact'])
			$query .= " WHERE prof='".$search['prof']."'";
		else if($search['prof'] != "" && !$search['exact'])
			$query .= " WHERE prof LIKE '%".$search['prof']."%'";
		
		$query .= " ORDER BY ".$search['sort']." ASC";

		if($search['limit'])
			$query .= " LIMIT ".$search['limit'];

		// get quotes that match something in the array
		// keys: prof, course, term, year, score
		if($query) {
			$result = $this->myQuery($query);
			
			while($row = mysql_fetch_assoc($result))
				$quotes[] = $row;
			
			if($search['rand'] && is_array($quotes))
				shuffle($quotes);
				
			return $quotes;
		}
		return false;
	}
	
	// For displaying random quotes
	function randomQuote() {
		$rand = rand(1,$this->getCount());
		$query = "SELECT * FROM ".$this->table." WHERE id=".$rand;
		$result = $this->myQuery($query);
		if($result)
			return mysql_fetch_assoc($result);
		return false;
	}
	
	// How many quotes in the database?
	function getCount($group=0) {
		// $group could be a string with multiple groupings
		// ie $group="term,year"
		if($group) 
			$query = "SELECT COUNT(quote), ".$group." GROUP BY ".$group;
		else
			$query = "SELECT * FROM ".$this->table;
			
		$result = $this->myQuery($query);
		if($result)
			$rows = mysql_num_rows($result);
		return ($rows) ? $rows : 0;
	}
	
	// Remove a quote from the DB
	function removeQuote($id) {
		$query = "DELETE FROM ".$this->table." WHERE id=$id LIMIT 1";
		return $this->myQuery($query);
	}
	
	// Update a quote
	function updateQuote($quote) {
		// Get id, then rest of the keys
		$id = array_shift($quote);
		$keys = array_keys($quote);
		
		$query = "UPDATE ".$this->table." SET ";
		foreach($keys as $key)
			$query .= $key."=\"".$quote[$key]."\",";
		// Remove last comma
		$query = rtrim($query,",");
		$query .= " WHERE id=$id";
		return $this->myQuery($query);
	}
}
?>
