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
    $namem       = $_POST['namem'] ?? '';
    $status      = $_POST['status'] ?? '';
    $deadline    = $_POST['deadline'] ?? '';
    $description = $_POST['description'] ?? '';
    $bounty      = $_POST['bounty'] ?? '';

    if (!isset($namem) || empty(trim($namem))
        || !isset($status) || empty(trim($status))
        || !isset($deadline) || empty(trim($deadline))
        || !isset($description) || empty(trim($description))
        || $bounty === '' || $bounty < 0
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
    $stmt->bindParam(':bounty', $bounty, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: manager.php");
    exit();
}
if (isset($_GET['Ntask'])) {
    if ($_GET['Ntask'] == 1) {}}
?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Task</title>

        <html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Task</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
        }

        .page-wrapper {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .task-form-card {
            background: #ffffff;
            width: 100%;
            max-width: 500px;
            padding: 24px 28px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(15, 23, 42, 0.12);
        }

        .task-form-card h2 {
            margin-top: 0;
            margin-bottom: 16px;
            font-size: 24px;
            color: #111827;
            text-align: center;
        }

        .task-form-card p.subtitle {
            margin-top: 0;
            margin-bottom: 20px;
            text-align: center;
            color: #6b7280;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 14px;
        }

        .form-group label {
            display: block;
            margin-bottom: 4px;
            font-size: 14px;
            color: #374151;
        }

        .form-group input[type="text"],
        .form-group input[type="date"],
        .form-group input[type="number"],
        .form-group textarea,
        .form-group select {
            width: 100%;
            padding: 8px 10px;
            border-radius: 6px;
            border: 1px solid #d1d5db;
            font-size: 14px;
            box-sizing: border-box;
        }

        .form-group textarea {
            min-height: 80px;
            resize: vertical;
        }

        .form-group input:focus,
        .form-group textarea:focus,
        .form-group select:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.3);
        }

        .error-msg {
            margin-top: 8px;
            margin-bottom: 0;
            color: #b91c1c;
            background: #fee2e2;
            border: 1px solid #fecaca;
            padding: 8px 10px;
            border-radius: 6px;
            font-size: 13px;
        }

        .btn-submit {
            width: 100%;
            padding: 10px 16px;
            border-radius: 8px;
            border: none;
            background: #3b82f6;
            color: #ffffff;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 6px;
        }

        .btn-submit:hover {
            background: #2563eb;
        }
    </style>
</head>
<body>
<div class="page-wrapper">
    <div class="task-form-card">
        <h2>Add New Task</h2>
        <p class="subtitle">Fill in the details below to create a new task.</p>

        <form method="POST" action="">
            <div class="form-group">
                <label for="namem">Task Name</label>
                <input type="text" id="namem" name="namem" required>
            </div>

            <div class="form-group">
                <label for="status">Status</label>
                <select id="status" name="status" required>
                    <option value="">Select status</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In progress</option>
                    <option value="done">Done</option>
                </select>
            </div>

            <div class="form-group">
                <label for="deadline">Deadline</label>
                <input type="date" id="deadline" name="deadline" required>
            </div>

            <div class="form-group">
                <label for="bounty">Bounty</label>
                <input type="number" step="1" id="bounty" name="bounty" min="0" required>
            </div>

            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" required></textarea>
            </div>

            <button type="submit" class="btn-submit">Add Task</button>
        </form>

        <?php
        if (isset($_GET['error']) && $_GET['error'] == 1) {
            echo "<p class='error-msg'>Please fill in all required fields (bounty must be ≥ 0).</p>";
        }
        ?>
    </div>
</div>
</body>
</html>