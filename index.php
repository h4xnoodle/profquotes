<?php

require_once('classes.php');

$Q = new Quotes();
include('header.php');

$quotes = 0;

if($_POST['submit']) {
	$search = $_POST['search'];	
	if(!$search['prof'] == "") {
		echo "<p class='searchresult'>You searched for: <b>".$search['prof']."</b> ";
		echo ($search['exact']) ? "(exact) " : "(partial) ";
		if(!$search['random'])
			echo "and sorted by <b>".$search['sort']."</b>.</p>\n\n";
		else
			echo "and chose to be <b>rAnDoM</b></p>\n\n";
	}
	else 
		echo "<p>You searched for nothing! Try again, noob</p>\n\n";

	$quotes = $Q->getQuotes($search);
}
else {
?>
<p>Scoring not implemented yet, but coming soon! Searching by prof only for now... Searching by quote would mean a lot of long-string searching which is pricey bizness</p>
<p>Todo: scoring, display options.</p>
<div class="search">
<form method="post" action="index.php">
	<h1>Search Filters</h1>
	<p>Type 'all' in the search box to get all quotes. Don't rape the server plz</p>
	<label for="search[prof]">Search by prof</label><input type="text" name="search[prof]" value="<?php echo ($search['prof']) ? $search['prof'] : ""; ?>" style="width:300px;" />
	<input type="checkbox" name="search[exact]" /> Exact match?<br />
	<label for="search[sort]">Sort by</label><select name="search[sort]"><?php echo ($search['sort']) ? "<option>".$search['sort']."</option>" : ""; ?><option>term</option><option>year</option><option>prof</option><option>score</option></select> (Ascending)<br />
	<label for="search[rand]">Randomize!</label><input type="checkbox" name="search[rand]" /> <br /><br />
	<label for="search[limit]">Limit</label><input type="text" name="search[limit]" value="<?php echo $search['limit']; ?>" /><br />
	<input type="submit" name="submit" value="Search" />
</form>
</div>
<?php } ?>
<div class="output">
<?php
if($quotes) {
	foreach($quotes as $quote) {
		echo "<p class='quote'>\"".nl2br($quote['quote'])."\"<br /><br /><p class='prof'>".$quote['prof'].", ".$quote['course']." (".$quote['term']." ".$quote['year'].")</p></p><br />\n";
	}
	unset($quotes);
}
else if($_POST['submit'] && !$quotes)
	echo "<p>No results for that search!</p>";
else {
	// Random quote!
	echo "<h2>A random quote...</h2>";
	$quote = $Q->randomQuote();
	if($quote)
		echo "<p class='quote'>\"".nl2br($quote['quote'])."\"<br /><br /><p class='prof'>".$quote['prof'].", ".$quote['course']." (".$quote['term']." ".$quote['year'].")</p></p>";
	else
		echo "<p>No quote for you!</p>";
}
?>
</div>
<?php
$Q->disconnect();
include('footer.php');
?>
