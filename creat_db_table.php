<?php
require 'component/opendb.php';

try {

    // USERS TABLE
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id_u INT(11) NOT NULL AUTO_INCREMENT,
        FullName VARCHAR(50) NOT NULL,
        phone INT(11) NOT NULL,
        address VARCHAR(100) NOT NULL,
        salary DECIMAL(10,2),
        email VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(120) NOT NULL,
        PRIMARY KEY (id_u)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // MANAGER TABLE
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS manager (
        id_m INT(11) NOT NULL AUTO_INCREMENT,
        name VARCHAR(50) NOT NULL,
        phone INT(11) NOT NULL,
        address VARCHAR(100) NOT NULL,
        salary DECIMAL(10,2),
        email VARCHAR(50) NOT NULL UNIQUE,
        password VARCHAR(120) NOT NULL,
        PRIMARY KEY (id_m)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

     // SKILLS TABLE
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS skills (
        id_S INT(11) NOT NULL AUTO_INCREMENT,
        skill_name VARCHAR(50) NOT NULL,
        user_id INT(11),
        proficiency_level ENUM('beginner','intermediate','expert') NOT NULL,
        PRIMARY KEY (id_S),
        FOREIGN KEY (user_id) REFERENCES users(id_u) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    
    // TASK TABLE
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS task (
        id_T INT(11) NOT NULL AUTO_INCREMENT,
        Title_T VARCHAR(255) NOT NULL,
        status ENUM('pending','in_progress','completed') DEFAULT 'pending',
        description TEXT,
        bounty DECIMAL(10,2),
        Start_Time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        End_Time DATETIME,
        required_skill VARCHAR(255),
        user_id INT(11),
        manager_id INT(11) NOT NULL,
        PRIMARY KEY (id_T),
        FOREIGN KEY (user_id) REFERENCES users(id_u) ON DELETE SET NULL,
        FOREIGN KEY (manager_id) REFERENCES manager(id_m) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");

    // Admin TABLE
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS admin (
        id_a INT(11) NOT NULL AUTO_INCREMENT,
        name VARCHAR(50) NOT NULL,
        email VARCHAR(50) NOT NULL,
        password VARCHAR(120) NOT NULL,
        PRIMARY KEY (id_a)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");


    echo "✔ All tables created successfully!";

   $sql = "INSERT INTO admin (name, email, password) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    'admin',
    'admin@gmail.com',
    password_hash('admin123', PASSWORD_BCRYPT)
]);
echo "\n✔ Default admin user created successfully!";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}