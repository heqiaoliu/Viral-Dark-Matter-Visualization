<?php
	$dbuser="nturner";
	$dbpass="LOB4steR";
	$dbname="viral_dark_matter";
	$dbhost = "localhost";

	$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

	if ($mysqli -> connect_errno) {
        die ("Failed to connect to MySQL: (".
                $mysqli->connect_errno. ") ".
                $mysqli->connect_error);
	}

?>

