<?php
require 'component/opendb.php';
session_start();
$theme = $_SESSION['theme'] ?? 'light';

if (!isset($_SESSION['LoggedIn']) || $_SESSION['LoggedIn'] !== true) {
    header("Location: signup.php");
    exit;
}
if (!isset($_SESSION['UorM']) || $_SESSION['UorM'] !== 'admin') {
    header("Location: signup.php");
    exit;
}



if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = trim($_POST['fullname'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $phone    = trim($_POST['phone'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $salary   = 0; // default

    /* Validation */
    if (
        empty($name) ||
        empty($email) ||
        empty($password) ||
        empty($phone) ||
        empty($address)
    ) {
        header("Location: add_user.php?error=1");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: add_user.php?error=2");
        exit();
    }

    /* Hash password */
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        $sql = "INSERT INTO users 
                (FullName, phone, address, salary, email, password)
                VALUES 
                (:name, :phone, :address, :salary, :email, :password)";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':name'     => $name,
            ':phone'    => $phone,
            ':address'  => $address,
            ':salary'   => $salary,
            ':email'    => $email,
            ':password' => $hashedPassword
        ]);

        header("Location: admin.php");
        exit();

    } catch (PDOException $e) {
        header("Location: add_user.php?error=4");
        exit();
    }
}


$theme = $_SESSION['theme'] ?? 'light';
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= $theme ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Task - Manager Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/add_task.css">
</head>
<body>


    <!-- Main Content -->
    <div class="main-content">


       <!-- Back Button -->
        <a href="admin.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>Back to Dashboard
        </a>
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-tasks"></i> Create New user</h1>
            <p>Fill in the details below to create a new user for your team</p>
        </div>

      
        <!-- Form Container -->
        <div class="form-container">
            <h2>
                <i class="fas fa-clipboard-list"></i>user Details
            </h2>
<form action="add_user.php" method="POST">

    <div class="form-group">
        <label><i class="fa-solid fa-user"></i> Full Name</label>
        <input type="text" name="fullname" class="form-control" required>
    </div>

    <div class="form-group">
        <label><i class="fa-solid fa-envelope"></i> Email</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="form-group">
        <label><i class="fa-solid fa-lock"></i> Password</label>
        <input type="password" name="password" class="form-control" required>
    </div>

    <div class="form-group">
        <label><i class="fa-solid fa-phone"></i> Phone</label>
        <input type="number" name="phone" class="form-control" required>
    </div>

    <div class="form-group">
        <label><i class="fa-solid fa-location-dot"></i> Address</label>
        <input type="text" name="address" class="form-control" required>
    </div>

    <button type="submit" class="btn-submit">
        <i class="fas fa-check-circle"></i> Create User
    </button>
</form>


            <!-- Error Messages -->
            <?php
            if (isset($_GET['error']) && $_GET['error'] == 1) {
                echo "<div class='error-msg'>
                        <i class='fas fa-exclamation-circle'></i>
                        Please fill in all required fields. Bounty must be ≥ 0.
                      </div>";
            }
            if (isset($_GET['error']) && $_GET['error'] == 2) {
                echo "<div class='error-msg'>
                        <i class='fas fa-exclamation-circle'></i>
                        Bounty must be a non-negative value.
                      </div>";
            }
            ?>
        </div>
    </div>
</body>
</html>