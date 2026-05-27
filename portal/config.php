<?php
$servername = getenv('DB_HOST') ?: 'localhost';
$username = getenv('DB_USER') ?: 'root';
$password = getenv('DB_PASS') ?: '';
$dbname = getenv('DB_NAME') ?: 'firsthon_lhp';

// Create connection
$conn = mysqli_connect($servername, $username, $password, $dbname);
// Check connection
?>