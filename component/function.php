<?php
session_start();
require 'opendb.php';

function show_task($pdo ) {
    $sql = "SELECT * FROM task";
    $stmt = $pdo->prepare($sql);
    
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $tasks;
}
