<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['button'])) {
        $name = $_POST['username'];
        $password = $_POST['password'];
        if ($name == "admin" && $password == "admin123") {
            // set session so other pages recognize the logged in user
            $_SESSION['user'] = $name;
            $_SESSION['role'] = 'admin';
            header("Location: ../Html/mapage.php");
            exit;
        } else {
            // failed login - redirect back (could show message)
            header("Location: ../Html/index.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/index1.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
</head>
<body>
    <div class="container">
        <div class="form-box login">
            <form action="../html/index.php" method="post">
                <h1>Login</h1>
                <div class="input-box">
                    <input type="text" placeholder="username" name="username" required>
                    <i class="bx bxs-user" id="aa" ></i>
                </div>
                <div class="input-box">
                    <input type="password" placeholder="password" name="password" required>
                    <i class="bx bxs-lock-alt" id="aa"></i>
                </div>
                <div class="forget-link">
                    <a href="#">Forgot password?</a>
                </div>
                <button type="submit" class="btn" name="button">Login</button>
                <p>or login with social platforms</p>
                <div class="social-icons">
                    <a href="https://www.google.com/"><i class="bx bxl-google" ></i></a>
                    <a href="https://www.facebook.com/"><i class="bx bxl-facebook" ></i></a>
                    <a href="https://github.com/"><i class="bx bxl-github" ></i></a>
                    <a href="https://www.linkedin.com/"><i class="bx bxl-linkedin" ></i></a>
                </div>
            </form>

        </div>
    </div>
</body>
</html>
