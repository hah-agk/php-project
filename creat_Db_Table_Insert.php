<?php

$server = "localhost";
$username = "root";
$password = "";

try {
    // 1) Connect to MySQL WITHOUT selecting a database
    $pdo = new PDO("mysql:host=$server", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 2) Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS project CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci");

    echo "✔ Database 'project' checked/created.<br>";

    // 3) Connect again but now to the database
    $pdo = new PDO("mysql:host=$server;dbname=project", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 4) SQL to create table
    $sql = "
    CREATE TABLE IF NOT EXISTS users (
        id INT(11) NOT NULL AUTO_INCREMENT,
        name VARCHAR(50) NOT NULL,
        phone INT(11) NOT NULL,
        addres VARCHAR(100) NOT NULL,
        salary INT(11) NOT NULL,
        email VARCHAR(50) NOT NULL,
        password VARCHAR(120) NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    // 5) Run SQL
    $pdo->exec($sql);

    echo "✔ Table 'users' created successfully.";

    $sql = "
    CREATE TABLE IF NOT EXISTS manager (
        id INT(11) NOT NULL AUTO_INCREMENT,
        name VARCHAR(50) NOT NULL,
        phone INT(11) NOT NULL,
        addres VARCHAR(100) NOT NULL,
        salary INT(11) NOT NULL,
        email VARCHAR(50) NOT NULL,
        password VARCHAR(120) NOT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
    ";

    // 5) Run SQL
    $pdo->exec($sql);

    echo "✔ Table 'manager' created successfully.";

   $sql = "
   CREATE TABLE IF NOT EXISTS  task (
    idm INT AUTO_INCREMENT PRIMARY KEY,
    namem VARCHAR(255) NOT NULL,
    status VARCHAR(100),
    deadline DATE,
    description TEXT,
    bounty DECIMAL(10,2)
)";;

    // 5) Run SQL
    $pdo->exec($sql);

    echo "✔ Table 'task' created successfully.";
} catch (PDOException $exception) {
    echo "❌ Error: " . $exception->getMessage();
}
?>

