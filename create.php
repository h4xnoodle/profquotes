<?php

$query = "CREATE TABLE IF NOT EXISTS quotes(
	id int(10) NOT NULL AUTO_INCREMENT,
	quote text,
	prof varchar(50) DEFAULT NULL,
	course varchar(15) DEFAULT NULL,
	term varchar(10) DEFAULT NULL,
	year smallint(4) unsigned DEFAULT NULL,
	score tinyint(2) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY(id),
	KEY score (score),
	KEY prof(prof),
	KEY term(term)) ENGINE=MyISAM";
mysql_connect('localhost','rjputins','CLmqx3ovlrRq6');
mysql_select_db('profquotes');
if(mysql_query($query))
	echo "yay";
else
	echo "aw... ".mysql_error();
?>

