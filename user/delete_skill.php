<?php
session_start();
require '../component/opendb.php';

if (!isset($_SESSION['userID'])) {
    header("Location: ../signup.php");
    exit();
}

$userID  = (int)$_SESSION['userID'];
$skillID = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($skillID > 0) {
    $stmt = $pdo->prepare("
        DELETE FROM skills 
        WHERE id_S = ? AND user_id = ?
    ");
    $stmt->execute([$skillID, $userID]);
}

header("Location: show_skill.php");
exit();
?>