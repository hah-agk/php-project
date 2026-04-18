<?php

session_start();
require_once 'opendb.php';

function showTask($pdo ,$mid ) {
    $sql = "SELECT * FROM task WHERE manager_id = :Mid AND status != 'completed'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':Mid', $mid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function showTaskCompleted($pdo ,$mid) {
    $sql = "SELECT * FROM task WHERE manager_id = :Mid AND status = 'completed'";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':Mid', $mid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function showTaskUser($pdo ,$uid ) {
    $sql = "SELECT * FROM task WHERE user_id = :Uid";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':Uid', $uid, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
function showAllTasks($pdo ) {
    $sql = "SELECT * FROM task";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}