
<?php
$server   = "sql7.freesqldatabase.com";
$username = "sql7811320";
$password = "VvgdRAgste";
$db       = "sql7811320";
$port     = 3306;

try {
    $dsn = "mysql:host=$server;port=$port;dbname=$db;charset=utf8mb4";
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}


 ?>



 <!-- eza fih meshkle b db
echo "<pre>";
echo "CHECKING USERS...\n";
var_dump($email);
var_dump($password);
var_dump($user);
echo "</pre>";
exit(); -->
