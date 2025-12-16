<?php
session_start();
require '../component/opendb.php';

if (!isset($_SESSION['userID'])) {
    header("Location: signup.php");
    exit();
}

$userID = (int)$_SESSION['userID'];

$stmt = $pdo->prepare("
SELECT 
    t.id_T,
    t.Title_T,
    t.status,
    t.bounty,
    t.required_skill,
    t.description,
    t.Start_Time,
    t.End_Time,
    m.name AS manager_name
FROM task t
JOIN manager m ON t.manager_id = m.id_m
WHERE t.user_id = ?
ORDER BY t.id_T DESC
");
$stmt->execute([$userID]);
$tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

$selectedTask = null;
if (isset($_GET['task_id'])) {
    $taskId = intval($_GET['task_id']);
    foreach ($tasks as $t) {
        if ($t['id_T'] == $taskId) {
            $selectedTask = $t;
            break;
        }
    }
}

/* Badge helper */
function badge($s){
    return match($s){
        'pending'        => 'badge pending',
        'in_progress'    => 'badge progress',
        'waiting_review' => 'badge review',
        'completed'      => 'badge done',
        'rejected'       => 'badge rejected',
        default          => 'badge'
    };
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Tasks</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>
body{font-family:Inter,Arial;background:#f4f6f9;margin:0}
.wrap{max-width:1200px;margin:28px auto;padding:0 18px}

.back-btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    margin-bottom:14px;
    padding:10px 14px;
    background:#fff;
    color:#0f172a;
    text-decoration:none;
    font-weight:800;
    border-radius:12px;
    border:1px solid rgba(0,0,0,.08);
    box-shadow:0 10px 22px rgba(0,0,0,.06);
}

h2{margin-bottom:14px}

table{
    width:100%;
    background:#fff;
    border-radius:16px;
    box-shadow:0 12px 30px rgba(0,0,0,.08);
    border-collapse:collapse
}

th,td{padding:14px 16px;text-align:left}
th{background:#f1f5f9;font-weight:800}
tr:not(:last-child) td{border-bottom:1px solid #e5e7eb}

.badge{
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:800;
    color:#fff
}
.pending{background:#f59e0b}
.progress{background:#0ea5e9}
.review{background:#f97316}
.done{background:#22c55e}
.rejected{background:#ef4444}

.view-btn{
    background:#334155;
    color:#fff;
    padding:8px 12px;
    border-radius:10px;
    text-decoration:none;
    font-weight:700
}

.details{
    margin-top:22px;
    background:#fff;
    padding:20px;
    border-radius:16px;
    border:1px solid rgba(0,0,0,.06);
    box-shadow:0 12px 30px rgba(0,0,0,.08)
}

.label{font-weight:800;color:#334155}

.submit-btn{
    margin-top:16px;
    background:#10b981;
    color:#fff;
    border:none;
    padding:10px 18px;
    border-radius:12px;
    font-weight:800;
    cursor:pointer;
    box-shadow:0 8px 20px rgba(16,185,129,.35);
}
.submit-btn:hover{background:#059669}
</style>
</head>

<body>
<div class="wrap">

<a href="../user.php" class="back-btn">
    <i class="fas fa-arrow-left"></i> Back
</a>

<h2><i class="fas fa-list-check"></i> My Tasks</h2>

<?php if (!$tasks): ?>
<p>No tasks assigned.</p>
<?php else: ?>

<table>
<thead>
<tr>
    <th>Title</th>
    <th>Manager</th>
    <th>Skill</th>
    <th>Bounty</th>
    <th>Status</th>
    <th>Action</th>
</tr>
</thead>
<tbody>
<?php foreach ($tasks as $t): ?>
<tr>
    <td><?= htmlspecialchars($t['Title_T']) ?></td>
    <td><?= htmlspecialchars($t['manager_name']) ?></td>
    <td><?= htmlspecialchars($t['required_skill']) ?></td>
    <td>$<?= number_format($t['bounty'],2) ?></td>
    <td>
        <span class="<?= badge($t['status']) ?>">
            <?= htmlspecialchars($t['status']) ?>
        </span>
    </td>
    <td>
        <a class="view-btn" href="?task_id=<?= $t['id_T'] ?>">
            <i class="fas fa-eye"></i> View
        </a>
    </td>
</tr>
<?php endforeach; ?>
</tbody>
</table>

<?php if ($selectedTask): ?>
<div class="details">
    <h3>Task Details</h3>

    <p><span class="label">Title:</span>
        <?= htmlspecialchars($selectedTask['Title_T']) ?></p>

    <p><span class="label">Manager:</span>
        <?= htmlspecialchars($selectedTask['manager_name']) ?></p>

    <p><span class="label">Skill:</span>
        <?= htmlspecialchars($selectedTask['required_skill']) ?></p>

    <p><span class="label">Bounty:</span>
        $<?= number_format($selectedTask['bounty'],2) ?></p>

    <p><span class="label">Status:</span>
        <span class="<?= badge($selectedTask['status']) ?>">
            <?= htmlspecialchars($selectedTask['status']) ?>
        </span>
    </p>

    <p><span class="label">Start Time:</span>
        <?= htmlspecialchars($selectedTask['Start_Time'] ?? '-') ?></p>

    <p><span class="label">Deadline:</span>
        <?= htmlspecialchars($selectedTask['End_Time'] ?? '-') ?></p>

    <p><span class="label">Description:</span><br>
        <?= nl2br(htmlspecialchars($selectedTask['description'])) ?></p>

    <?php if ($selectedTask['status'] === 'in_progress'): ?>
        <form method="POST" action="submit_task.php">
            <input type="hidden" name="task_id" value="<?= $selectedTask['id_T'] ?>">
            <button type="submit" class="submit-btn">
                <i class="fas fa-paper-plane"></i> Submit Task
            </button>
        </form>
    <?php endif; ?>

</div>
<?php endif; ?>

<?php endif; ?>

</div>
</body>
</html>
