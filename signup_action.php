<?php
session_start();
require 'component/opendb.php';

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
    header("location: signup.php?errr=1");
    exit();
}
$_SESSION['email']=$email;
$_SESSION['name']=$name;
$_SESSION['phone']=$phone;
$_SESSION['address']=$address;

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("location: signup.php?errr=2");
    exit();
}

if (strlen($password)<8) {
    header("location: signup.php?errr=3");
    exit();
}

try {
    if(isset($_POST['user_type']) && $_POST['user_type'] === 'user'){

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (name, phone, addres, email, password)
<<<<<<< HEAD
            VALUES (:name, :phone, :address, :email, :password)";
=======
            VALUES (:name, :phone, :addres, :email, :password)";
>>>>>>> deef82ad22a5da893c9a0bdf8457b3bed7ca7a92
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":phone", $phone);
    $stmt->bindParam(":address", $address);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $hashed_password);
    $stmt->execute();

    $_SESSION['UorM']= "users";
    $_SESSION['LoggedIn']= true;
    $_SESSION['userID']= $pdo->lastInsertId();
    $_SESSION['userName']= $name;
    
    header("Location: user.php");
    exit();
    }
    else{
         $hashed_password = password_hash($password, PASSWORD_BCRYPT);

<<<<<<< HEAD
    $sql = "INSERT INTO manager (name, phone, addres, email, password)
            VALUES (:name, :phone, :address, :email, :password)";
=======
    $sql = "INSERT INTO manager (name, phone, addres , email, password)
            VALUES (:name, :phone, :addres, :email, :password)";
>>>>>>> deef82ad22a5da893c9a0bdf8457b3bed7ca7a92
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":phone", $phone);
    $stmt->bindParam(":addres", $address);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $hashed_password);
    $stmt->execute();
    $_SESSION['UorM']= "manager";
    $_SESSION['LoggedIn']= true;
    $_SESSION['userID']= $pdo->lastInsertId();
    $_SESSION['userName']= $name;
    header("Location: manager.php");
    exit();
    }
} catch (PDOException $e) {
    header("location: signup.php?errr=4");

//   echo "Error: " . $e->getMessage();
    exit();
}

// if (isset($_GET['errr'])) {
//     switch ($_GET['errr']) {
//         case 1:
//             echo "Missing Parameters";
//             break;
//         case 2:
//             echo "Invalid Email Format";
//             break;
//         case 3:
//             echo "Password must be at least 8 characters long";
//             break;
//         case 4:
//             echo "talk to admin";
//             break;
//         case 5:
//             echo "Email already exists";    
//             break;
//     }
// }
