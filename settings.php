<?php
session_start();

if (!isset($_SESSION['LoggedIn']) || $_SESSION['LoggedIn'] !== true) {
    header("Location: signup.php");
    exit;
}
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
    <link rel="stylesheet" href="css/settings.css">
</head>
<body>
<div class="layout">

    <!-- Sidebar -->
    <div class="sidebar">
        <h4>
            <i class="fas fa-list me-2"></i> Menu</h4>
        <ul class="nav-links">
            
                <li>
                <a class="nav-link" <?php if($_SESSION["UorM"] == "manager") { echo 'href="manager.php"'; } else { echo 'href="user.php"'; } ?>>
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
        // Save theme via AJAX
        fetch('update_theme.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'theme=' + encodeURIComponent(this.value)
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                console.log('Theme updated successfully');
            } else {
                console.error('Failed to update theme');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });
    });
});
</script>

</body>
</html>