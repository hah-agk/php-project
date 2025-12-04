<?php
require 'component/opendb.php';
session_start();
//     if (!isset($_SESSION['LoggedIn']) || $_SESSION['LoggedIn'] !== true) {
//     header("Location: signup.php");
//     exit;
// }
// if (!isset($_SESSION['UorM']) || $_SESSION['UorM'] !== 'manager') {
//     header("Location: signup.php");
//     exit;
// }

if (isset($_GET['Ntask'])) {
    if ($_GET['Ntask'] == 1) {
  

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $namem = $_POST['namem'];
            $status = $_POST['status'];
            $deadline = $_POST['deadline'];
            $description = $_POST['description'];
            $bounty = $_POST['bounty'];

          if (!isset($namem) || empty(trim($namem))
    || !isset($status) || empty(trim($status))
    || !isset($deadline) || empty(trim($deadline))
    || !isset($description) || empty(trim($description))
    || !isset($bounty) || empty(trim($bounty))
) {

    header("location: add_task.php?error=1");
    exit();

}
            $sql = "INSERT INTO task (namem, status, deadline, description, bounty) 
                    VALUES (:namem, :status, :deadline, :description, :bounty)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':namem', $namem);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':deadline', $deadline);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':bounty', $bounty);
            $stmt->execute();

            echo "New task added successfully.";
            header("Location: manager.php");
            exit();
        }
    }
}
        ?>

        <form method="POST" action="">
            <label for="namem">Task Name:</label>
            <input type="text" id="namem" name="namem" required><br>

            <label for="status">Status:</label>
            <input type="text" id="status" name="status"><br>

            <label for="deadline">Deadline:</label>
            <input type="date" id="deadline" name="deadline"><br>

            <label for="description">Description:</label>
            <textarea id="description" name="description"></textarea><br>

            <label for="bounty">Bounty:</label>
            <input type="number" step="1" id="bounty" name="bounty"><br>

            <input type="submit" value="Add Task">
        </form>

        <?php
        if (isset($_GET['error']) && $_GET['error'] == 1) {
            echo "<p style='color:red;'>Please fill in all required fields.</p>";
        
    }
    ?>
