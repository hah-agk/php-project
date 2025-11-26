<?php
session_start();
require  "components/open_db.php";

if ($_SERVER['REQUEST_METHOD'] != "POST") {
    die("Wrong Method");
}

$name = htmlspecialchars($_POST['name']);
$phone = htmlspecialchars($_POST['phone']); 
$address = htmlspecialchars($_POST['address']);
$email = htmlspecialchars($_POST['email']);
$password = $_POST['password'];

if (!isset($name) || empty(trim($name))
    || !isset($phone) || empty(trim($phone))
    || !isset($address) || empty(trim($address))
    || !isset($email) || empty(trim($email))
    || !isset($password) || empty(trim($password))
) {
    header("location: signup.php?err=1");
    exit();
}
try {
    if(isset($_POST['user_type']) && $_POST['user_type'] === 'user'){

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (name, phone, addres, email, password) 
            VALUES (:name, :phone, :address, :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":phone", $phone);
    $stmt->bindParam(":address", $address);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $hashed_password);
    $stmt->execute();
    header("Location: user.php");
    exit();
    }
    else{
         $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO manager (name, phone, addres, email, password) 
            VALUES (:name, :phone, :address, :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":phone", $phone);
    $stmt->bindParam(":address", $address);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $hashed_password);
    $stmt->execute();
    header("Location: manager.php");
    exit();
    }
} catch (PDOException $e) {
    header("location: signup.php?err=2");
    exit();
}

