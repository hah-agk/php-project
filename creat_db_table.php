<?php
$host = 'localhost';
$port = 3306;
$user = 'root';
$pass = '';
$dbname = 'project';

try {
    // First, connect without specifying database to create it if it doesn't exist
    $pdo = new PDO(
        "mysql:host=$host;port=$port;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");
    
    // Now connect to the database
    $pdo = new PDO(
        "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );
    
    // Enable foreign key checks
    $pdo->exec("SET FOREIGN_KEY_CHECKS=1");

    // USERS TABLE
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS users (
        id_u INT(11) NOT NULL AUTO_INCREMENT,
        FullName VARCHAR(50) NOT NULL,
        phone VARCHAR(20) NOT NULL,
        address VARCHAR(100) NOT NULL,
        salary DECIMAL(10,2) NOT NULL DEFAULT 0,
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
        phone VARCHAR(20) NOT NULL,
        address VARCHAR(100) NOT NULL,
        salary DECIMAL(10,2) NOT NULL DEFAULT 0,
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
        KEY (user_id),
        FOREIGN KEY (user_id) REFERENCES users(id_u) ON DELETE CASCADE
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ");
    
    // TASK TABLE
    $pdo->exec("
    CREATE TABLE IF NOT EXISTS task (
        id_T INT(11) NOT NULL AUTO_INCREMENT,
        Title_T VARCHAR(255) NOT NULL,
        status ENUM('pending','in_progress','waiting_review','completed','rejected') DEFAULT 'pending',
        description TEXT,
        bounty DECIMAL(10,2) NOT NULL DEFAULT 0,
        Start_Time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        End_Time DATETIME,
        required_skill VARCHAR(255),
        user_id INT(11),
        manager_id INT(11) NOT NULL,
        PRIMARY KEY (id_T),
        KEY (user_id),
        KEY (manager_id),
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
    
$pdo->exec("
CREATE TABLE IF NOT EXISTS manager_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    phone VARCHAR(20),
    address VARCHAR(255),
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    status ENUM('pending','approved','rejected') DEFAULT 'pending',
    request_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
");


    echo "✔ All tables created successfully!";
    
//     $pdo->exec("
// ALTER TABLE task
// MODIFY status 
// ENUM('pending','in_progress','waiting_review','completed','rejected')
// DEFAULT 'pending';
//     ");

//     $pdo->exec("
// ALTER TABLE task
// MODIFY Start_Time DATETIME NOT NULL;
//     ");

    // Create a default admin user
   $sql = "INSERT INTO admin (name, email, password) VALUES (?, ?, ?)";
$stmt = $pdo->prepare($sql);

$stmt->execute([
    'admin',
    'admin@gmail.com',
    password_hash('admin123', PASSWORD_BCRYPT)
]);

// $sql = "ALTER TABLE manager 
// MODIFY salary DECIMAL(10,2) NOT NULL DEFAULT 0;
// ";
// $pdo->exec($sql);
// $sql = "ALTER TABLE users 
// MODIFY salary DECIMAL(10,2) NOT NULL DEFAULT 0;
// ";
// $pdo->exec($sql);

// $sql= "UPdate manager set salary=0 where salary is null;";
// $pdo->exec($sql);
// $sql = "UPdate users set salary=0 where salary is null;";
// $pdo->exec($sql);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
