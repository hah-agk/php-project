<?php
$server = "localhost";
$username = "root";
$password = "";

try {
   
    $pdo = new PDO("mysql:host=$server", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE DATABASE IF NOT EXISTS task_management_db
                CHARACTER SET utf8mb4 
                COLLATE utf8mb4_general_ci");

    echo "✔ Database 'task_management_db' checked/created.<br>";

    $pdo = new PDO("mysql:host=$server;dbname=task_management_db", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
 
    $sql = "
    CREATE TABLE IF NOT EXISTS users (
        id_u INT(11) NOT NULL AUTO_INCREMENT,
        FullName VARCHAR(50) NOT NULL,
        phone INT(11) NOT NULL,
        address VARCHAR(100) NOT NULL,
        salary DECIMAL(10,2) NULL,
        email VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(120) NOT NULL,
        PRIMARY KEY (id_u)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($sql);

    echo "✔ Table 'users' checked/created.<br>";

     $sql = "
    CREATE TABLE IF NOT EXISTS manager (
        id_m INT(11) NOT NULL AUTO_INCREMENT,
        name VARCHAR(50) NOT NULL,
        phone INT(11) NOT NULL,
        address VARCHAR(100) NOT NULL,
        salary DECIMAL(10,2) NULL,
        email VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(120) NOT NULL,
        PRIMARY KEY (id_m)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($sql);

    echo "✔ Table 'manager' checked/created.<br>";

    $sql = "
    CREATE TABLE IF NOT EXISTS task (
        id_T INT AUTO_INCREMENT PRIMARY KEY,
        Title_T VARCHAR(255) NOT NULL,
        status enum('pending', 'in_progress', 'completed') DEFAULT 'pending',
        description TEXT,
        bounty DECIMAL(10,2),
        Start_Time DATETIME DEFAULT CURRENT_TIMESTAMP,
        End_Time DATETIME,
        required_skill VARCHAR(255),
        user_id INT(11) NULL,
        manager_id INT(11) NOT NULL,
    FOREIGN KEY (user_id) REFERENCES users(id_u) ON DELETE SET NULL,
    FOREIGN KEY (manager_id) REFERENCES manager(id_m) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($sql);

    echo "✔ Table 'task' checked/created.<br>";

    $sql = "
    CREATE TABLE IF NOT EXISTS skills (
        id_S INT(11) NOT NULL AUTO_INCREMENT,
        skill_name VARCHAR(50) NOT NULL,
        user_id INT(11),
        proficiency_level ENUM('beginner', 'intermediate', 'expert') NOT NULL,
        
        PRIMARY KEY (id_S),
        FOREIGN KEY (user_id) REFERENCES users(id_u) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $pdo->exec($sql);

    echo "✔ Table 'skills' checked/created.<br>";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage();
}
?>
