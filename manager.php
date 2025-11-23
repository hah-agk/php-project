<?php
session_start();

// Simple file-based authentication (no database required)
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
    'revenue' => '$12,847'
];


// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php ");
    exit;
}

// Check if user is logged in
$is_logged_in = isset($_SESSION['user']);
$current_user = $_SESSION['user'] ?? '';
$user_role = $_SESSION['role'] ?? '';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
<link rel="stylesheet"  href="../css/man.css">
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar" style="width: 250px;">
        <div class="mp">
            <h4 class="text-center mb-4">
                <i class="fas fa-tachometer-alt me-2"></i>Maneger Panel
            </h4>
        </div>
        <ul class="nav flex-column">
            <li>
                <a class="nav-link active" href="manager.php">
                    <i class="fas fa-home me-2"></i>Dashboard
                </a>
            </li>
            
            <li >
                <a class="nav-link" href="analyticspage.php">
                    <i class="fas fa-chart-bar me-2"></i>Analytics
                </a>
            </li>
            <li>
                <a class="nav-link" href="#">
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
        <!-- Navbar -->
        <nav class="navbar ">
            <div class="container-fluid">
                <span >Dashboard</span>
                <div >
                    <span >
                        Welcome, <strong><?= htmlspecialchars($current_user) ?></strong>
                        <span class="badge bg-<?= $user_role === 'admin' ? 'danger' : 'primary' ?> ms-1">
                            <?= ucfirst($user_role) ?>
                        </span>
                    </span>
                    <a href="?logout=1">
                        <i class="fas fa-sign-out-alt me-1"></i>Logout
                    </a>
                </div>
            </div>
        </nav>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card stat-card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2><?= $stats['total_users'] ?></h2>
                                <p style="color: black;">Total Users</p>
                            </div>
                            <i class="fas fa-users fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2><?= $stats['active_users'] ?></h2>
                                <p style="color: black;">Active Users</p>
                            </div>
                            <i class="fas fa-user-check fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2><?= $stats['new_today'] ?></h2>
                                <p style="color: black;">New Today</p>
                            </div>
                            <i class="fas fa-user-plus fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card stat-card bg-info text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h2><?= $stats['revenue'] ?></h2>
                                <p style="color: black;">Revenue</p>
                            </div>
                            <i class="fas fa-dollar-sign fa-3x opacity-50"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Users Table -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-users me-2"></i>User Management
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sample_users as $user): ?>
                            <tr>
                                <td><?= $user['id'] ?></td>
                                <td><?= htmlspecialchars($user['name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $user['role'] === 'Admin' ? 'danger' : 
                                        ($user['role'] === 'Moderator' ? 'warning' : 'secondary')
                                    ?>">
                                        <?= $user['role'] ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $user['status'] === 'Active' ? 'success' : 'secondary' ?>">
                                        <?= $user['status'] ?>
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
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
                            <button class="btn btn-outline-primary">
                                <i class="fas fa-plus me-2"></i>Add New User
                            </button>
                                 <button class="btn btn-outline-primary">
                                <i class="fas fa-plus me-2"></i>Add New task
                            </button>
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
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">System Info</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <small class="text-muted">Server Time</small>
                                <p><?= date('Y-m-d H:i:s') ?></p>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">PHP Version</small>
                                <p><?= PHP_VERSION ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

 
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
