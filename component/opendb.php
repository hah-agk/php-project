
<?php


$server = "localhost";
$username = "root";
$password = "";
$db="task_management_db";

//PDO
try {
    $pdo= new PDO("mysql: host=$server;dbname=$db", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $exception) {
    echo "Connection to database faild! Error : ". $exception->getMessage();
    die();
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
