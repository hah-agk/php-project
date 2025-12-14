<?php
session_start();
require 'component/opendb.php';

$managerID = $_SESSION['managerID'];
$stmt = $pdo->prepare("SELECT salary FROM manager WHERE id_m = ?");
$stmt->execute([$managerID]);
$salary = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Wallet</title>

<style>
/* ---------------- GLOBAL ---------------- */
body{
    margin:0;
    padding:0;
    font-family:'Inter',sans-serif;
    background:#14161B;
    color:#E5E7EB;
    display:flex;
    justify-content:center;
    align-items:flex-start;
    min-height:100vh;
}

.page{
    width:100%;
    max-width:750px;
    padding:50px 20px;
}

/* ---------------- TITLE ---------------- */
.title{
    font-size:32px;
    font-weight:700;
    margin-bottom:6px;
}
.subtitle{
    font-size:14px;
    color:#9CA3AF;
    margin-bottom:35px;
}

/* ---------------- BALANCE BOX ---------------- */
.balance-box{
    background:#1B1E24;
    padding:25px;
    border-radius:16px;
    border:1px solid #2C2F36;
    margin-bottom:40px;
    text-align:center;
}
.balance-label{
    font-size:14px;
    color:#9CA3AF;
}
.balance-value{
    font-size:42px;
    font-weight:700;
    margin-top:5px;
    color:#7FFFE2;
}

/* ---------------- TABS ---------------- */
.tabs{
    display:flex;
    justify-content:center;
    margin-bottom:25px;
}
.tab-btn{
    padding:12px 26px;
    margin:0 8px;
    border-radius:10px;
    cursor:pointer;
    border:1px solid #2C2F36;
    background:#1F2329;
    color:#D1D5DB;
    transition:0.25s;
}
.tab-btn.active{
    background:#273038;
    color:#7FFFE2;
    border-color:#7FFFE2;
}

/* ---------------- PANELS ---------------- */
.panel{
    display:none;
}
.panel.active{
    display:block;
    animation:fade 0.35s ease;
}

@keyframes fade{
    from{opacity:0; transform:translateY(6px);}
    to{opacity:1; transform:translateY(0);}
}

/* ---------------- BUTTONS ---------------- */
.buttons{
    display:flex;
    gap:15px;
    flex-wrap:wrap;
    justify-content:center;
}

.action-btn{
    padding:15px 26px;
    font-size:18px;
    border-radius:12px;
    border:1px solid #2A2F36;
    background:#1F2329;
    color:#D1D5DB;
    cursor:pointer;
    transition:0.25s;
}
.action-btn:hover{
    background:#273038;
    color:#7FFFE2;
    transform:translateY(-3px);
}

/* ---------------- BACK BUTTON ---------------- */
.back-btn{
    margin-top:45px;
    display:inline-block;
    padding:14px 32px;
    font-size:17px;
    border-radius:12px;
    background:linear-gradient(90deg,#4DD7E6,#7B88FF);
    text-decoration:none;
    color:white;
    transition:0.25s;
}
.back-btn:hover{
    transform:translateY(-2px);
}
</style>

</head>
<body>

<div class="page">

    <div class="title">Wallet</div>
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
function showTab(tab){
    document.querySelectorAll(".tab-btn").forEach(btn=>btn.classList.remove("active"));
    document.querySelectorAll(".panel").forEach(p=>p.classList.remove("active"));
    document.querySelector(`.tab-btn:nth-child(${tab})`).classList.add("active");
    document.getElementById(`panel${tab}`).classList.add("active");
}
</script>

</body>
</html>
