<?php
$databaseUrl = "mysql://root:oNJAWOcqGcPkTNNtLQFbbBKzHWCWWgXS@yamabiko.proxy.rlwy.net:19029/railway";

$db = parse_url($databaseUrl);

$host = $db['host'];      // yamabiko.proxy.rlwy.net
$port = $db['port'];      // 19029
$user = $db['user'];      // root
$pass = $db['pass'];      // كلمة المرور
$name = ltrim($db['path'], '/'); // railway

try {
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$name;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
} catch (PDOException $e) {
    die("❌ Database connection failed: " . $e->getMessage());
}
