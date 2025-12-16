<?php
session_start();
require '../component/opendb.php';
$theme = $_SESSION['theme'] ?? 'light';

if (!isset($_SESSION['userID'])) {
    header("Location: signup.php");
    exit();
}

$userID = $_SESSION['userID'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: show_task.php");
    exit();
}
// check of task_id is valid integer
$taskID = intval($_POST['task_id'] ?? 0);
if ($taskID <= 0) {
    header("Location: show_task.php?error=invalid_task");
    exit();
}

// accept the task
$sql = "
UPDATE task
SET user_id = ?, status = 'in_progress'
WHERE id_T = ? AND user_id IS NULL AND status = 'pending'
";

$stmt = $pdo->prepare($sql);
$stmt->execute([$userID, $taskID]);

// check if the update was successful
if ($stmt->rowCount() === 1) {
    // update successful
    header("Location: show_task.php?task=accepted");
    exit();
} else {
    // update failed (task not available)
    header("Location: show_task.php?error=not_available");
    exit();
}
