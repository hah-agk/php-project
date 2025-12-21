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

<style>


@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap');

@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
 

:root {
    /* Light theme */
    --bg: #f8fafc;
    --sidebar-bg: #0f172a;
    --accent: #3b82f6;
    --accent-hover: #2563eb;
    --accent-light: #dbeafe;
    --muted: #64748b;
    --card-bg: #ffffff;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
    --shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    --text-color: #1e293b;
    --text-secondary: #64748b;
    --content-bg: #f1f5f9;
    --border: #e2e8f0;
    --border-light: #f1f5f9;
    --hover-bg: #f8fafc;
    --success: #10b981;
    --warning: #f59e0b;
    --danger: #ef4444;
    --info: #3b82f6;
    --radius: 12px;
    --radius-sm: 8px;
    --transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    --accentt: #06b6d4;
    --accentt-hover: #0891b2;
}

html[data-theme="dark"] {
    --bg: #0f172a;
    --sidebar-bg: #1e293b;
    --accent: #60a5fa;
    --accent-hover: #93c5fd;
    --accent-light: #1e3a8a;
    --muted: #94a3b8;
    --card-bg: #1e293b;
    --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3), 0 2px 4px -1px rgba(0, 0, 0, 0.2);
    --shadow-hover: 0 20px 25px -5px rgba(0, 0, 0, 0.3), 0 10px 10px -5px rgba(0, 0, 0, 0.2);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.3), 0 4px 6px -2px rgba(0, 0, 0, 0.2);
    --text-color: #f1f5f9;
    --text-secondary: #94a3b8;
    --content-bg: #0f172a;
    --border: #334155;
    --border-light: #1e293b;
    --hover-bg: #334155;
    --success: #10b981;
    --warning: #fbbf24;
    --danger: #f87171;
    --info: #60a5fa;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    background: var(--bg);
    color: var(--text-color);
    line-height: 1.6;
    min-height: 100vh;
    padding: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

.container {
    width: 500px;
    min-height: 100vh;
    
    margin: 0 auto;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}
.container:hover{
    transform: translateY(-5px);
    width: 800px;

    
  
}

/* Header */
.header {
    text-align: center;
    margin-bottom: 32px;
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

.back-btn:hover {
    background: var(--accent);
    color: white;
    border-color: var(--accent);
    transform: translateY(-2px);
    box-shadow: var(--shadow-hover);
}

.back-btn i {
    font-size: 12px;
}

/* Card */
.card {
    background: var(--card-bg);
    border-radius: var(--radius);
    padding: 40px;
    box-shadow: var(--shadow-lg);
    border: 1px solid var(--border);
    position: relative;
    overflow: hidden;
}

.card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, var(--accentt), var(--accentt-hover));
}

.card-header {
    text-align: center;
    margin-bottom: 32px;
}

.card-title {
     font-size: 32px;
    font-weight: 800;
    margin-bottom: 8px;
    letter-spacing: -0.025em;

    z-index: 1;
}

.card-title i {
    color: var(--accent);
    font-size: 24px;
}

.card-subtitle {
 font-size: 16px;
    opacity: 0.95;
    margin: 0;
   
    z-index: 1;
}

/* Form */
.form-group {
    margin-bottom: 24px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 600;
    color: var(--text-color);
    font-size: 14px;
}

.form-control {
    width: 100%;
    padding: 14px 16px;
    border-radius: var(--radius-sm);
    border: 2px solid var(--border);
    background: var(--card-bg);
    font-size: 15px;
    color: var(--text-color);
    transition: var(--transition);
    font-family: 'Inter', sans-serif;
}

.form-control:focus {
    outline: none;
    border-color: var(--accent);
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.form-control::placeholder {
    color: var(--text-secondary);
    opacity: 0.7;
}

select.form-control {
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748b' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 16px center;
    background-size: 16px;
    padding-right: 44px;
}

/* Custom Skill Animation */
#customSkillBox {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Button */
.btn {
 background: linear-gradient(135deg, var(--accentt) 0%, var(--accentt-hover) 100%);
    border-radius: 20px;
    padding: 32px 40px;
    margin-bottom: 32px;
    box-shadow: var(--shadow);
    color: white;
    position: relative;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
width: 100%;}
    

.btn:hover {
    transform: translateY(-2px);
    box-shadow: var(--shadow-hover);
}

.btn:active {
    transform: translateY(0);
}

.btn::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 5px;
    height: 5px;
    background: rgba(255, 255, 255, 0.5);
    opacity: 0;
    border-radius: 100%;
    transform: scale(1, 1) translate(-50%);
    transform-origin: 50% 50%;
}

.btn:focus:not(:active)::after {
    animation: ripple 1s ease-out;
}


/* Messages */
.message {
    padding: 16px;
    border-radius: var(--radius-sm);
    margin-bottom: 24px;
    font-weight: 500;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 12px;
    animation: slideIn 0.3s ease-out;
}


.message.error {
    background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
    color: var(--danger);
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.message.success {
    background: linear-gradient(135deg, rgba(16, 185, 129, 0.1), rgba(16, 185, 129, 0.05));
    color: var(--success);
    border: 1px solid rgba(16, 185, 129, 0.2);
}

.message i {
    font-size: 16px;
}

/* Skill Level Indicator */
.level-indicator {
    display: flex;
    gap: 4px;
    margin-top: 8px;
}

.level-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--border);
    transition: var(--transition);
}

.level-dot.active {
    background: var(--accent);
    transform: scale(1.2);
}



</style>

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

