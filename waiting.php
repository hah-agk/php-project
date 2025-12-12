<?php
session_start();
require 'component/opendb.php';
$name = $_SESSION['name'];
$phone = $_SESSION['phone'];
$address = $_SESSION['address'];
$email = $_SESSION['email'];
$salary = 0;
$hashed_password = $_SESSION['hashed_password'];
if(isset($_SESSION['waitAdmin']) && $_SESSION['waitAdmin'] === 'true'){
try {
    $sql = "INSERT INTO manager (name, phone, address, salary ,  email, password)
            VALUES (:name, :phone, :address,:salary , :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":phone", $phone);
    $stmt->bindParam(":address", $address);
    $stmt->bindParam(":salary", $salary);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $hashed_password);
    $stmt->execute();

    $_SESSION['UorM']= "manager";
    $_SESSION['LoggedIn']= true;
    $_SESSION['managerID']= $pdo->lastInsertId();
    $_SESSION['managerName']= $name;
    $signup = true;
    setcookie("signup", $signup,);
    header("Location: manager.php");
    exit();
} catch (PDOException $e) {
    if ($e->getCode() == 23000) {
        header("location: signup.php?errr=4");
        exit();
    } else {
        die("Database error: " . $e->getMessage());
    }
}
}
else{
   echo 'waiting for admin approval';
}