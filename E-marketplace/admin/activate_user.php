<?php
require_once "../config/admin_check.php";
require_once "../config/db.php";

$user_id = $_GET['id'] ?? null;

if ($user_id) {
    $stmt = $conn->prepare("UPDATE users SET status = 'active' WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
}

header("Location: dashboard.php");
exit();