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
$salary = 0;
 
    $sql = "INSERT INTO users (FullName, phone, address, salary ,  email, password)
            VALUES (:name, :phone, :address,:salary , :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":phone", $phone);
    $stmt->bindParam(":address", $address);
    $stmt->bindParam(":salary", $salary);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $hashed_password);
    $stmt->execute();

    $_SESSION['UorM']= "users";
    $_SESSION['LoggedIn']= true;
    $_SESSION['userID']= $pdo->lastInsertId();
    $_SESSION['userName']= $name;
    $signup = true;
    setcookie("signup", $signup,);
    header("Location: user.php");
    exit();
    }
    else{
     $hashed_password = password_hash($password, PASSWORD_BCRYPT);
     $salary = 0;
    // check if the email is already exists in the manager table
    $sql = "SELECT COUNT(*) FROM manager WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":email", $email);
    $stmt->execute();
    $count = $stmt->fetchColumn();
    if ($count > 0) {
        header("location: signup.php?errr=4");
        exit();
    } else {

    $sql = "INSERT INTO manager_requests (name, phone, address,email, password)
            VALUES (:name, :phone, :address , :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":name", $name);
    $stmt->bindParam(":phone", $phone);
    $stmt->bindParam(":address", $address);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":password", $hashed_password);
    $stmt->execute();

        $_SESSION['wait_email'] = $email;

        header("Location: waiting.php");
        exit();
    }
   
    }
} catch (PDOException $e) {
//    header("location: signup.php?errr=4");

   echo "Error: " . $e->getMessage();
    exit();
}

if (isset($_GET['errr'])) {
    switch ($_GET['errr']) {
        case 1:
            echo "Missing Parameters";
            break;
        case 2:
            echo "Invalid Email Format";
            break;
        case 3:
            echo "Password must be at least 8 characters long";
            break;
        case 4:
            echo "talk to admin";
            break;
        case 5:
            echo "Email already exists";    
            break;
    }
}
