<?php
session_start();
require 'component/opendb.php';

if (!isset($_SESSION['wait_email'])) {
    header("Location: signup.php");
    exit();
}

$email = $_SESSION['wait_email'];

$stmt = $pdo->prepare("SELECT * FROM manager_requests WHERE email = ?");
$stmt->execute([$email]);
$request = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$request) {
    die("Request not found");
}

if ($request['status'] === 'approved') {

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM manager WHERE email = ?");
    $stmt->execute([$email]);
    $exists = $stmt->fetchColumn();

    if ($exists == 0) {

        $stmt = $pdo->prepare("
            INSERT INTO manager (name, phone, address, email, password, salary)
            VALUES (?, ?, ?, ?, ?, 0)
        ");
        

        $stmt->execute([
            $request['name'],
            $request['phone'],
            $request['address'],
            $request['email'],
            $request['password'],
        ]);
    }

    $stmt = $pdo->prepare("SELECT id_m FROM manager WHERE email = ?");
    $stmt->execute([$email]);
    $_SESSION['managerID'] = $stmt->fetchColumn();

    $_SESSION['UorM'] = "manager";
    $_SESSION['LoggedIn'] = true;
    $_SESSION['managerName'] = $request['name'];
    $_SESSION['managerEmail'] = $request['email'];
     $signup = true;
    setcookie("signup", $signup,);
    
    unset($_SESSION['wait_email']);

    header("Location: manager.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Waiting Approval</title>
<style>
body{
    background:#0f172a;
    color:#e5e7eb;
    font-family:Arial;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
}
.box{
    background:#111827;
    padding:40px;
    border-radius:12px;
    text-align:center;
    width:420px;
}
.loader{
    width:40px;
    height:40px;
    border:4px solid #374151;
    border-top:4px solid #38bdf8;
    border-radius:50%;
    margin:20px auto;
    animation:spin 1s linear infinite;
}
@keyframes spin{
    to{transform:rotate(360deg);}
}
</style>
</head>
<body>

<div class="box">
    <div class="loader"></div>
    <h2>Request Pending</h2>
    <p>Your manager request is under review.</p>
</div>

<script>
setTimeout(() => location.reload(), 5000);
</script>

</body>
</html>
