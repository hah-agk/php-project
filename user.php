<?php
session_start();

// USER ONLY ACCESS
// if (!isset($_SESSION['LoggedIn']) || $_SESSION['LoggedIn'] !== true) {
//     header("Location: signup.php?errr=7");
//     exit();
// }

// if (!isset($_SESSION['UorM']) || $_SESSION['UorM'] !== "users") {
//     header("Location: user.php");
//     exit();
// }
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: signup.php ");
    exit;
}
// USER DATA
// $userID   = $_SESSION['userID'];
// $userName = $_SESSION['userName'];
// $userMail = $_SESSION['email'];
// $userPhone = $_SESSION['phone'];
// $userAddress = $_SESSION['address'];

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: signup.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en" data-theme="<?php echo htmlspecialchars($theme ?? 'light', ENT_QUOTES, 'UTF-8'); ?>">
<head>
    <meta charset="UTF-8">
    <title>User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap');

        :root {
            --bg: #f8fafc;
            --sidebar-bg: #0f172a;
            --accent: #06b6d4;
            --muted: #64748b;
            --card-bg: #ffffff;
            --shadow: 0 10px 25px rgba(0,0,0,0.08);
            --text-color: #1e293b;
            --content-bg: #f1f5f9;
            --border: #e2e8f0;
            --hover-bg: #f8fafc;
        }

        html[data-theme="dark"] {
            --bg: #0f172a;
            --sidebar-bg: #1e293b;
            --accent: #06b6d4;
            --muted: #94a3b8;
            --card-bg: #1e293b;
            --shadow: 0 10px 25px rgba(0,0,0,0.3);
            --text-color: #f1f5f9;
            --content-bg: #0f172a;
            --border: #334155;
            --hover-bg: #334155;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text-color);
            margin: 0;
            line-height: 1.6;
        }

        .sidebar {
            background: linear-gradient(135deg, var(--sidebar-bg) 0%, #1e293b 100%);
            color: white;
            height: 100vh;
            position: fixed;
            width: 260px;
            top: 0;
            left: 0;
            box-shadow: 4px 0 20px rgba(0,0,0,0.1);
            overflow-y: auto;
        }

        .sidebar h4 {
            text-align: center;
            padding: 24px 0 16px;
            font-weight: 700;
            font-size: 22px;
            letter-spacing: -0.025em;
            color: #f1f5f9;
            margin: 0;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar ul {
            list-style: none;
            padding: 16px 0;
            margin: 0;
        }

        .sidebar .nav-link {
            color: #cbd5e1;
            padding: 14px 20px;
            margin: 4px 12px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
            transition: all 0.2s ease;
            text-decoration: none;
            font-weight: 500;
        }

        .sidebar .nav-link i {
            width: 20px;
            text-align: center;
            font-size: 18px;
        }

        .sidebar .nav-link:hover {
            transform: translateX(4px);
            background: rgba(255,255,255,0.08);
            color: #ffffff;
        }

        .sidebar .nav-link.active{
            background: rgba(45,212,191,0.12);
            color: var(--accent);
            box-shadow: inset 4px 0 0 rgba(45,212,191,0.18);
            font-weight:600;
        }


        /* Responsive */
        @media (max-width: 768px) {

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                box-shadow: none;
            }

        }
    </style>
</head>
<body>

    <!-- Sidebar -->
    <div class="sidebar">
        <h4>
            <i class="fas fa-list me-2"></i> Menu</h4>
        <ul class="nav-links">
            
                <li>
                <a class="nav-link active" href="user.php">
                   <i class="fas fa-home me-2"></i>Dashboard
                </a>
            </li>
       
           <li>
                <a class="nav-link " href="settings.php">
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
</body>
</html>
