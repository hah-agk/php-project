<?php
session_start();

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
    <style>
        :root {
            --bg-light: #ffffff;
            --bg-dark: #121212;
            --text-light: #000000;
            --text-dark: #f5f5f5;
            --accent: #007bff;
            --border: #cccccc;
            --sidebar-bg-light: #f0f0f0;
            --sidebar-bg-dark: #1e1e1e;
        }

        /* Theme */
        html[data-theme="light"] body {
            background: var(--bg-light);
            color: var(--text-light);
        }
        html[data-theme="dark"] body {
            background: var(--bg-dark);
            color: var(--text-dark);
        }

        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        /* Layout with sidebar */
        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 220px;
            padding: 20px 0;
            box-sizing: border-box;
        }

        html[data-theme="light"] .sidebar {
            background: var(--sidebar-bg-light);
            border-right: 1px solid var(--border);
        }

        html[data-theme="dark"] .sidebar {
            background: var(--sidebar-bg-dark);
            border-right: 1px solid #333;
        }

        .sidebar h2 {
            font-size: 18px;
            margin: 0 20px 15px 20px;
        }

        .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .nav-links li {
            margin-bottom: 5px;
        }

        .nav-links a {
            display: block;
            padding: 10px 20px;
            text-decoration: none;
            color: inherit;
        }

        .nav-links a:hover {
            background: rgba(0,0,0,0.05);
        }

        html[data-theme="dark"] .nav-links a:hover {
            background: rgba(255,255,255,0.05);
        }

        .nav-links a.active {
            background: var(--accent);
            color: #fff;
        }

        .content {
            flex: 1;
            padding: 40px 20px;
            box-sizing: border-box;
        }

        .settings-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid var(--border);
            background: rgba(255,255,255,0.02);
        }
        html[data-theme="light"] .settings-container {
            background: #f9f9f9;
        }

        h1, h2 {
            margin-top: 0;
        }
        .section {
            margin-bottom: 30px;
        }
        .field-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        input[type="text"],
        input[type="email"],
        input[type="password"],
        select {
            width: 100%;
            padding: 8px;
            border-radius: 4px;
            border: 1px solid var(--border);
            box-sizing: border-box;
        }
        .inline-field {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .inline-field input[type="radio"] {
            width: auto;
        }
        .btn-save {
            padding: 10px 20px;
            background: var(--accent);
            color: #fff;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .btn-save:hover {
            opacity: 0.9;
        }
        .message {
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        .message.success {
            background: #d4edda;
            color: #155724;
        }
        .message.error {
            background: #f8d7da;
            color: #721c24;
        }
        html[data-theme="dark"] .message.success {
            background: #1e4030;
        }
        html[data-theme="dark"] .message.error {
            background: #4a1f24;
        }
    </style>
</head>
<body>
<div class="layout">

    <!-- Sidebar -->
    <aside class="sidebar">
        <h2>Menu</h2>
        <ul class="nav-links">
            <!-- Change hrefs to your real pages -->
            <li><a href="index.php">Home</a></li>
            <li><a href="users.php">Users</a></li>
            <li><a href="reports.php">Reports</a></li>
            <li><a href="settings.php" class="active">Settings</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </aside>

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

            <form method="post" action="">

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
</body>
</html>