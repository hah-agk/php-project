<?php 
session_start(); 
require 'component/opendb.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Task ID is missing!");
}

$id = intval($_GET['id']); //intval bethwel mn string l integer w security betehme mn `sql injection`

// DELETE TASK
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM task WHERE id_T = ?");
    $stmt->execute([$id]);

    header("Location: manager.php");
    exit;
}

// jib el task details
$stmt = $pdo->prepare("SELECT * FROM task WHERE id_T = ?");
$stmt->execute([$id]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    die("Task not found!");
}

// UPDATE TASK
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title_T = trim($_POST['title']);
    $description = trim($_POST['description']);
    $bounty = trim($_POST['bounty']);
    $end_time = trim($_POST['end_time']);
    $skill = ($_POST['required_skill'] === "Other") 
             ? $_POST['other_skill']
             : $_POST['required_skill'];

    if (empty($title_T) || empty($description) || empty($end_time) || empty($skill) || empty($bounty)) {
        header("location: edit_task.php?id=$id&error=1");
        exit();
    }

    if ($bounty < 0) {
        header("location: edit_task.php?id=$id&error=2");
        exit();
    }

    $sql = "UPDATE task 
            SET Title_T = ?, description = ?, bounty = ?, End_Time = ?, required_skill = ?
            WHERE id_T = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$title_T, $description, $bounty, $end_time, $skill, $id]);

    header("Location: manager.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= $theme ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Task - Manager Panel</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/add_task.css">
</head>
<body>


    <!-- Main Content -->
    <div class="main-content">


       <!-- Back Button -->
        <a href="manager.php" class="back-btn">
            <i class="fas fa-arrow-left"></i>Back to Dashboard
        </a>
        <!-- Page Header -->
        <div class="page-header">
            <h1><i class="fas fa-tasks"></i> Edit your Task</h1>
            <p>Fill in the details below to edit your task for your team</p>
        </div>

      
        <!-- Form Container -->
        <div class="form-container">
            <h2>
                <i class="fas fa-clipboard-list"></i>Edit Task Details
            </h2>

            <form action="edit_delete_task.php" method="POST">
                
                <!-- Title -->
                <div class="form-group">
                    <label>
                        <i class="fas fa-heading"></i>
                        Task Title
                        <span class="required"></span>
                    </label>
                    <input type="text" 
                           name="title" 
                           class="form-control" 
                           placeholder="Enter task title..."
                           required>
                </div>

                <!-- Description -->
                <div class="form-group">
                    <label>
                        <i class="fas fa-align-left"></i>
                        Description
                        <span class="required"></span>
                    </label>
                    <textarea name="description" 
                              class="form-control" 
                              placeholder="Provide a detailed description of the task..."
                              required></textarea>
                </div>

                <!-- Bounty -->
                <div class="form-group">
                    <label>
                        <i class="fas fa-dollar-sign"></i>
                        Bounty Amount
                        <span class="required"></span>
                    </label>
                    <input type="number" 
                           step="5" 
                           name="bounty" 
                           class="form-control" 
                           placeholder="Enter bounty amount..."
                           min="0"
                           required>
                </div>

                <!-- End Time -->
                <div class="form-group">
                    <label>
                        <i class="fas fa-calendar-alt"></i>
                        End Date
                        <span class="required"></span>
                    </label>
                    <input type="date" 
                           name="end_time" 
                           class="form-control"
                           required>
                </div>

                <!-- Required Skill -->
                <div class="form-group">
                    <label>
                        <i class="fas fa-code"></i>
                        Required Skill
                        <span class="required"></span>
                    </label>
                    <select name="required_skill" 
                            id="skillSelect" 
                            class="form-select" 
                            onchange="toggleOtherSkill()"
                            required>
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
                    
                    <input type="text" 
                           name="other_skill" 
                           id="otherSkillBox" 
                           class="form-control"
                           placeholder="Enter custom skill..." 
                           style="display:none;">
                </div>

                <!-- Submit Button -->
                <button class="btn-submit" type="submit">
                    <i class="fas fa-check-circle"></i>
                    Update Task
                </button>

            </form>

            <!-- Error Messages -->
            <?php
            if (isset($_GET['error']) && $_GET['error'] == 1) {
                echo "<div class='error-msg'>
                        <i class='fas fa-exclamation-circle'></i>
                        Please fill in all required fields. Bounty must be ≥ 0.
                      </div>";
            }
            if (isset($_GET['error']) && $_GET['error'] == 2) {
                echo "<div class='error-msg'>
                        <i class='fas fa-exclamation-circle'></i>
                        Bounty must be a non-negative value.
                      </div>";
            }
            ?>
        </div>
    </div>
<script>
function toggleOtherSkill() {
    var select = document.getElementById("skillSelect");
    var otherBox = document.getElementById("otherSkillBox");

    if (select.value === "Other") {
        otherBox.style.display = "block";
    } else {
        otherBox.style.display = "none";
        otherBox.value = "";
    }
}
</script>

</body>
</html>
