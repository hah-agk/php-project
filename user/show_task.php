<?php
session_start();
require '../component/opendb.php';
$theme = $_SESSION['theme'] ?? 'light';

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
<html lang="en" data-theme="<?= $theme ?>">
<head>
<meta charset="UTF-8">
<title>Available Tasks</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">

<style>
    
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap');

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
    --border: #e2e8f0;
    --content-bg: #f1f5f9;
}

/* DARK MODE */
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
}
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


/*search bar*/
.search {
    position: relative;
    width: 320px;
    max-width: 100%;
}

.search-s {
    width: 100%;
    padding: 12px 45px 12px 15px;
    border-radius: 25px;
    border: 1px solid #e5e7eb;
    font-size: 14px;
    outline: none;
    transition: all 0.3s ease;
    background-color: #fff;
}

.search-s::placeholder {
    color: #9ca3af;
 
}

.search-s:focus {
    border-color: #06b6d4;
    box-shadow: 0 0 0 3px rgba(6, 182, 212, 0.25);
}

.search-icon {
    position: absolute;
    top: 50%;
    right: 15px;
    transform: translateY(-50%);
    color: #6b7280;
    cursor: pointer;
    transition: color 0.3s ease;
}

.search:hover .search-icon {
    color: #06b6d4;
}
.task-card{
transition: transform 0.25s ease;
}
.task-card:hover{
    transform: translateY(-5px);
  width: 500px;
  height: 300px;
}
.header {
    background: linear-gradient(135deg, var(--accent) 0%, var(--accent-hover) 100%);
    border-radius: 20px;
    padding: 32px 40px;
    margin-bottom: 32px;
    box-shadow: var(--shadow);
    color: white;
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    
}
.header:hover {
    transform: translateY(-8px);
    box-shadow: var(--shadow-hover);}


.header h1 {
    font-size: 32px;
    font-weight: 800;
    margin-bottom: 8px;
    letter-spacing: -0.025em;
  
    z-index: 1;
}

.header p {
    font-size: 16px;
    opacity: 0.95;
    margin: 0;
   
    z-index: 1;
}


</style>
</head>

<body>
    <a href="../user.php" class="btn-back">← Back to Dashboard</a>
<div class="header">
<h1>Available Tasks</h1>
<p>Choose a task posted by managers and start working 🚀</p></div>

<div class="search">
<input type="search" placeholder="Search.." class="search-s">
<div class="search-icon"><i class="fa-solid fa-magnifying-glass"></i></div>

</div>


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
