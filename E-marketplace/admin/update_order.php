<?php
require_once "../config/admin_check.php";
require_once "../config/db.php";

$order_id = $_POST['order_id'] ?? null;
$status = $_POST['status'] ?? null;

if ($order_id && $status) {

    $stmt = $conn->prepare("
        UPDATE orders 
        SET status = ? 
        WHERE id = ?
    ");
    $stmt->bind_param("si", $status, $order_id);
    $stmt->execute();
}

header("Location: dashboard.php");
exit();