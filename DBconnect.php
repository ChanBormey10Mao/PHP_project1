<?php
define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'u596909339_MSG');
define('DB_PASSWORD', 'PHPmsg#12345');
define('DB_NAME', 'u596909339_PHP_DB');

/* Attempt to connect to MySQL database */
$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);
 
// Check connection
if($link === false){
    die("ERROR: Could not connect. " . mysqli_connect_error());
}
?>