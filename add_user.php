<?php
require 'component/opendb.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $name =  htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = trim($_POST['password']);
    $phone = trim($_POST['phone']);
    $address = htmlspecialchars(trim($_POST['address']));

    if (!isset($name) || empty($name)
        || !isset($email) || empty($email)
        || !isset($password) || empty($password)
        || !isset($phone) || empty($phone)
        || !isset($address) || empty($address)
    ) {
        header("location: add_user.php?error=1");
        exit();
    }

     // neshuf eza email mawgud
    $sql = "SELECT id_u FROM users WHERE email = :email";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    if ($stmt->fetch()) {
        header("location: add_user.php?error=2");
        exit();
    }

    // Hash  password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$salary = 0;
    // nezid user jdid 3 database
    $sql = "INSERT INTO users (FullName,phone,address,salary, email, password) 
            VALUES (:name, :phone, :address, :salary, :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':salary', $salary);
    $stmt->bindParam(':password', $hashedPassword);
    $stmt->bindParam(':phone', $phone);
    $stmt->bindParam(':address', $address);
    
    $stmt->execute();

    header("Location: manager.php");
    exit();
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>add_user</title>
</head>
<body>
    <h2>Add New User</h2>
    <form action="add_user.php" method="POST">
        <label for="name">Name:</label>
        <input type="text" id="name" name="name" required><br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required><br><br>

        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required><br><br>

        <label for="phone">Phone:</label>
        <input type="text" id="phone" name="phone" required><br><br>

        <label for="address">Address:</label>
        <input type="text" id="address" name="address" required><br><br>

        <input type="submit" value="Add User">
    </form>
    <?php
    if (isset($_GET['error'])) {
        $error = $_GET['error'];
        switch ($error) {
            case 1:
                echo "<p style='color:red;'>All fields are required.</p>";
                break;
            case 2:
                echo "<p style='color:red;'>Email already exists.</p>";
                break;
            default:
                echo "<p style='color:red;'>An unknown error occurred.</p>";
        }
    }
    ?>
</body>
</html>