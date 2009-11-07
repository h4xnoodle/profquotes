<?php

require_once('classes.php');

$Q = new Quotes();
include('header.php');

$quotes = 0;

if($_POST['submit']) {
	if($_POST['random']) $random = true;
	if($_POST['exact']) $exact = true;
	
	if(!$_POST['search_prof'] == "") {
		echo "<p class='searchresult'>You searched for: <b>".$_POST['search_prof']."</b> ";
		echo ($exact) ? "(exact) " : "(partial) ";
		if(!$random)
			echo "and sorted by <b>".$_POST['sort']."</b>.</p>\n\n";
		else
			echo "and chose to be <b>rAnDoM</b></p>\n\n";
	}
	else 
		echo "<p>You searched for nothing! Try again, noob</p>\n\n";
	
	// getQuotes(string<search>,string<sort>,bool<exact>,bool<random>)
	if($_POST['search_prof'] == "all")
		$quotes = $Q->getQuotes("all",$_POST['sort'],$exact,$random);
	else
		$quotes = $Q->getQuotes($_POST['search_prof'],$_POST['sort'],$exact,$random);
}
else {
?>
<p>Scoring not implemented yet, but coming soon! Searching by prof only for now... Searching by quote would mean a lot of long-string searching which is pricey bizness</p>
<p>Todo: scoring, display options.</p>
<div class="search">
<form method="post" action="index.php">
	<h1>Search Filters</h1>
	<p>Type 'all' in the search box to get all quotes. Don't rape the server plz</p>
	<label for="search_prof">Search by prof</label><input type="text" name="search_prof" value="<?php echo ($_POST['search_prof']) ? $_POST['search_prof'] : ""; ?>" style="width:300px;" />
	<input type="checkbox" name="exact" /> Exact match?<br />
	<label for="sort">Sort by</label><select name="sort"><?php echo ($_POST['sort']) ? "<option>".$_POST['sort']."</option>" : ""; ?><option>term</option><option>year</option><option>prof</option><option>score</option></select> (Ascending)<br />
	<label for="random">Randomize!</label><input type="checkbox" name="random" /><br />
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
