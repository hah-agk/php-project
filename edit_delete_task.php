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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Task</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: #f5f7fa;
        }
        .form-container {
            width: 500px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0px 0px 20px rgba(0,0,0,0.15);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: 600;
        }
        label {
            margin-top: 10px;
            font-weight: 500;
        }
        button, a.btn {
            margin-top: 20px;
            width: 48%;
        }
        .btn-container {
            display: flex;
            justify-content: space-between;
        }
    </style>
</head>

<body class="p-5">

<div class="form-container">
    <h2>Edit Task</h2>

    <form method="POST">

        <label>Title</label>
        <input type="text" name="title" class="form-control" 
               required value="<?= htmlspecialchars($task['Title_T']); ?>">

        <label>Description</label>
        <textarea name="description" class="form-control" required><?= htmlspecialchars($task['description']); ?></textarea>

        <label>Bounty</label>
        <input type="number" step="5" name="bounty" min="0" 
               class="form-control" required 
               value="<?= htmlspecialchars($task['bounty']); ?>">

        <label>End Time</label>
        <input type="date" name="end_time" class="form-control"
               required value="<?= htmlspecialchars($task['End_Time']); ?>">

        <label>Required Skill</label>
        <select name="required_skill" id="skillSelect" class="form-control" onchange="toggleOtherSkill()">
            <option value="Java" <?= $task['required_skill']=="Java"?"selected":"" ?>>Java</option>
            <option value="PHP" <?= $task['required_skill']=="PHP"?"selected":"" ?>>PHP</option>
            <option value="Laravel" <?= $task['required_skill']=="Laravel"?"selected":"" ?>>Laravel</option>
            <option value="JavaScript" <?= $task['required_skill']=="JavaScript"?"selected":"" ?>>JavaScript</option>
            <option value="Python" <?= $task['required_skill']=="Python"?"selected":"" ?>>Python</option>
            <option value="C#" <?= $task['required_skill']=="C#"?"selected":"" ?>>C#</option>
            <option value="Other" <?= !in_array($task['required_skill'], ["Java","PHP","Laravel","JavaScript","Python","C#"]) ? "selected" : "" ?>>Other</option>
        </select>

        <input 
            type="text" 
            name="other_skill" 
            id="otherSkillBox" 
            class="form-control mt-2"
            placeholder="Enter custom skill..."
            style="<?= !in_array($task['required_skill'], ["Java","PHP","Laravel","JavaScript","Python","C#"]) ? "" : "display:none;" ?>"
            value="<?= !in_array($task['required_skill'], ["Java","PHP","Laravel","JavaScript","Python","C#"]) ? htmlspecialchars($task['required_skill']) : "" ?>"
        >

        <div class="btn-container">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="manager.php" class="btn btn-secondary">Cancel</a>
        </div>

    </form>
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
