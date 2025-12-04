<?php
$server = "localhost";
$username = "root";
$password = "";

try {
   
    $pdo = new PDO("mysql:host=$server", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS project 
                CHARACTER SET utf8mb4 
                COLLATE utf8mb4_general_ci");

    echo "✔ Database 'project' checked/created.<br>";

    $pdo = new PDO("mysql:host=$server;dbname=project", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

   
    $sql = "
    CREATE TABLE IF NOT EXISTS users (
        id INT(11) NOT NULL AUTO_INCREMENT,
        name VARCHAR(50) NOT NULL,
        phone INT(11) NOT NULL,
        addres VARCHAR(100) NOT NULL,
        email VARCHAR(50) NOT NULL,
        password VARCHAR(120) NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($sql);

    echo "✔ Table 'users' checked/created.<br>";


    $sql = "
    CREATE TABLE IF NOT EXISTS manager (
        id INT(11) NOT NULL AUTO_INCREMENT,
        name VARCHAR(50) NOT NULL,
        phone INT(11) NOT NULL,
        addres VARCHAR(100) NOT NULL,
        email VARCHAR(50) NOT NULL,
        password VARCHAR(120) NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($sql);

    echo "✔ Table 'manager' checked/created.<br>";

    $sql = "
    CREATE TABLE IF NOT EXISTS task (
        idm INT AUTO_INCREMENT PRIMARY KEY,
        namem VARCHAR(255) NOT NULL,
        status VARCHAR(100),
        deadline DATE,
        description TEXT,
        bounty DECIMAL(10,2)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($sql);

    echo "✔ Table 'task' checked/created.<br>";

   
    $check = $pdo->prepare("SELECT id FROM manager WHERE email = 'm1@gmail.com'");
    $check->execute();

    if ($check->rowCount() == 0) {
        $passwordHash = password_hash("123456", PASSWORD_BCRYPT);

        $sql = "INSERT INTO manager (name, phone, addres, email, password)
                VALUES ('m1', '81825465', 'beirut', 'm1@gmail.com', '$passwordHash')";
        $pdo->exec($sql);

        echo "✔ Default manager 'm1' added.<br>";
    } else {
        echo "✔ Default manager 'm1' already exists (skipped).<br>";
    }

    $checkCol = $pdo->query("SHOW COLUMNS FROM manager LIKE 'salary'");
    if ($checkCol->rowCount() > 0) {
        $pdo->exec("ALTER TABLE manager DROP COLUMN salary");
        echo "✔ Column 'salary' removed from manager.<br>";
    } else {
        echo "✔ Column 'salary' already removed (skipped).<br>";
    }

    $checkUnique = $pdo->query("SHOW INDEX FROM users WHERE Key_name = 'unique_user_email'");
    if ($checkUnique->rowCount() == 0) {
        $pdo->exec("ALTER TABLE users ADD CONSTRAINT unique_user_email UNIQUE(email)");
        echo "✔ UNIQUE added to users.email<br>";
    } else {
        echo "✔ UNIQUE on users.email already exists (skipped).<br>";
    }

    $checkUnique = $pdo->query("SHOW INDEX FROM manager WHERE Key_name = 'unique_manager_email'");
    if ($checkUnique->rowCount() == 0) {
        $pdo->exec("ALTER TABLE manager ADD CONSTRAINT unique_manager_email UNIQUE(email)");
        echo "✔ UNIQUE added to manager.email<br>";
    } else {
        echo "✔ UNIQUE on manager.email already exists (skipped).<br>";
    }

    echo "<br>🎉 All operations completed successfully.";

    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
