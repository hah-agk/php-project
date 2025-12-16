<?php
session_start();
require 'component/opendb.php';

if (!isset($_SESSION['managerID'])) {
    header("Location: login.php");
    exit();
}

$managerID = (int)$_SESSION['managerID'];

if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['action'], $_POST['task_id'])) {

    $taskId = (int)$_POST['task_id'];
    $action = $_POST['action'];

    try {
        $pdo->beginTransaction();

        /* ---------- APPROVE ---------- */
        if ($action === 'approve') {

            // get task info
            $stmt = $pdo->prepare("
                SELECT bounty, user_id
                FROM task
                WHERE id_T = ?
                  AND manager_id = ?
                  AND status = 'waiting_review'
            ");
            $stmt->execute([$taskId, $managerID]);
            $task = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$task || !$task['user_id']) {
                throw new Exception("Invalid task");
            }

            $bounty = (float)$task['bounty'];
            $userId = (int)$task['user_id'];

            // pay user
            $stmt = $pdo->prepare("
                UPDATE users
                SET salary = salary + ?
                WHERE id_u = ?
            ");
            $stmt->execute([$bounty, $userId]);

            // mark completed
            $stmt = $pdo->prepare("
                UPDATE task
                SET status = 'completed'
                WHERE id_T = ? AND manager_id = ?
            ");
            $stmt->execute([$taskId, $managerID]);
        }

// reject
        else if ($action === 'reject') {

            $stmt = $pdo->prepare("
                UPDATE task
                SET status = 'in_progress'
                WHERE id_T = ?
                  AND manager_id = ?
                  AND status = 'waiting_review'
            ");
            $stmt->execute([$taskId, $managerID]);
        }

// INVALID ACTION
        else {
            throw new Exception("Invalid action");
        }

        $pdo->commit();
        header("Location: review_tasks.php");
        exit();

    } catch (Exception $e) {
        $pdo->rollBack();
        die("Action failed: " . $e->getMessage());
    }
}

// Fetch tasks waiting for review
$stmt = $pdo->prepare("
    SELECT
        t.id_T,
        t.Title_T,
        t.description,
        t.bounty,
        t.End_Time,
        u.FullName AS user_name
    FROM task t
    LEFT JOIN users u ON t.user_id = u.id_u
    WHERE t.manager_id = ?
      AND t.status = 'waiting_review'
    ORDER BY t.End_Time DESC
");
$stmt->execute([$managerID]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>




<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Review Tasks</title>

<style>
body{
    font-family: Inter, Arial;
    background:#f4f6f9;
    padding:30px;
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:center;
    margin-bottom:20px;
}

.back-btn{
    text-decoration:none;
    background:#0d6efd;
    color:white;
    padding:8px 14px;
    border-radius:8px;
    font-size:14px;
}

.back-btn:hover{
    background:#0b5ed7;
}

.table{
    width:100%;
    background:white;
    border-radius:12px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
}

th, td{
    padding:12px;
    border-bottom:1px solid #eee;
}

th{
    background:#f8f9fb;
    text-align:left;
}

.btn{
    padding:6px 12px;
    border:none;
    border-radius:6px;
    cursor:pointer;
    font-size:13px;
}

.approve{
    background:#28a745;
    color:white;
}

.reject{
    background:#ffc107;
}

.empty{
    background:white;
    padding:20px;
    border-radius:10px;
}
</style>
</head>

<body>

<div class="header">
    <h2>📝 Tasks Waiting for Review</h2>
    <a href="manager.php" class="back-btn">⬅ Back to Dashboard</a>
</div>

<?php if (empty($tasks)): ?>
    <div class="empty">No tasks to review 🎉</div>
<?php else: ?>
<table class="table">
<thead>
<tr>
    <th>ID</th>
    <th>Title</th>
    <th>User</th>
    <th>Description</th>
    <th>Bounty</th>
    <th>Submitted At</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>

<?php foreach ($tasks as $task): ?>
<tr>
    <td><?= $task['id_T'] ?></td>
    <td><?= htmlspecialchars($task['Title_T']) ?></td>
    <td><?= htmlspecialchars($task['user_name']) ?></td>
    <td><?= htmlspecialchars($task['description']) ?></td>
    <td>$<?= $task['bounty'] ?></td>
    <td><?= $task['End_Time'] ?></td>
    <td>
        <form method="POST" style="display:inline">
            <input type="hidden" name="task_id" value="<?= $task['id_T'] ?>">
            <button class="btn approve" name="action" value="approve">✔ Approve</button>
        </form>

        <form method="POST" style="display:inline">
            <input type="hidden" name="task_id" value="<?= $task['id_T'] ?>">
            <button class="btn reject" name="action" value="reject">↩ Send Back</button>
        </form>
    </td>
</tr>
<?php endforeach; ?>

</tbody>
</table>
<?php endif; ?>

</body>
</html>
