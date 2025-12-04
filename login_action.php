<?php
session_start();
require 'component/opendb.php';

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    die("Wrong Method");
}

$email=htmlspecialchars($_POST['email']);
$password = $_POST['password'];

if (!isset($email) || empty(trim($email))
||  !isset($password) || empty(trim($password)) ) {
    header("location: signup.php?err=1");
    exit();
}
$_SESSION['email']=$email;
try {
    $sql ="SELECT id ,name , password
           FROM manager 
           where email = :email ";
           $stmt =$pdo->prepare($sql);
           $stmt->bindParam(":email", $email);
           $stmt->execute();
           $manager = $stmt->fetch(PDO::FETCH_ASSOC);
                
    if (!$manager) {
         $sql ="SELECT id  , name , password
           FROM users 
           where email = :email ";
           $stmt =$pdo->prepare($sql);
           $stmt->bindParam(":email", $email);
           $stmt->execute();
           $user = $stmt->fetch(PDO::FETCH_ASSOC);
   if (!$user) {
        header("location:signup.php?err=2");
        exit();
    }
    if (!password_verify($password , $user['password'])) {
             header("location:signup.php?err=2");
        exit();
    }   
    $_SESSION['UorM']= "users";
    $_SESSION['LoggedIn']= true;
    $_SESSION['userID']= $user['id'];
    $_SESSION['userName']= $user['name'];
    header("Location:user.php");
    exit();
    }else{
     if (!password_verify($password , $user['password'])) {
             header("location:signup.php?err=2");
        exit();
        }   
    $_SESSION['UorM']= "manager";
    $_SESSION['LoggedIn']= true;
    $_SESSION['userID']= $user['id'];
    $_SESSION['userName']= $user['name'];
    header("Location:manager.php");
    exit();
    }
} catch (PDOException $ex) {
    header("location:signup.php?err=3");
}