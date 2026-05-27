<?php
session_start();
$host = getenv('DB_HOST') ?: 'localhost'; /* Host name */
$user = getenv('DB_USER') ?: 'root'; /* User */
$password = getenv('DB_PASS') ?: ''; /* Password */
$dbname = getenv('DB_NAME') ?: 'firsthon_lhp'; /* Database name */

$con = mysqli_connect($host, $user, $password,$dbname);
// Check connection
if (!$con) {
 die("Connection failed: " . mysqli_connect_error());
}