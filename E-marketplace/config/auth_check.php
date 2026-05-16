<?php
//This file checks if users are login or not and can be use to protect differetn pages
//The main purpose of this page is to first authenticate the users
require_once "../config/session.php";
require_once "../config/db.php";

//This will redirect the user to the login
if (!isset($_SESSION['user_id'])){
    header("Location: ../auth/login.php");
    exit();
}

$stmt = $conn->prepare("SELECT status FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result()->fetch_assoc();

if ($result['status'] === 'suspended') {
    session_destroy();
    header("Location: ../auth/login.php");
    exit();
}
?>