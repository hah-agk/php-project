<?php
session_start();
$theme = $_SESSION['theme'] ?? 'light';
require '../component/opendb.php';

if (!isset($_SESSION['userID'])) {
    header("Location: ../signup.php");
    exit();
}

$userID = (int)$_SESSION['userID'];

$stmt = $pdo->prepare("
    SELECT id_S, skill_name, proficiency_level
    FROM skills
    WHERE user_id = ?
    ORDER BY id_S DESC
");
$stmt->execute([$userID]);
$skills = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= $theme ?>">
<head>
<meta charset="UTF-8">
<title>My Skills</title>
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
}
body{
    font-family:Inter, Arial, sans-serif;
    background:var(--bg);
    color:var(--text-color);
} 
.container{
    max-width:1000px;
    margin:50px auto;
    background:var(--card-bg);
    padding:30px;
    border-radius:16px;
    box-shadow:var(--shadow);
    border:1px solid var(--border);
    transition: all 0.18s ease;
}
.container:hover{
    transform: translateY(-5px);
    box-shadow:var(--shadow-hover);
} 

.top-actions{
    margin-bottom:20px;
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
     transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

.back-btn:hover {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
    transform: translateY(-5px);
    box-shadow: var(--shadow-hover);
}

.back-btn i {
    font-size: 12px;
}

h1{
    margin:15px 0 25px;
    display:flex;
    gap:10px;
}
h1 i{
  color: var(--accent);
}
table{
    width:100%;
    border-collapse:collapse;
}
thead{
    background:linear-gradient(90deg, var(--accent), var(--accent-hover));
    color:#fff;
}
th,td{
    padding:14px;
}
tbody tr{
    border-bottom:1px solid var(--border);
} 

.badge{
 position: relative;
 left:66px
   
} 
.beginner{ background: rgba(59,130,246,0.08); color: var(--accent); }
.intermediate{ background: rgba(245,158,11,0.08); color: var(--warning); }
.expert{ background: rgba(16,185,129,0.08); color: var(--success); } 

.actions{
  position: relative;
  right: -125px;
}


.skills{
    position: relative;
    left: 105px;
}
.id{
    position: relative;
    left: 30px;

}






.btn{
    padding:8px 10px;
    border-radius:8px;
    color:#fff;
    text-decoration:none;
    display:inline-flex;
    align-items:center;
    justify-content:center;
    width:36px;
    height:36px;
}
.btn.edit{background: linear-gradient(90deg,var(--accent),var(--accent-hover));}
.btn.delete{background: linear-gradient(90deg,var(--danger), #ef4444);}
</style>
</head>

<body>
<div class="container">

    <div class="top-actions">
        <a href="../user.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>
<div>
    <h1>
        <i class="fas fa-lightbulb"></i>
        My Skills
    </h1></div>

    <?php if (!$skills): ?>
        <p>No skills added yet.</p>
    <?php else: ?>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Skill Name</th>
                <th>Level</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($skills as $i => $skill): ?>
            <tr>
                <td class="id"><?= $i+1 ?></td>
                <td class="skills"><?= htmlspecialchars($skill['skill_name']) ?></td>
                <td>
                    <span class="badge <?= $skill['proficiency_level'] ?>">
                        <?= ucfirst($skill['proficiency_level']) ?>
                    </span>
                </td>
                <td class="actions">
                    <a href="edit_skill.php?id=<?= $skill['id_S'] ?>" class="btn edit">
                        <i class="fas fa-pen"></i>
                    </a>
                    <a href="delete_skill.php?id=<?= $skill['id_S'] ?>"
                       class="btn delete"
                       onclick="return confirm('Delete this skill?');">
                        <i class="fas fa-trash"></i>
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <?php endif; ?>

</div>
</body>
</html>
