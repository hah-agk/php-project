<?php
session_start();
require '../component/opendb.php';

if (!isset($_SESSION['userID'])) {
    header("Location: signup.php");
    exit();
}

$userID = $_SESSION['userID'];

$sql = "
SELECT 
    t.id_T,
    t.Title_T,
    t.description,
    t.bounty,
    t.required_skill,
    m.name AS manager_name
FROM task t
JOIN manager m ON t.manager_id = m.id_m
WHERE t.status = 'pending' AND t.user_id IS NULL
ORDER BY t.id_T DESC
";

$stmt = $pdo->prepare($sql);
$stmt->execute();
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Available Tasks</title>

<style>
body{
    font-family:Inter,Arial;
    background:#f4f6f9;
    margin:0;
    padding:20px
}
.btn-back{
    display:inline-block;
    margin:10px 0 20px;
    padding:10px 18px;
    background:#e5e7eb;
    color:#111;
    text-decoration:none;
    border-radius:10px;
    font-weight:500;
}
.btn-back:hover{
    background:#d1d5db;
}
.tasks-grid{
    display:grid;
    grid-template-columns:repeat(auto-fill,minmax(280px,1fr));
    gap:20px
}
.task-card{
    background:#fff;
    border-radius:14px;
    padding:20px;
    box-shadow:0 10px 25px rgba(0,0,0,.08)
}
.task-card h3{
    margin:0 0 10px
}
.task-meta{
    font-size:14px;
    color:#555;
    margin-bottom:10px
}
.task-bounty{
    font-weight:bold;
    color:#0aa;
    margin-top:10px
}
.btn-apply{
    background:#0aa;
    color:#fff;
    border:none;
    padding:10px 16px;
    border-radius:8px;
    cursor:pointer;
    width:100%;
    margin-top:10px
}
.btn-apply:hover{
    opacity:.9
}
.empty{
    background:#fff;
    padding:30px;
    border-radius:14px;
    text-align:center
}
.message-success{
    color:green;
    margin-top:15px
}
.message-error{
    color:red;
    margin-top:15px
}
</style>
</head>

<body>

<h2>Available Tasks</h2>

<!-- زر الرجوع -->
<a href="../user.php" class="btn-back">← Back to Dashboard</a>

<p>Choose a task posted by managers and start working 🚀</p>

<?php if (empty($tasks)): ?>
    <div class="empty">
        <p>No available tasks right now.</p>
    </div>
<?php else: ?>
<div class="tasks-grid">
<?php foreach ($tasks as $task): ?>
    <div class="task-card">
        <h3><?= htmlspecialchars($task['Title_T']) ?></h3>

        <div class="task-meta">
            Manager: <?= htmlspecialchars($task['manager_name']) ?><br>
            Skill: <?= htmlspecialchars($task['required_skill']) ?>
        </div>

        <p><?= nl2br(htmlspecialchars($task['description'])) ?></p>

        <div class="task-bounty">
            Bounty: $<?= htmlspecialchars($task['bounty']) ?>
        </div>

        <form method="POST" action="show_task_action.php">
            <input type="hidden" name="task_id" value="<?= $task['id_T'] ?>">
            <button type="submit" class="btn-apply">Apply Task</button>
        </form>
    </div>
<?php endforeach; ?>
</div>
<?php endif; ?>

<?php if (isset($_GET['error']) && $_GET['error'] === 'not_available'): ?>
    <p class="message-error">❌ This task is no longer available.</p>
<?php endif; ?>

<?php if (isset($_GET['task']) && $_GET['task'] === 'accepted'): ?>
    <p class="message-success">✅ Task accepted successfully!</p>
<?php endif; ?>

</body>
</html>
