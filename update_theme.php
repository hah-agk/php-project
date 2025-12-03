<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['theme'])) {
    $theme = $_POST['theme'] === 'dark' ? 'dark' : 'light';
    $_SESSION['theme'] = $theme;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>
