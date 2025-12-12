<?php
session_start();
require 'component/opendb.php';

if (!isset($_SESSION['managerID'])) {
    die("Not authorized.");
}

$managerID = (int) $_SESSION['managerID'];
$amount    = (float) ($_POST['amount'] ?? 0);

// If no amount sent
if ($amount == 0) {
    header("Location: balance.php");
    exit();
}

try {
    // Start transaction
    $pdo->beginTransaction();

    // Get current balance
    $stmt = $pdo->prepare("SELECT salary FROM manager WHERE id_m = ?");
    $stmt->execute([$managerID]);
    $currentBalance = (float) $stmt->fetchColumn();

    // Prevent negative balance
    if ($currentBalance + $amount < 0) {
        $pdo->rollBack();
        header("Location: balance.php?error=insufficient_funds");
        exit();
    }

    // Update balance
    $stmt = $pdo->prepare(
        "UPDATE manager 
         SET salary = salary + ? 
         WHERE id_m = ?"
    );
    $stmt->execute([$amount, $managerID]);

    // Commit transaction
    $pdo->commit();

    header("Location: balance.php?success=1");
    exit();

} catch (Exception $e) {
    $pdo->rollBack();
    die("Something went wrong.");
}
