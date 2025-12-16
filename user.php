<?php
session_start();
require 'component/opendb.php';
// USER ONLY ACCESS
// if (!isset($_SESSION['LoggedIn']) || $_SESSION['LoggedIn'] !== true) {
//     header("Location: signup.php?errr=7");
//     exit();
// }

// if (!isset($_SESSION['UorM']) || $_SESSION['UorM'] !== "users") {
//     header("Location: user.php");
//     exit();
// }
$theme = $_SESSION['theme'] ?? 'light';

if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: signup.php ");
    exit;
}
// USER DATA
$userID   = $_SESSION['userID'];
// $userName = $_SESSION['userName'];
// $userMail = $_SESSION['email'];
// $userPhone = $_SESSION['phone'];
// $userAddress = $_SESSION['address'];

// Fetch user name from database
$sql = "SELECT FullName FROM users WHERE id_u = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userID]);
$userName = $stmt->fetchColumn();

$welcomeText = "Welcome, $userName! 👋";
if (isset($_COOKIE['login']) && $_COOKIE['login'] == true) {
    $h1 = "Welcome back, $userName! 👋";
    $p= "Here's what's happening with your tasks today.";
}
if (isset($_COOKIE['signup']) && $_COOKIE['signup'] == true) {
        $h1 = "Welcome, $userName! 👋";
        $p= "Your account has been created successfully.";
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: signup.php");
    exit;
}
// balance of user
$sql = "SELECT salary FROM users WHERE id_u = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userID]);
$balance = $stmt->fetchColumn();

// Fetch total tasks for the user
$sql = "SELECT COUNT(*) FROM task WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userID]);   
$totalTasks = $stmt->fetchColumn();

// Fetch completed tasks for the user
$sql = "SELECT COUNT(*) FROM task 
        WHERE user_id = ? AND status = 'completed'";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userID]);
$completedTasks = $stmt->fetchColumn();

// Fetch available tasks (not assigned to any user)
$sql = "SELECT COUNT(*) FROM task WHERE user_id IS NULL";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$availableTasks = $stmt->fetchColumn();

// Fetch total skills for the user
$sql = "SELECT COUNT(*) FROM skills WHERE user_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userID]);
$totalSkills = $stmt->fetchColumn();


?>
<!DOCTYPE html>
<html lang="en" data-theme="<?= $theme ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="css/user.css">
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="p-3">
            <h4>
                <i class="fas fa-th-large"></i> Dashboard
            </h4>
        </div>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active" href="user.php">
                    <i class="fas fa-home me-2"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href= "settings.php">
                    <i class="fas fa-cog me-2"></i>
                    Settings
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="user.php?logout=1">
                    <i class="fas fa-sign-out-alt me-2"></i>
                    Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Welcome Header -->
        <div class="welcome-header">
            <h1><?php echo $h1; ?> </h1>
            <p><?php echo $p; ?></p>
        </div>


        <!-- Stats Row -->
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-card-header">
                    <span class="stat-card-title">Total Tasks</span>
                    <div class="stat-card-icon primary">
                        <i class="fas fa-tasks"></i>
                    </div>
                </div>
                <div class="stat-card-value"><?= $totalTasks ?></div>
                <div class="stat-card-label">Active tasks</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <span class="stat-card-title">balance</span>
                    <div class="stat-card-icon success">
                      <i class="fa-solid fa-magnifying-glass-dollar"></i>
                    </div>
                </div>
                <div class="stat-card-value"><?= $balance ?></div>
                <div class="stat-card-label">In progress</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <span class="stat-card-title">Completed</span>
                    <div class="stat-card-icon info">
                        <i class="fas fa-check-circle"></i>
                    </div>
                </div>
                <div class="stat-card-value"><?= $completedTasks ?></div>
                <div class="stat-card-label">This month</div>
            </div>

           <a href="user/show_task.php" style="text-decoration: none; color: inherit;">
    <div class="stat-card">
        <div class="stat-card-header">
            <span class="stat-card-title">Available Tasks</span>
            <div class="stat-card-icon warning">
                <i class="fas fa-briefcase"></i>
            </div>
        </div>

        <div class="stat-card-value">
            <?= $availableTasks ?? 0 ?>
        </div>

        <div class="stat-card-label">
            Browse tasks from managers
        </div>
    </div>
</a>
        </div>

        <!-- Quick Actions -->
        <div class="quick-actions">
            <h2>
                <i class="fas fa-bolt"></i>
                Quick Actions
            </h2>
            <div class="action-buttons">
           
                <a href="user/add_skill.php" class="action-btn ">
                    <i class="fas fa-plus"></i>
                    add skills 
                </a>
                <button class="action-btn secondary">
                    <i class="fa-solid fa-pen-to-square"></i>
                    number of skills: <?= $totalSkills ?>
                </button>
                <a href="user/show_skill.php" class="action-btn secondary">
                     <i class="fas fa-tasks"></i>
                    Show Skills
                </a>
                <a href="user/my_task.php" class="action-btn secondary">
                <i class="fas fa-clipboard-list"></i>
                 My Tasks
                </a>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="recent-activity">
            <h2>
                <i class="fas fa-clock"></i>
                Recent Activity
            </h2>
            <ul class="activity-list">
                <li class="activity-item">
                    <div class="activity-icon success">
                        <i class="fas fa-check"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Task completed: Update user interface</div>
                        <div class="activity-time">2 hours ago</div>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon primary">
                        <i class="fas fa-comment"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">New comment on "Website Redesign"</div>
                        <div class="activity-time">5 hours ago</div>
                    </div>
                </li>
                <li class="activity-item">
                    <div class="activity-icon warning">
                        <i class="fas fa-exclamation-triangle"></i>
                    </div>
                    <div class="activity-content">
                        <div class="activity-title">Deadline approaching: Mobile App Development</div>
                        <div class="activity-time">1 day ago</div>
                    </div>
                </li>
               
                 
                </li>
            </ul>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
