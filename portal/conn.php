   <?php
$db_host = getenv('DB_HOST') ?: 'localhost';
$db_user = getenv('DB_USER') ?: 'root';
$db_pass = getenv('DB_PASS') ?: '';
$db_name = getenv('DB_NAME') ?: 'firsthon_lhp';
try
{
$conn=new PDO("mysql:host={$db_host};dbname={$db_name}", $db_user, $db_pass);
} catch (PDOException $ex) {
echo 'Exception'.$ex->getMessage();
}