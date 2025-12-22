<?php
session_start();
$theme = $_SESSION['theme'] ?? 'light';
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
<html lang="en" data-theme="<?= $theme ?>">
<head>
<meta charset="UTF-8">
<title>Add Skill</title>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/addskils_user.css">



<div class="container">
    <div class="header">
        <a href="../user.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>
            Back to Dashboard
        </a>
    </div>

    <div class="card">
        <div class="card-header">
            <h1 class="card-title">
                <i class="fas fa-magic"></i>
                Add New Skill
            </h1>
            <p class="card-subtitle">Enhance your profile with new skills</p>
        </div>

        <?php if ($error): ?>
            <div class="message error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="message success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form method="post" id="skillForm">
            <div class="form-group">
                <label class="form-label">Skill Name</label>
                <select name="skill_select" id="skillSelect" class="form-control" required onchange="toggleCustomSkill()">
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

            <div class="form-group" id="customSkillBox" style="display:none;">
                <label class="form-label">Custom Skill Name</label>
                <input type="text" name="custom_skill" class="form-control" placeholder="Enter your custom skill">
            </div>

            <div class="form-group">
                <label class="form-label">Proficiency Level</label>
                <select name="proficiency_level" class="form-control" required>
                    <option value="">-- Select Level --</option>
                    <option value="beginner">Beginner</option>
                    <option value="intermediate">Intermediate</option>
                    <option value="expert">Expert</option>
                </select>
            </div>

            <button type="submit" class="btn">
                <i class="fas fa-plus-circle"></i>
                Add Skill
            </button>
        </form>
    </div>
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

// Add focus styles for accessibility
document.addEventListener('DOMContentLoaded', function() {
    const inputs = document.querySelectorAll('.form-control');
    inputs.forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
        });
    });
});
</script>

</head>

