<?php

// To store current search
session_start();

require_once('classes.php');

$Q = new Quotes();
include('header.php');

$quotes = 0;

if($_POST['submit']){ 
	$search = $_POST['search'];
	$_SESSION['search'] = $search;
	
	if(!$search['prof'] == "") {
		echo "<p class='searchresult'>You searched for: <b>".$search['prof']."</b> ";
		echo ($search['exact']) ? "(exact) " : "(partial) ";
		if(!$search['rand'])
			echo "and sorted by <b>".$search['sort']."</b>.";
		else
			echo "and chose to be <b>rAnDoM</b>!";
		if(intval($search['limit']))
			echo " Returning ".intval($search['limit'])." results.</p>\n\n";
		else
			echo " Returning all results.</p>\n\n";
	}
	else 
		echo "<p>You searched for nothing! Try again, noob</p>\n\n";

	$quotes = $Q->getQuotes($search);
}
else {
	$search = $_SESSION['search'];
?>
<p>Search for your favourite profs! If you encounter any issues, please contact <a href="mailto:rjputins@csclub.uwaterloo.ca">rjputins</a></p>
<div class="search">
<form method="post" action="index.php">
	<h1>Search Filters</h1>
	<p>Type 'all' in the search box to get all quotes.</p>
	<p><label>Search by prof</label><input type="text" name="search[prof]" value="<?php echo ($search['prof']) ? $search['prof'] : ""; ?>" style="width:300px;" />
	<input type="checkbox" name="search[exact]" /> Exact match?</p>
	<p><label>Sort by</label><select name="search[sort]"><?php echo ($search['sort']) ? "<option>".$search['sort']."</option>" : ""; ?><option>term</option><option>year</option><option>prof</option><option>score</option></select> (Ascending)</p>
	<p><label>Randomize!</label><input type="checkbox" name="search[rand]" /></p>
	<p><label>Limit</label><input type="text" name="search[limit]" value="<?php echo $search['limit']; ?>" style="width:40px;" /></p>
	<p><label>Rm Term/Year</label><input type="checkbox" name="search[ty]" /></p>
	<p><label> </label><input type="submit" name="submit" value="Search" /></p>
</form>
<br style="clear:both;" />
</div>
<?php } ?>
<div class="output">
<?php
if($quotes) {
	foreach($quotes as $quote) {
		echo "<p class='quote'>\"".nl2br($quote['quote'])."\"<br /><br /><p class='prof'>".$quote['prof'].", ".$quote['course'];
		if(!$search['ty'])
			echo " (".$quote['term']." ".$quote['year'].")";
		echo "</p></p><br />\n";
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
