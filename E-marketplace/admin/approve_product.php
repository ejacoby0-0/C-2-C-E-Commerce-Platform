<?php
require_once "../config/admin_check.php";
require_once "../config/db.php";

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("UPDATE products SET status = 'active' WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
}

header("Location: dashboard.php");
exit();