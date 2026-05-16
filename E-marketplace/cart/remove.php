<?php
require_once "../config/auth_check.php";
require_once "../config/db.php";

$cart_id = $_GET['id'] ?? null;

if ($cart_id) {
    $stmt = $conn->prepare("DELETE FROM cart WHERE id = ?");
    $stmt->bind_param("i", $cart_id);
    $stmt->execute();
}

header("Location: index.php");
exit();