<?php

	date_default_timezone_set("ASIA/Tehran");

	$host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'altcoin-trader';
    
    $mysqli = new mysqli($host, $user, $password, $database);

    if (mysqli_connect_errno()) {
        exit('Connect failed: '. mysqli_connect_error());
    }

?>