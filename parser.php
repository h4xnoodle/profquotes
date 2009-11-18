<?php 
session_start();

$i = 0; // table stripeys
$pass = "7e16da8707112e18296fe5e1ceebf992";

require('classes.php');
$Q = new Quotes();

include('header.php');
include('parserfuncs.php');

?>

<form method="post" action="parser.php">

<?php
echo ($_SESSION['loggedin']) ? "<p>LOGGED IN <input type='submit' name='logout' value='logout' /></p>" : "<p>You haz no permissionz</p>";
if($_SESSION['loggedin']){
?>
	<a href="parser.php">Start Fresh</a> <a href="fix.php">Update quotes</a><br /><br />
	<select name="term">
		<?php defTerm(); ?>
	</select>
	<input type="text" name="year" <?php echo ($_POST['year']) ? "value=\"".$_POST['year']."\"" : "value=\"".date('Y')."\""; ?> size="5">
	<input type="checkbox" name="style" value="new" <?php echo ($_POST['style']) ? "checked" : ""; ?>/> Old style? <br />
	<textarea name="quotes" style="width:95%;height:300px;"><?php echo stripslashes($_POST['quotes']); ?></textarea><br />
	<input type="reset" value="Reset to last parse" /> <input type="submit" name="submit" value="Parse Input + Preview" />

<?php
	if($_POST['submit']){
		$quotes = stripslashes($_POST['quotes']);
		
		if($quotes == "") {
			echo "You didn't input anything!";
			exit;
		}
		
		echo "<input type='submit' style='background-color:red;color:white;' name='verify' value='Verify all ouput is well-formed and submit'>";

		echo "<table><tr><th>Quote</th><th>Prof</th><th>Course</th><th>Term/Year</th></tr>\n";
		parseQuotes($quotes);
		echo "</table>\n";
		
		unset($_POST['submit']);
	}
	else if($_POST['verify']) {
		$quotes = $_POST['quotes'];
		parseQuotes($quotes,1);
	}
	else if($_POST['logout'])
		unset($_SESSION['loggedin']);
}
else {
?>
	<p>Sorry, but I didn't think it would be a great idea if anyone could pop quotes into the database. Clearly other things could get in there... Log in below if I've given you access. kthxbai</p>
	<p>Password? <input type="password" name="password" /> <input type="submit" name="login" value="GIMME ACCESS" /></p>
<?php
	// This is probably crappy but w/e
	if($_POST['login']) {
		if(md5($_POST['password']) == $pass)
			$_SESSION['loggedin'] = true;
		else
			echo "<p>Log in failed. You suck</p>";
	}
	else if($_SERVER['QUERY_STRING'] != "") {
		$fake = preg_replace("/[\w\d]+=([\w\d]+)/", "\${1}", $_SERVER['QUERY_STRING']);
		echo "<p>Do you really think I would use GET with a password? I'm hurt :(</p><p>And no, the password is not '".$fake."'.";
	}
}
echo "</form>";

$Q->disconnect();
include('footer.php');
?>
