<?php
session_start();
require 'component/opendb.php';

// if (!isset($_SESSION['UorMorA']) || $_SESSION['UorMorA'] !== 'admin' || !isset($_SESSION['LoggedIn']) || $_SESSION['LoggedIn'] !== true) {
//     header("Location: signup.php");
//     exit();
// }