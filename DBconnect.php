

<?php
$servername = "localhost";
$username = "root";
$password = "";
$databasename = "u596909339_PHP_DB";

// Create connection
session_start();
$conn = @mysqli_connect($servername, $username, $password, $databasename);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

?>