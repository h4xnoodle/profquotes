<?php
session_start();

require('classes.php');
include('header.php');

$update = new Quotes();

if($_SESSION['loggedin']) { ?>

	<p>Fix troublesome quotes...</p>
	<form method="post" action="fix.php">
	Search for a bad quote by prof: <input type="text" name="search" value="<?php echo($_POST['search']) ? $_POST['search'] : ""; ?>" />
	<br />Term <select name="term"><option>Spring</option><option>Fall</option><option>Winter</option></select> 
	<input type="text" name="syear" value="<?php echo ($_POST['syear']) ? $_POST['syear'] : ""; ?>" />
	<br /><input type="submit" name="submit" value="Search" />

<?php if($_POST['submit']) {
		// Need to search by term/year too
		// Need new search/better. search should accept "stuff?stuff?" or something
		$quotes = $update->getQuotes($_POST['search']);
		echo "<table><tr><th>Quote</th><th>Prof</th><th>Course</th><th>Term/Year</th></tr>\n";
		foreach($quotes as $quote) {
			echo "<tr><td><input type='text' name='quote[]' value=\"".$quote['quote']."\" /></td>";
			echo "<td><input type='text' name='prof[]' value='".$quote['prof']."' /></td>";
			echo "<td><input type='text' name='course[]' value='".$quote['course']."' /></td>";
			echo "<td><select name='term[]'><option>".$quote['term']."</option><option>Winter</option><option>Spring</option><option>Fall</option></select> ";
			echo "<input type='text' name='year[]' value='".$quote['year']."' /></td></tr>";
		}
		echo "</table>";
		echo "<input type='submit' name='update' value='Update Quotes' />";
	}
	else if($_POST['update']) {
		echo "<pre>";
		print_r($_POST);
		echo "</pre>";
		foreach($_POST['quote'] as $quote) {
			echo "<p>".$quote." ".$prof." ".$course." ".$term;
		}
	}
		
$update->disconnect();
} else {
?>
	<p>You need the password to enter this area. <a href="parser.php">Try here</a></p>

<?php
}

include('footer.php');
?>
