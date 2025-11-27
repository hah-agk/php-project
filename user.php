<?php
session_start();

// USER ONLY ACCESS
if (!isset($_SESSION['LoggedIn']) || $_SESSION['LoggedIn'] !== true) {
    header("Location: signup.php?errr=7");
    exit();
}

if (!isset($_SESSION['UorM']) || $_SESSION['UorM'] !== "users") {
    header("Location: manager.php");
    exit();
}

// USER DATA
$userID   = $_SESSION['userID'];
$userName = $_SESSION['userName'];
$userMail = $_SESSION['email'];
$userPhone = $_SESSION['phone'];
$userAddress = $_SESSION['address'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f2f2f2;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 450px;
            margin: 50px auto;
            background: white;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
        }
        h2 {
            text-align: center;
            margin-bottom: 25px;
            color: #3b3b3b;
        }
        .info-box {
            padding: 12px;
            background: #f8f8f8;
            border-radius: 6px;
            margin-bottom: 12px;
            font-size: 15px;
        }
        .logout-btn {
            width: 100%;
            padding: 12px;
            border: none;
            background: #d9534f;
            color: white;
            font-size: 16px;
            border-radius: 7px;
            cursor: pointer;
            margin-top: 15px;
            transition: 0.2s;
        }
        .logout-btn:hover {
            background: #b52b27;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($userName); ?> 👋</h2>

    <div class="info-box"><b>ID:</b> <?php echo $userID; ?></div>
    <div class="info-box"><b>Name:</b> <?php echo htmlspecialchars($userName); ?></div>
    <div class="info-box"><b>Email:</b> <?php echo htmlspecialchars($userMail); ?></div>
    <div class="info-box"><b>Phone:</b> <?php echo htmlspecialchars($userPhone); ?></div>
    <div class="info-box"><b>Address:</b> <?php echo htmlspecialchars($userAddress); ?></div>

    <form action="logout.php" method="POST">
        <button class="logout-btn">Logout</button>
    </form>
</div>

</body>
</html>
