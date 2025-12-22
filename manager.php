<?php
require 'component/opendb.php';
require 'component/function.php';
$theme = $_SESSION['theme'] ?? 'light';




// Simple file-based authentication (no database required)
// if (!isset($_SESSION['LoggedIn']) || $_SESSION['LoggedIn'] !== true) {
//     header("Location: signup.php?errr=7");
//     exit();
// }

// if (!isset($_SESSION['UorM']) || $_SESSION['UorM'] !== "users") {
//     header("Location: manager.php");
//     exit();
// }

 $mID   =  $_SESSION['managerID'];
 $tasks=show_task($pdo ,$mID);
 $Ctasks=show_task_completed($pdo ,$mID);

 if($mID){
    $stmt = $pdo->prepare("SELECT salary FROM manager WHERE id_M = ?");
    $stmt->execute([$mID]);
    $Msalary = $stmt->fetch(PDO::FETCH_ASSOC);
 }
$users = [
    'admin' => password_hash('admin123', PASSWORD_DEFAULT),
    'user' => password_hash('user123', PASSWORD_DEFAULT)
];
// Sample data
$sample_users = [
    ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com', 'role' => 'Admin', 'status' => 'Active'],
    ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com', 'role' => 'User', 'status' => 'Active'],
    ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com', 'role' => 'User', 'status' => 'Inactive'],
    ['id' => 4, 'name' => 'Alice Brown', 'email' => 'alice@example.com', 'role' => 'Moderator', 'status' => 'Active'],
];
 
$stats = [
    'total_users' => 1542,
    'active_users' => 1247,
    'new_today' => 23,
    'revenue' => $Msalary ? $Msalary['salary'] : 0,
];


// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: signup.php ");
    exit;
}
// if(isset($_GET['Ntask'])){
//     header("Location: add_task.php?Ntask=1");
//     exit();
// }
// Check if user is logged in
$is_logged_in = isset($_SESSION['user']);
$current_user = $_SESSION['user'] ?? '';
$user_role = $_SESSION['role'] ?? '';

// Fetch user name from database
$sql = "SELECT name FROM manager WHERE id_m = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$mID]);
$managerName = $stmt->fetchColumn();

$welcomeText = "Welcome, $managerName! 👋";
if (isset($_COOKIE['login']) && $_COOKIE['login'] == true) {
    $h1 = "Welcome back, $managerName! 👋";
    $p= "Here's what's happening with your tasks today.";
}
if (isset($_COOKIE['signup']) && $_COOKIE['signup'] == true) {
        $h1 = "Welcome, $managerName! 👋";
        $p= "Your account has been created successfully.";
}

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: signup.php");
    exit;
}
// balance of manager
$sql = "SELECT salary FROM manager WHERE id_m = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$mID]);
$balance = $stmt->fetchColumn();

// Fetch total tasks for the manager
$sql = "SELECT COUNT(*) FROM task WHERE manager_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$mID]);
$totalTasks = $stmt->fetchColumn();

// Fetch completed tasks for the manager
$sql = "SELECT COUNT(*) FROM task 
        WHERE manager_id = ? AND status = 'completed'";
$stmt = $pdo->prepare($sql);
$stmt->execute([$mID]);
$completedTasks = $stmt->fetchColumn();

// ftch the number of tasks the user send him to manager
$sql = "SELECT COUNT(*) FROM task WHERE manager_id = ? and status = 'waiting_review'";
$stmt = $pdo->prepare($sql);
$stmt->execute([$mID]);
$inReviewTasks = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?= $theme ?>">
<head>
  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
  <link href="css/man.css" rel="stylesheet">

</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar" style="width: 250px;">
        <div class="p-3">
            <div class="text-center mb-4">
             
                <h4 class="text-center mb-4">
                    <i class="fas fa-tachometer-alt me-2"></i>Manager Panel
                </h4>
            </div>
        </div>
        <ul class="nav flex-column">
            <li>
                <a class="nav-link active" href="manager.php">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>
            </li>
            
     
           <li>
                <a class="nav-link" href="balance.php">
                  <i class="fa-solid fa-wallet me-2"></i>Balance
                 </a>
            </li>
            <li>
                <a class="nav-link" href="settings.php">
                    <i class="fas fa-cog me-2"></i>Settings
                </a>
            </li>
            <li>
                <a class="nav-link" href="?logout=1">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        
       
             <div class="welcome-header">
            <h1><?= $h1 ?></h1>
            <p><?= $p ?></p>
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
                <div class="stat-card-label">your task</div>
            </div>

            <div class="stat-card">
                <div class="stat-card-header">
                    <span class="stat-card-title">balance</span>
                    <div class="stat-card-icon success">
                      <i class="fa-solid fa-magnifying-glass-dollar"></i>
                    </div>
                </div>
                <div class="stat-card-value"><?= $balance?></div>
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
    <a href="review_tasks.php" style="text-decoration: none; color: inherit;">
            <div class="stat-card">
                <div class="stat-card-header">
                    <span class="stat-card-title">Review tasks</span>
                    <div class="stat-card-icon warning">
                        <i class="fas fa-bell"></i>
                    </div>
                </div>
                <div class="stat-card-value"><?= $inReviewTasks ?></div>
                <div class="stat-card-label">Unread messages</div>
            </div>
        </div>
        </a>
    

          <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fa-solid fa-list-check"></i></i> task List not completed
                </h5>
            </div>




            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <div class="t">
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>status</th>
                                <th>start_time</th>
                                <th>end_time</th>
                                <th>description</th>
                                <th>bounty</th>
                                <th>user_id</th>
                                <th>manager_id</th>
                                <th>Actions</th>
                            </tr>
                            </div>
                        </thead>
                        <tbody>
                        <?php
                                 if (!empty($tasks)) { 
                                    foreach ($tasks as $task) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($task['id_T']) ?></td>
                                    <td><?= htmlspecialchars($task['Title_T']) ?></td>
                                    <td><?= htmlspecialchars($task['status']) ?></td>
                                    <td><?= htmlspecialchars($task['Start_Time']) ?></td>
                                    <td><?= htmlspecialchars($task['End_Time']) ?></td>
                                    <td><?= htmlspecialchars($task['description']) ?></td>
                                    <td><?= htmlspecialchars($task['bounty']) ?></td>
                                    <td><?= empty($task['user_id']) ? 'no user accept the task' : htmlspecialchars($task['user_id']) ?></td>

                                    <td><?= htmlspecialchars($task['manager_id']) ?></td>
                                    <td>
                                    <td>
                                          <a class="btn btn-sm btn-outline-primary" 
                                           href ="edit_delete_task.php?edit=1&id=<?= $task['id_T'] ?>">
                                          <i class="fas fa-edit"></i>
                                            </a>
                                            <a class="btn btn-sm btn-outline-danger" 
                                            href="edit_delete_task.php?delete=1&id=<?= $task['id_T'] ?>">
                                          <i class="fas fa-trash"></i>
                                            </a>
                                </tr>
                        <?php 
                                    }
                                }else {
                                    echo "<tr><td colspan='7'>No tasks found.</td></tr>";
                                } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>



       <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fa-solid fa-list-check"></i></i> task List Completed
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                        <div class="t">
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>status</th>
                                <th>start_time</th>
                                <th>end_time</th>
                                <th>description</th>
                                <th>bounty</th>
                                <th>user_id</th>
                                <th>manager_id</th>
                            </tr>
                            </div>
                        </thead>
                        <tbody>
                        <?php
                                 if (!empty($Ctasks)) { 
                                    foreach ($Ctasks as $task) { ?>
                                <tr>
                                    <td><?= htmlspecialchars($task['id_T']) ?></td>
                                    <td><?= htmlspecialchars($task['Title_T']) ?></td>
                                    <td><?= htmlspecialchars($task['status']) ?></td>
                                    <td><?= htmlspecialchars($task['Start_Time']) ?></td>
                                    <td><?= htmlspecialchars($task['End_Time']) ?></td>
                                    <td><?= htmlspecialchars($task['description']) ?></td>
                                    <td><?= htmlspecialchars($task['bounty']) ?></td>
                                    <td><?= empty($task['user_id']) ? 'no user accept the task' : htmlspecialchars($task['user_id']) ?></td>

                                    <td><?= htmlspecialchars($task['manager_id']) ?></td>
                                </tr>
                        <?php 
                                    }
                                }else {
                                    echo "<tr><td colspan='7'>No tasks found.</td></tr>";
                                } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        


        
        
        <!-- Quick Actions -->
        <div class="row mt-4">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">Quick Actions</h6>
                    </div>
                    
                    <div class="card-body">
                        
                        <div class="d-grid gap-2">
                                 
                    

                        <a href="add_task.php?Ntask=1" class="btn btn-outline-primary">
                           <i class="fas fa-plus me-2"></i> Add New Task
                        </a>
                            <button class="btn btn-outline-success">
                                <i class="fas fa-download me-2"></i>Export Data
                            </button>
                            <button class="btn btn-outline-info">
                                <i class="fas fa-cog me-2"></i>System Settings
                            </button>
                        </div>
                    </div>
                </div>
            </div>
      


    
 
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>