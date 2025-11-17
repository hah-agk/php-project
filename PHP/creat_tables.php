<?php
require 'db.php';  // لكي نستعمل $pdo الجاهز من ملف db.php

try {

    // SQL to create table
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

    // run SQL
    $pdo->exec($sql);

    echo "✔ Table 'users' has been created successfully.";

} catch (PDOException $exception) {
    echo "❌ Error creating table: " . $exception->getMessage();
}
?>
