<?php
session_start();
$theme = $_SESSION['theme'] ?? 'light';
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
<html lang="en" data-theme="<?= $theme ?>">
<head>
<meta charset="UTF-8">
<title>My Tasks</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<style>

        :root {
    --bg: #f8fafc;
    --sidebar-bg: #0f172a;
    --accent: #06b6d4;
    --accent-hover: #0891b2;
    --muted: #64748b;
    --card-bg: #ffffff;
    --shadow: 0 10px 25px rgba(0,0,0,0.08);
    --shadow-hover: 0 20px 40px rgba(0,0,0,0.12);
    --text-color: #1e293b;
    --text-secondary: #64748b;
    --content-bg: #f1f5f9;
    --border: #e2e8f0;
    --hover-bg: #f8fafc;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #3b82f6;
    /* badge colors */
    --badge-pending: #f59e0b;
    --badge-progress: #0ea5e9;
    --badge-review: #f97316;
    --badge-done: #22c55e;
    --badge-rejected: #ef4444;
    --success-hover: #059669;
}

html[data-theme="dark"] {
    --bg: #0f172a;
    --sidebar-bg: #1e293b;
    --accent: #06b6d4;
    --accent-hover: #22d3ee;
    --muted: #94a3b8;
    --card-bg: #1e293b;
    --shadow: 0 10px 25px rgba(0,0,0,0.3);
    --shadow-hover: 0 20px 40px rgba(0,0,0,0.4);
    --text-color: #f1f5f9;
    --text-secondary: #94a3b8;
    --content-bg: #0f172a;
    --border: #334155;
    --hover-bg: #334155;
    --success: #10b981;
    --warning: #fbbf24;
    --danger: #f87171;
    --info: #60a5fa;
    /* badge colors (dark tweaks) */
    --badge-pending: #f59e0b;
    --badge-progress: #38bdf8;
    --badge-review: #fb923c;
    --badge-done: #4ade80;
    --badge-rejected: #f87171;
    --success-hover: #059669;
}
body{font-family:Inter,Arial;background:var(--bg); color:var(--text-color); margin:0} 
.wrap{max-width:1200px;
    margin:28px auto;
    padding:0 18px;}



.title{
      font-size: 27px;
    font-weight: 800;
    margin-bottom: 8px;
    letter-spacing: -0.025em;
  
    z-index: 1;
}
.back-btn {
    display: inline-flex;
    align-items: center;
    gap: 10px;
    padding: 10px 20px;
    color: var(--text-color);
    text-decoration: none;
    border-radius: var(--radius-sm);
    font-weight: 600;
    font-size: 14px;
    transition: var(--transition);
    box-shadow: var(--shadow);
    margin-bottom: 24px;
}

.back-btn i {
    background: var(--accent);
    color: white;

    background:var(--card-bg);
    color:var(--text-color);
    text-decoration:none;
    font-weight:800;
    border-radius:12px;
    border:1px solid var(--border);
    box-shadow:var(--shadow);
}



table{
    width:100%;
    background:var(--card-bg);
    border-radius:16px;
    box-shadow:var(--shadow);
    border-collapse:collapse;
    border:1px solid var(--border);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
} 
table:hover{
transform: translateY(-8px);
}

th,td{padding:14px 16px;text-align:left}
thead{ background: linear-gradient(90deg, var(--accent), var(--accent-hover)); color: #fff; font-weight: 800 }
tr:not(:last-child) td{border-bottom:1px solid var(--border)}

.badge{
    padding:6px 12px;
    border-radius:999px;
    font-size:12px;
    font-weight:800;
    color:#fff
}

.pending{background:var(--badge-pending)}
.progress{background:var(--badge-progress)}
.review{background:var(--badge-review)}
.done{background:var(--badge-done)}
.rejected{background:var(--badge-rejected)}

.view-btn{
    background:var(--muted);
    color:#fff;
    padding:8px 12px;
    border-radius:10px;
    text-decoration:none;
    font-weight:700;
    transition: all 0.18s ease;
    border:1px solid var(--border);
}
.view-btn:hover{
    transform: translateY(-3px);
    box-shadow:var(--shadow-hover);
}

.details{
    margin-top:22px;
    background:var(--card-bg);
    padding:20px;
    border-radius:16px;
    border:1px solid var(--border);
    box-shadow:var(--shadow);
} 

.label{font-weight:800;color:var(--text-color)}

.submit-btn{
    margin-top:16px;
    background:var(--success);
    color:#fff;
    border:none;
    padding:10px 18px;
    border-radius:12px;
    font-weight:800;
    cursor:pointer;
    box-shadow:var(--shadow);
}
.submit-btn:hover{background:var(--success-hover)}
</style>
</head>

<body>
<div class="wrap">

<a href="../user.php" class="back-btn">
    <i class="fas fa-arrow-left"></i> Back
</a>
<div class="title">
<h2><i class="fas fa-list-check"></i> My Tasks</h2>
</div>

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

<script>
// Listen for theme changes from Settings and update immediately
window.addEventListener('storage', function (e) {
    if (e.key === 'siteTheme') {
        document.documentElement.setAttribute('data-theme', e.newValue);
    }
});
</script>

</body>
</html>
