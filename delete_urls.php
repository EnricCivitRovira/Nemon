<?php

	$mysqli = new mysqli("localhost", "root", "", "nemon");

	if ($mysqli->connect_errno) {
		echo "Falló la conexión a MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
	}

	$query = "CALL nemon.deleteUrls()";

	if (!$mysqli->query($query)) {
			echo $mysqli->errno;
	}

?>