<?php
session_start();
require '../component/opendb.php';

if (!isset($_SESSION['userID'])) {
    header("Location: ../signup.php");
    exit();
}

$userID  = (int)$_SESSION['userID'];
$skillID = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("
    SELECT skill_name, proficiency_level
    FROM skills
    WHERE id_S = ? AND user_id = ?
");
$stmt->execute([$skillID, $userID]);
$skill = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$skill) {
    die("Skill not found");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $skill_name = trim($_POST['skill_name']);
    $level      = $_POST['proficiency_level'];

    if ($skill_name && $level) {
        $stmt = $pdo->prepare("
            UPDATE skills
            SET skill_name = ?, proficiency_level = ?
            WHERE id_S = ? AND user_id = ?
        ");
        $stmt->execute([$skill_name, $level, $skillID, $userID]);

        header("Location: show_skill.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit Skill</title>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    font-family:Inter, Arial, sans-serif;
    background:#f4f6f9;
}
.container{
    max-width:600px;
    margin:60px auto;
    background:#fff;
    padding:35px;
    border-radius:16px;
    box-shadow:0 15px 30px rgba(0,0,0,.08);
}
.back-btn{
    text-decoration:none;
    font-weight:600;
}
.form-group{
    margin-bottom:20px;
}
label{
    display:block;
    margin-bottom:6px;
}
input,select{
    width:100%;
    padding:12px;
    border-radius:8px;
    border:1px solid #d1d5db;
}
button{
    width:100%;
    padding:14px;
    border:none;
    background:#2563eb;
    color:#fff;
    font-size:16px;
    border-radius:10px;
}
</style>
</head>

<body>
<div class="container">

    <a href="show_skill.php" class="back-btn">
        <i class="fas fa-arrow-left"></i> Back to Skills
    </a>

    <h1 style="margin:20px 0;">
        <i class="fas fa-pen"></i>
        Edit Skill
    </h1>

    <form method="post">
        <div class="form-group">
            <label>Skill Name</label>
            <input type="text" name="skill_name"
                   value="<?= htmlspecialchars($skill['skill_name']) ?>" required>
        </div>

        <div class="form-group">
            <label>Level</label>
            <select name="proficiency_level" required>
                <option value="beginner" <?= $skill['proficiency_level']=='beginner'?'selected':'' ?>>Beginner</option>
                <option value="intermediate" <?= $skill['proficiency_level']=='intermediate'?'selected':'' ?>>Intermediate</option>
                <option value="expert" <?= $skill['proficiency_level']=='expert'?'selected':'' ?>>Expert</option>
            </select>
        </div>

        <button type="submit">Update Skill</button>
    </form>

</div>
</body>
</html>
