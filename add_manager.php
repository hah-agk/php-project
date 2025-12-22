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



/* FORM SUBMIT */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $phone    = trim($_POST['phone'] ?? '');
    $address  = trim($_POST['address'] ?? '');
    $salary   = 0;

    if (
        empty($name) ||
        empty($email) ||
        empty($password) ||
        empty($phone) ||
        empty($address)
    ) {
        header("Location: add_manager.php?error=1");
        exit();
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: add_manager.php?error=2");
        exit();
    }


    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    try {
        $sql = "INSERT INTO manager
                (name, phone, address, salary, email, password)
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
        header("Location: add_manager.php?error=4");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= $theme ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Manager - Admin Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/add_task.css">
</head>
<body>

<div class="main-content">

    <!-- Back Button -->
    <a href="admin.php" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Dashboard
    </a>

    <!-- Page Header -->
    <div class="page-header">
        <h1><i class="fa-solid fa-user-tie"></i> Create New Manager</h1>
        <p>Fill in the details below to create a new manager</p>
    </div>

    <!-- Form Container -->
    <div class="form-container">
        <h2>
            <i class="fas fa-id-card"></i> Manager Details
        </h2>

        <form action="add_manager.php" method="POST">

            <!-- Name -->
            <div class="form-group">
                <label>
                    <i class="fa-solid fa-user"></i> Manager Name
                </label>
                <input type="text"
                       name="name"
                       class="form-control"
                       placeholder="Enter manager name"
                       required>
            </div>

            <!-- Email -->
            <div class="form-group">
                <label>
                    <i class="fa-solid fa-envelope"></i> Email
                </label>
                <input type="email"
                       name="email"
                       class="form-control"
                       placeholder="Enter email"
                       required>
            </div>

            <!-- Password -->
            <div class="form-group">
                <label>
                    <i class="fa-solid fa-lock"></i> Password
                </label>
                <input type="password"
                       name="password"
                       class="form-control"
                       placeholder="Enter password"
                       required>
            </div>

            <!-- Phone -->
            <div class="form-group">
                <label>
                    <i class="fa-solid fa-phone"></i> Phone Number
                </label>
                <input type="number"
                       name="phone"
                       class="form-control"
                       placeholder="Enter phone number"
                       required>
            </div>

            <!-- Address -->
            <div class="form-group">
                <label>
                    <i class="fa-solid fa-location-dot"></i> Address
                </label>
                <input type="text"
                       name="address"
                       class="form-control"
                       placeholder="Enter address"
                       required>
            </div>

            <!-- Submit -->
            <button class="btn-submit" type="submit">
                <i class="fas fa-check-circle"></i> Create Manager
            </button>

        </form>

        <!-- Error Messages -->
        <?php if (isset($_GET['error'])): ?>
            <div class="error-msg">
                <i class="fas fa-exclamation-circle"></i>
                <?php
                    echo match($_GET['error']) {
                        '1' => 'Please fill in all required fields.',
                        '2' => 'Invalid email format.',
                        '4' => 'Email already exists.',
                        default => 'Something went wrong.'
                    };
                ?>
            </div>
        <?php endif; ?>

    </div>
</div>

</body>
</html>
