<?php
session_start();
require '../component/opendb.php';

if (!isset($_SESSION['userID'])) {
    die("Unauthorized");
}

$userID = (int)$_SESSION['userID'];
$taskID = (int)($_POST['task_id'] ?? 0);

if ($taskID === 0) {
    header("Location: my_task.php");
    exit();
}

$stmt = $pdo->prepare("
    UPDATE task
    SET status = 'waiting_review'
    WHERE id_T = ?
      AND user_id = ?
      AND status = 'in_progress'
");
$stmt->execute([$taskID, $userID]);

header("Location: my_task.php?task_id=".$taskID);
exit();
