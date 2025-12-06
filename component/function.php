<?php
session_start();
require 'opendb.php';

function show_task($pdo ,$Mid ) {
    $sql = "SELECT * FROM task WHERE manager_id = :Mid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':Mid', $Mid, PDO::PARAM_INT);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $tasks;
}
