<?php
session_start();
require 'opendb.php';

function show_task($pdo ,$Mid ) {
    $sql = "SELECT * FROM task WHERE manager_id = :Mid AND status != 'completed'"; 
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':Mid', $Mid, PDO::PARAM_INT);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $tasks;
}
function show_task_completed($pdo ,$Mid ) {
    $sql = "SELECT * FROM task WHERE manager_id = :Mid AND status = 'completed'"; 
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':Mid', $Mid, PDO::PARAM_INT);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $tasks;
}
function show_task_user($pdo ,$Uid ) {
    $sql = "SELECT * FROM task WHERE user_id = :Uid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':Uid', $Uid, PDO::PARAM_INT);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $tasks;
}   
function show_all_tasks($pdo ) {
    $sql = "SELECT * FROM task";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $tasks;
}