<?php
require 'component/opendb.php';
session_start();

$managerID = 3; // ← غيّرها حسب الموجود بالجدول

$sql = "SELECT id_m, salary FROM manager WHERE id_m = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$managerID]);

$manager = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$manager) {
    die("❌ Manager not found in database");
}

echo "Manager salary: " . $manager['salary'];
