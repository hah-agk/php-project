<?php
session_start();
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
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Skills</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    font-family:Inter, Arial, sans-serif;
    background:#f4f6f9;
}
.container{
    max-width:1000px;
    margin:50px auto;
    background:#fff;
    padding:30px;
    border-radius:16px;
    box-shadow:0 15px 30px rgba(0,0,0,.08);
}
.top-actions{
    margin-bottom:20px;
}
.back-btn{
    padding:10px 16px;
    background:#f3f4f6;
    text-decoration:none;
    color:#111;
    border-radius:8px;
    font-weight:600;
}
h1{
    margin:15px 0 25px;
    display:flex;
    gap:10px;
}
table{
    width:100%;
    border-collapse:collapse;
}
thead{
    background:#1f2937;
    color:#fff;
}
th,td{
    padding:14px;
}
tbody tr{
    border-bottom:1px solid #e5e7eb;
}
.badge{
    padding:6px 14px;
    border-radius:20px;
    font-weight:600;
}
.beginner{background:#e0f2fe;color:#0369a1;}
.intermediate{background:#fef3c7;color:#92400e;}
.expert{background:#dcfce7;color:#166534;}

.actions{
    display:flex;
    gap:10px;
}
.btn{
    padding:8px 10px;
    border-radius:8px;
    color:#fff;
    text-decoration:none;
}
.btn.edit{background:#2563eb;}
.btn.delete{background:#dc2626;}
</style>
</head>

<body>
<div class="container">

    <div class="top-actions">
        <a href="../user.php" class="back-btn">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    <h1>
        <i class="fas fa-lightbulb"></i>
        My Skills
    </h1>

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
                <td><?= $i+1 ?></td>
                <td><?= htmlspecialchars($skill['skill_name']) ?></td>
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
