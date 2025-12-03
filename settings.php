<?php
session_start();

// Handle logout
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: signup.php");
    exit;
}

// ---- Mock "logged in user" data (replace with real DB logic) ----
if (!isset($_SESSION['user'])) {
    $_SESSION['user'] = [
        'name'        => 'John Doe',
        'email'       => 'john@example.com',
        'language'    => 'en',
        'notifications' => true,
        // Normally you NEVER store plain passwords in session or DB
        'password_hash' => password_hash('password123', PASSWORD_DEFAULT),
    ];
}

// Theme: light (default) or dark
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}

$user  = $_SESSION['user'];
$theme = $_SESSION['theme'];

$success_msg = '';
$error_msg   = '';

// ---- Handle form submission ----
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Theme
    $theme = ($_POST['theme'] ?? 'light') === 'dark' ? 'dark' : 'light';
    $_SESSION['theme'] = $theme;

    // Profile
    $name  = trim($_POST['name'] ?? $user['name']);
    $email = trim($_POST['email'] ?? $user['email']);
    $language = $_POST['language'] ?? $user['language'];
    $notifications = isset($_POST['notifications']);

    if ($name === '' || $email === '') {
        $error_msg = 'Name and email are required.';
    } else {
        $user['name']          = $name;
        $user['email']         = $email;
        $user['language']      = $language;
        $user['notifications'] = $notifications;
    }

    // Password change (optional)
    $current_password = $_POST['current_password'] ?? '';
    $new_password     = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if ($current_password !== '' || $new_password !== '' || $confirm_password !== '') {
        // User wants to change password
        if (!password_verify($current_password, $user['password_hash'])) {
            $error_msg = 'Current password is incorrect.';
        } elseif ($new_password === '' || strlen($new_password) < 6) {
            $error_msg = 'New password must be at least 6 characters.';
        } elseif ($new_password !== $confirm_password) {
            $error_msg = 'New password and confirmation do not match.';
        } else {
            $user['password_hash'] = password_hash($new_password, PASSWORD_DEFAULT);
        }
    }

    if ($error_msg === '') {
        $_SESSION['user'] = $user;
        $success_msg = 'Settings updated successfully.';
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="<?php echo htmlspecialchars($theme, ENT_QUOTES, 'UTF-8'); ?>">
<head>
    <meta charset="UTF-8">
    <title>Settings</title>
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

        .sidebar .nav-link.active {
            background: rgba(6,182,212,0.15);
            color: var(--accent);
            box-shadow: inset 0 0 0 1px rgba(6,182,212,0.3);
            font-weight: 600;
        }

        .content {
            margin-left: 260px;
            padding: 32px;
            background: var(--content-bg);
            min-height: 100vh;
        }

        .settings-container {
            max-width: 800px;
            margin: 0 auto;
            background: transparent;
        }

        .settings-container h1 {
            font-weight: 800;
            font-size: 32px;
            color: var(--text-color);
            margin-bottom: 32px;
            letter-spacing: -0.025em;
        }

        .message {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-weight: 500;
            border-left: 4px solid;
        }

        .message.success {
            background: linear-gradient(90deg, #dcfce7, #bbf7d0);
            color: #166534;
            border-left-color: #16a34a;
        }

        .message.error {
            background: linear-gradient(90deg, #fef2f2, #fee2e2);
            color: #991b1b;
            border-left-color: #dc2626;
        }

        .form {
            width: 100%;
            background: transparent;
            padding: 0;
            box-shadow: none;
        }

        .section {
            background: var(--card-bg);
            border-radius: 16px;
            padding: 32px;
            margin-bottom: 32px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .section:hover {
            transform: translateY(-2px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }

        .section h2 {
            font-size: 22px;
            font-weight: 700;
            color: var(--text-color);
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 12px;
            letter-spacing: -0.025em;
        }

        .section h2 i {
            color: var(--accent);
            font-size: 24px;
        }

        .field-group {
            margin-bottom: 24px;
        }

        .field-group label {
            display: block;
            font-weight: 600;
            color: var(--text-color);
            margin-bottom: 10px;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .field-group input[type="text"],
        .field-group input[type="email"],
        .field-group input[type="password"],
        .field-group select {
            width: 100%;
            padding: 14px 18px;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-size: 16px;
            background: var(--card-bg);
            color: var(--text-color);
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .field-group input:focus,
        .field-group select:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 0 4px rgba(6,182,212,0.1);
            background: var(--hover-bg);
        }

        .inline-field {
            display: flex;
            gap: 24px;
            align-items: center;
        }

        .inline-field label {
            display: flex;
            align-items: center;
            gap: 10px;
            font-weight: 500;
            cursor: pointer;
            padding: 12px 16px;
            border-radius: 8px;
            transition: background 0.2s ease;
        }

        .inline-field label:hover {
            background: var(--hover-bg);
        }

        .inline-field input[type="radio"] {
            margin: 0;
            width: 18px;
            height: 18px;
        }

        .btn-save {
            background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%);
            color: white;
            border: none;
            padding: 16px 32px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: 0 8px 20px rgba(6,182,212,0.3);
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .btn-save:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(6,182,212,0.4);
        }

        .btn-save:active {
            transform: translateY(-1px);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .content {
                margin-left: 0;
                padding: 20px;
            }

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                box-shadow: none;
            }

            .settings-container h1 {
                font-size: 28px;
            }

            .section {
                padding: 20px;
            }

            .inline-field {
                flex-direction: column;
                align-items: flex-start;
                gap: 16px;
            }

            .btn-save {
                width: 100%;
            }
        }
    </style>














</head>
<body>
<div class="layout">

    <!-- Sidebar -->
    <div class="sidebar">
        <h4>
            <i class="fas fa-list me-2"></i> Menu</h4>
        <ul class="nav-links">
            
                <li>
                <a class="nav-link" href="manager.php">
                   <i class="fas fa-home me-2"></i>Dashboard
                </a>
            </li>
       
           <li>
                <a class="nav-link active" href="settings.php">
                    <i class="fas fa-cog me-2"></i>Settings
                </a>
            </li>
            <li>
                <a class="nav-link" href="?logout=1">
                    <i class="fas fa-sign-out-alt me-2"></i>Logout
                </a>
            </li>
</div>

    <!-- Main content -->
    <main class="content">
        <div class="settings-container">
            <h1>Settings</h1>

            <?php if ($success_msg): ?>
                <div class="message success"><?php echo htmlspecialchars($success_msg, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <?php if ($error_msg): ?>
                <div class="message error"><?php echo htmlspecialchars($error_msg, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <form method="post" action="" class="form">

                <!-- Appearance / Theme -->
                <div class="section">
                    <h2>Appearance</h2>
                    <div class="field-group">
                        <label>Theme</label>
                        <div class="inline-field">
                            <label>
                                <input type="radio" name="theme" value="light"
                                    <?php echo $theme === 'light' ? 'checked' : ''; ?>>
                                Light
                            </label>
                            <label>
                                <input type="radio" name="theme" value="dark"
                                    <?php echo $theme === 'dark' ? 'checked' : ''; ?>>
                                Dark
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Profile -->
                <div class="section">
                    <h2>Profile</h2>
                    <div class="field-group">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name"
                               value="<?php echo htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                    <div class="field-group">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email"
                               value="<?php echo htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8'); ?>">
                    </div>
                </div>

                <!-- Password -->
                <div class="section">
                    <h2>Change Password</h2>
                    <div class="field-group">
                        <label for="current_password">Current password</label>
                        <input type="password" name="current_password" id="current_password">
                    </div>
                    <div class="field-group">
                        <label for="new_password">New password</label>
                        <input type="password" name="new_password" id="new_password">
                    </div>
                    <div class="field-group">
                        <label for="confirm_password">Confirm new password</label>
                        <input type="password" name="confirm_password" id="confirm_password">
                    </div>
                </div>

                <!-- Other common settings -->
                <div class="section">
                    <h2>Preferences</h2>
                    <div class="field-group">
                        <label for="language">Language</label>
                        <select name="language" id="language">
                            <option value="en" <?php echo $user['language'] === 'en' ? 'selected' : ''; ?>>English</option>
                            <option value="ar" <?php echo $user['language'] === 'ar' ? 'selected' : ''; ?>>Arabic</option>
                            <option value="fr" <?php echo $user['language'] === 'fr' ? 'selected' : ''; ?>>French</option>
                        </select>
                    </div>
                    <div class="field-group">
                        <label>
                            <input type="checkbox" name="notifications"
                                <?php echo $user['notifications'] ? 'checked' : ''; ?>>
                            Receive email notifications
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn-save">Save changes</button>
            </form>
        </div>
    </main>
</div>

<script>
document.querySelectorAll('input[name="theme"]').forEach(option => {
    option.addEventListener("change", function () {
        document.documentElement.setAttribute("data-theme", this.value);
    });
});
</script>

</body>
</html>