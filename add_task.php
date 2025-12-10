<?php
require 'component/opendb.php';
session_start();

if (!isset($_SESSION['LoggedIn']) || $_SESSION['LoggedIn'] !== true) {
    header("Location: signup.php");
    exit;
}
if (!isset($_SESSION['UorM']) || $_SESSION['UorM'] !== 'manager') {
    header("Location: signup.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 $title_T= trim($_POST['title']);
 $description= trim($_POST['description']);
 $bounty= trim($_POST['bounty']);
 $end_time= trim($_POST['end_time']);
 $skill = ($_POST['required_skill'] === "Other") 
         ? $_POST['other_skill'] 
         : $_POST['required_skill'];


    if (!isset($title_T) || empty(trim($title_T))
        || !isset($description) || empty(trim($description))
        || !isset($end_time) || empty(trim($end_time))
        || !isset($skill) || empty(trim($skill))
        ||  !isset($bounty) || empty(trim($bounty)) 
    ) {
        header("location: add_task.php?error=1");
        exit();
    }
    if ($bounty < 0) {
        header("location: add_task.php?error=2");
        exit();
    }

    if (isset($_SESSION['managerID'])) {
        $mid = $_SESSION['managerID'];
    } else {
        
        header("Location: signup.php");
        exit();
    }
    $sql = "INSERT INTO task (Title_T , description, bounty , End_Time, required_skill, manager_id) 
            VALUES (:title_T,:description,:bounty,:end_time,:skill,:mid)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':title_T', $title_T);
    $stmt->bindParam(':description', $description);
    $stmt->bindParam(':bounty', $bounty, PDO::PARAM_INT);
    $stmt->bindParam(':end_time', $end_time);
    $stmt->bindParam(':skill', $skill);
    $stmt->bindParam(':mid', $mid, PDO::PARAM_INT);
    
    $stmt->execute();

    header("Location: manager.php");
    exit();
}

?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Task</title>
    

<style>
    body {
        font-family: Arial, sans-serif;
        background: #f3f3f3;
        padding: 30px;
    }

    .form-container {
        width: 420px;
        background: #fff;
        padding: 25px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        margin: auto;
    }

    h2 {
        text-align: center;
        margin-bottom: 20px;
    }

    label {
        font-weight: bold;
        margin-top: 10px;
        display: block;
    }

    input, select, textarea {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        border: 1px solid #ccc;
        border-radius: 6px;
        font-size: 15px;
    }

    textarea {
        height: 120px;
        resize: none;
    }

    .btn {
        width: 100%;
        background: #4CAF50;
        color: #fff;
        border: none;
        padding: 12px;
        border-radius: 6px;
        font-size: 17px;
        cursor: pointer;
        margin-top: 15px;
    }

    .btn:hover {
        background: #45a049;
    }
</style>

</head>
<body>

<div class="form-container">
    <h2>Create New Task</h2>

    <form action="add_task.php" method="POST">

        <label>Title</label>
        <input type="text" name="title" required>

     

        <label>Description</label>
        <textarea name="description" required></textarea>

        <label>Bounty</label>
        <input type="number" step="5" name="bounty" min="0">

        <label>End Time</label>
        <input type="date" name="end_time">

              <label>Required Skill</label>
        <select name="required_skill" id="skillSelect" onchange="toggleOtherSkill()">
            <option value="Java">Java</option>
            <option value="PHP">PHP</option>
            <option value="Laravel">Laravel</option>
            <option value="JavaScript">JavaScript</option>
            <option value="Python">Python</option>
            <option value="C#">C#</option>
            <option value="Other">Other</option>
        </select>

        
        <input type="text" name="other_skill" id="otherSkillBox" placeholder="Enter custom skill..." style="display:none;"
>

        <button class="btn" type="submit">Create Task</button>

    </form>
        <?php
        if (isset($_GET['error']) && $_GET['error'] == 1) {
            echo "<p class='error-msg'>Please fill in all required fields (bounty must be ≥ 0).</p>";
        }
        if (isset($_GET['error']) && $_GET['error'] == 2) {
            echo "<p class='error-msg'>Bounty must be a non-negative value.</p>";
        }
        ?>
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