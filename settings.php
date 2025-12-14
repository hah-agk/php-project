<?php
session_start();
require 'component/opendb.php';

/* =========================
   CHECK ROLE
   ========================= */
if (!isset($_SESSION['UorM'])) {
    header("Location: signup.php");
    exit;
}

$role = $_SESSION['UorM']; // manager | users | admin
$settingsKey = '';

/* =========================
   LOAD SETTINGS INTO SESSION
   ========================= */

if ($role === 'manager') {

    $stmt = $pdo->prepare("SELECT name, email FROM manager WHERE id_m = ?");
    $stmt->execute([$_SESSION['managerID']]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!isset($_SESSION['managerSettings'])) {
        $_SESSION['managerSettings'] = [
            'name'          => $data['name'] ?? 'Manager',
            'email'         => $data['email'] ?? 'manager@gmail.com',
            'language'      => 'en',
            'notifications' => true,
        ];
    }
    $settingsKey = 'managerSettings';

} elseif ($role === 'users') {

    $stmt = $pdo->prepare("SELECT FullName AS name, email FROM users WHERE id_u = ?");
    $stmt->execute([$_SESSION['userID']]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!isset($_SESSION['userSettings'])) {
        $_SESSION['userSettings'] = [
            'name'          => $data['name'] ?? 'User',
            'email'         => $data['email'] ?? 'user@gmail.com',
            'language'      => 'en',
            'notifications' => true,
        ];
    }
    $settingsKey = 'userSettings';

} elseif ($role === 'admin') {

    $stmt = $pdo->query("SELECT name, email FROM admin LIMIT 1");
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!isset($_SESSION['adminSettings'])) {
        $_SESSION['adminSettings'] = [
            'name'          => $data['name'] ?? 'Admin',
            'email'         => $data['email'] ?? 'admin@gmail.com',
            'language'      => 'en',
            'notifications' => true,
        ];
    }
    $settingsKey = 'adminSettings';
}

/* =========================
   FINAL USER OBJECT
   ========================= */
$user = $_SESSION[$settingsKey];

/* =========================
   THEME
   ========================= */
if (!isset($_SESSION['theme'])) {
    $_SESSION['theme'] = 'light';
}
$theme = $_SESSION['theme'];

$success_msg = '';
$error_msg   = '';

/* =========================
   HANDLE FORM SUBMIT
   ========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* Theme */
    $theme = ($_POST['theme'] === 'dark') ? 'dark' : 'light';
    $_SESSION['theme'] = $theme;

    /* Profile */
    $name  = trim($_POST['name']);
    $email = trim($_POST['email']);
    $language = $_POST['language'] ?? 'en';
    $notifications = isset($_POST['notifications']) ? 1 : 0;

    if ($name === '' || $email === '') {
        $error_msg = 'Name and email are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_msg = 'Invalid email format.';
    }

    /* Update profile */
    if ($error_msg === '') {

        if ($role === 'manager') {
            $pdo->prepare(
                "UPDATE manager SET name=?, email=? WHERE id_m=?"
            )->execute([$name, $email, $_SESSION['managerID']]);

        } elseif ($role === 'users') {
            $pdo->prepare(
                "UPDATE users SET FullName=?, email=? WHERE id_u=?"
            )->execute([$name, $email, $_SESSION['userID']]);

        } elseif ($role === 'admin') {
            $pdo->prepare(
                "UPDATE admin SET name=? LIMIT 1"
            )->execute([$name]);
        }

        $_SESSION[$settingsKey]['name'] = $name;
        $_SESSION[$settingsKey]['email'] = $email;
        $_SESSION[$settingsKey]['language'] = $language;
        $_SESSION[$settingsKey]['notifications'] = $notifications;
    }

    /* Change password */
    $current = $_POST['current_password'] ?? '';
    $new     = $_POST['new_password'] ?? '';
    $confirm = $_POST['confirm_password'] ?? '';

    if ($current || $new || $confirm) {

        if ($new !== $confirm) {
            $error_msg = 'Passwords do not match.';
        } elseif (strlen($new) < 6) {
            $error_msg = 'Password must be at least 6 characters.';
        } else {

            if ($role === 'manager') {
                $stmt = $pdo->prepare("SELECT password FROM manager WHERE id_m=?");
                $stmt->execute([$_SESSION['managerID']]);
            } elseif ($role === 'users') {
                $stmt = $pdo->prepare("SELECT password FROM users WHERE id_u=?");
                $stmt->execute([$_SESSION['userID']]);
            } else {
                $stmt = $pdo->query("SELECT password FROM admin LIMIT 1");
            }

            $hashed = $stmt->fetchColumn();

            if (!password_verify($current, $hashed)) {
                $error_msg = 'Current password is incorrect.';
            } else {
                $newHashed = password_hash($new, PASSWORD_BCRYPT);

                if ($role === 'manager') {
                    $pdo->prepare("UPDATE manager SET password=? WHERE id_m=?")
                        ->execute([$newHashed, $_SESSION['managerID']]);
                } elseif ($role === 'users') {
                    $pdo->prepare("UPDATE users SET password=? WHERE id_u=?")
                        ->execute([$newHashed, $_SESSION['userID']]);
                } else {
                    $pdo->prepare("UPDATE admin SET password=? LIMIT 1")
                        ->execute([$newHashed]);
                }
            }
        }
    }

    if ($error_msg === '') {
        $success_msg = 'Settings updated successfully.';
    }
}
?>

<!DOCTYPE html>
<html lang="en" data-theme="<?php echo htmlspecialchars($theme ?? 'light', ENT_QUOTES, 'UTF-8'); ?>">
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
            <?php
 $dashboard = 'signup.php';

if (isset($_SESSION['UorM'])) {
    if ($_SESSION['UorM'] === 'manager') {
        $dashboard = 'manager.php';
    } elseif ($_SESSION['UorM'] === 'user') {
        $dashboard = 'user.php';
    } elseif ($_SESSION['UorM'] === 'admin') {
        $dashboard = 'admin.php';
    }
}
?>

<li>
    <a class="nav-link" href="<?= $dashboard ?>">
        <i class="fas fa-home me-2"></i> Dashboard
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
        // Change theme immediately on the page
        document.documentElement.setAttribute("data-theme", this.value);

        // Save theme via AJAX (POST)
        fetch('update_theme.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: 'theme=' + encodeURIComponent(this.value)
        })
        .then(r => r.json())
        .then(data => {
            // optional: handle response
            console.log('Theme saved:', data);
        })
        .catch(err => console.error('Error:', err));
    });
});
</script>

</body>
</html>