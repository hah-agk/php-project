<?php
session_start();
require '../component/opendb.php';


// check if user is logged in

if (!isset($_SESSION['userID'])) {
    header("Location: ../signup.php");
    exit();
}

$userID  = (int)$_SESSION['userID'];
$error   = '';
$success = '';


// handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $skill_select = $_POST['skill_select'] ?? '';
    $custom_skill = trim($_POST['custom_skill'] ?? '');
    $level        = $_POST['proficiency_level'] ?? '';

    if ($skill_select === '' || $level === '') {
        $error = "All fields are required.";
    } else {

        if ($skill_select === 'Other') {
            if ($custom_skill === '') {
                $error = "Please enter your custom skill.";
            } else {
                $skill_name = $custom_skill;
            }
        } else {
            $skill_name = $skill_select;
        }
    }

    // insert skill into database
    if ($error === '') {
        $stmt = $pdo->prepare("
            INSERT INTO skills (skill_name, user_id, proficiency_level)
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$skill_name, $userID, $level]);

        $success = "Skill added successfully.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Add Skill</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
body{
    margin:0;
    padding:0;
    font-family:Inter, Arial, sans-serif;
    background:#f4f6f9;
}

.container{
    max-width:600px;
    margin:60px auto;
    background:#fff;
    padding:35px;
    border-radius:14px;
    box-shadow:0 15px 30px rgba(0,0,0,.08);
}

/* ---------- TOP ACTIONS ---------- */
.top-actions{
    margin-bottom:20px;
}

.back-btn{
    display:inline-flex;
    align-items:center;
    gap:8px;
    padding:10px 16px;
    background:#f3f4f6;
    color:#111827;
    text-decoration:none;
    border-radius:8px;
    font-weight:600;
    transition:.2s;
}

.back-btn:hover{
    background:#e5e7eb;
}

/* ---------- TITLE ---------- */
h1{
    margin-bottom:25px;
    display:flex;
    align-items:center;
    gap:12px;
    font-size:28px;
}

/* ---------- FORM ---------- */
.form-group{
    margin-bottom:20px;
}

label{
    display:block;
    margin-bottom:8px;
    font-weight:600;
}

input, select{
    width:100%;
    padding:14px;
    border-radius:10px;
    border:1px solid #d1d5db;
    font-size:15px;
}

input:focus, select:focus{
    outline:none;
    border-color:#2563eb;
}

button{
    width:100%;
    padding:14px;
    border:none;
    border-radius:10px;
    background:#2563eb;
    color:#fff;
    font-size:17px;
    font-weight:600;
    cursor:pointer;
}

button:hover{
    background:#1e40af;
}

/* ---------- MESSAGES ---------- */
.error{
    background:#fee2e2;
    color:#991b1b;
    padding:14px;
    border-radius:10px;
    margin-bottom:20px;
}

.success{
    background:#dcfce7;
    color:#166534;
    padding:14px;
    border-radius:10px;
    margin-bottom:20px;
}
</style>
</head>

<body>

<div class="container">

    <!-- Back to Dashboard -->
    <div class="top-actions">
        <a href="../user.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Dashboard
        </a>
    </div>

    <h1>
        <i class="fas fa-plus"></i>
        Add Skill
    </h1>

    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="success"><?= htmlspecialchars($success) ?></div>
    <?php endif; ?>

    <form method="post">

        <!-- Skill Name -->
        <div class="form-group">
            <label>Skill Name</label>
            <select name="skill_select" id="skillSelect" required onchange="toggleCustomSkill()">
                <option value="">Select a skill...</option>
                <option value="Java">Java</option>
                <option value="PHP">PHP</option>
                <option value="Laravel">Laravel</option>
                <option value="JavaScript">JavaScript</option>
                <option value="Python">Python</option>
                <option value="C#">C#</option>
                <option value="React">React</option>
                <option value="Node.js">Node.js</option>
                <option value="Other">Other (Custom)</option>
            </select>
        </div>

        <!-- Custom Skill -->
        <div class="form-group" id="customSkillBox" style="display:none;">
            <label>Custom Skill</label>
            <input type="text" name="custom_skill" placeholder="Enter your skill">
        </div>

        <!-- Level -->
        <div class="form-group">
            <label>Proficiency Level</label>
            <select name="proficiency_level" required>
                <option value="">-- Select Level --</option>
                <option value="beginner">Beginner</option>
                <option value="intermediate">Intermediate</option>
                <option value="expert">Expert</option>
            </select>
        </div>

        <button type="submit">Add Skill</button>
    </form>

</div>

<script>
function toggleCustomSkill() {
    const select = document.getElementById('skillSelect');
    const box = document.getElementById('customSkillBox');

    if (select.value === 'Other') {
        box.style.display = 'block';
    } else {
        box.style.display = 'none';
    }
}
</script>

</body>
</html>
