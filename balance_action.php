<?php
session_start();
require 'component/opendb.php';

if (!isset($_SESSION['managerID'])) {
    die("Not authorized.");
}

$managerID = $_SESSION['managerID'];
$amount = floatval($_POST['amount'] ?? 0);


if ($amount == 0) {
    header("Location: balance.php");
    exit();
}

$stmt = $pdo->prepare("UPDATE manager SET salary = salary + ? WHERE id_m = ?");
$stmt->execute([$amount, $managerID]);

header("Location: balance.php");
exit();
