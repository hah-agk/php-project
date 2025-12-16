<?php
session_start();
require 'component/opendb.php';

$managerID = $_SESSION['managerID'];
$stmt = $pdo->prepare("SELECT salary FROM manager WHERE id_m = ?");
$stmt->execute([$managerID]);
$salary = $stmt->fetchColumn();


$theme = $_SESSION['theme'] ?? 'light';
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= $theme ?>">
<head>
<meta charset="UTF-8">
<title>Wallet</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link href="css/balnce.css" rel="stylesheet">

</head>
<body>

<div class="page">

    <div class="title">
        <h2>
        <i class="fa-solid fa-wallet"></i>wallet</h2>
         </div>
    <div class="subtitle">Add or withdraw money from your account</div>

    <!-- BALANCE -->
    <div class="balance-box">
        <div class="balance-label">Current Balance</div>
        <div class="balance-value">$<?= number_format($salary, 2) ?></div>
    </div>

    <!-- TABS -->
    <div class="tabs">
        <div class="tab-btn active" onclick="showTab(1)">Add Money</div>
        <div class="tab-btn" onclick="showTab(2)">Withdraw</div>
    </div>
<!-- ADD MONEY PANEL -->
<div class="panel active" id="panel1">
    <form action="balance_action.php" method="post">
        <div class="buttons">
            <button type="submit" name="amount" value="5" class="action-btn">+$5</button>
            <button type="submit" name="amount" value="10" class="action-btn">+$10</button>
            <button type="submit" name="amount" value="20" class="action-btn">+$20</button>
            <button type="submit" name="amount" value="50" class="action-btn">+$50</button>
            <button type="submit" name="amount" value="100" class="action-btn">+$100</button>
        </div>
    </form>
</div>

<!-- WITHDRAW PANEL -->
<div class="panel" id="panel2">
    <form action="balance_action.php" method="post">
        <div class="buttons">
            <button type="submit" name="amount" value="-5" class="action-btn">-$5</button>
            <button type="submit" name="amount" value="-10" class="action-btn">-$10</button>
            <button type="submit" name="amount" value="-20" class="action-btn">-$20</button>
            <button type="submit" name="amount" value="-50" class="action-btn">-$50</button>
            <button type="submit" name="amount" value="-100" class="action-btn">-$100</button>
        </div>
    </form>
</div>



    <a href="manager.php" class="back-btn">← Back to Dashboard</a>
</div>

<script>
function showTab(tab) {
    document.querySelectorAll(".tab-btn").forEach(btn => btn.classList.remove("active"));
    document.querySelectorAll(".panel").forEach(panel => panel.classList.remove("active"));

    document.querySelector(`.tab-btn:nth-child(${tab})`).classList.add("active");
    document.getElementById(`panel${tab}`).classList.add("active");
}
</script>

</body>
</html>
