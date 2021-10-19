

<?php
$servername = "localhost";
$username = "u596909339_MSG";
$password = "PHPmsg#12345";
$databasename = "u596909339_PHP_DB";

// Create connection
session_start();
$conn = @mysqli_connect($servername, $username, $password, $databasename);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>