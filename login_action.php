<?php
session_start();
require_once 'component/opendb.php';

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    die("Wrong Method");
}

$email=htmlspecialchars($_POST['email']);
$password = $_POST['password'];

if (!isset($email) || empty(trim($email))
||  !isset($password) || empty(trim($password)) ) {
    header("location: login.php?err=1");
    exit();
}

try {
    $sql ="SELECT id ,name , password
           FROM manager 
           where email = :email ";
           $stmt =$pdo->prepare($sql);
           $stmt->blindParam(":email", $email);
           $stmt->execute();
           $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
    if (!user) {
         $sql ="SELECT id  , name , password
           FROM users 
           where email = :email ";
           $stmt =$pdo->prepare($sql);
           $stmt->blindParam(":email", $email);
           $stmt->execute();
           $user = $stmt->fetch(PDO::FETCH_ASSOC);
   if (!user) {
        header("location:login.php?err=2");
        exit();
    }
    if (!password_verify($password , $user['password'])) {
             header("location:login.php?err=2");
        exit();
    }   
    $_SESSION['LoggedIn']= true;
    $_SESSION['userID']= $user['id'];
    $_SESSION['userName']= $user['name'];
    header("Location:user.php");
    exit();
        }
     if (!password_verify($password , $user['password'])) {
             header("location:login.php?err=2");
        exit();
        }   
    $_SESSION['LoggedIn']= true;
    $_SESSION['userID']= $user['id'];
    $_SESSION['userName']= $user['name'];
    header("Location:manager.php");
    exit();

} catch (PDOException $ex) {
    header("location:login.php?err=3");
}