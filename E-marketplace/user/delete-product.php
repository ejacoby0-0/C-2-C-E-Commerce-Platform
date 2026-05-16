<?php
require_once "../config/auth_check.php";
require_once "../config/db.php";

$user_id = $_SESSION['user_id'];
$product_id = $_GET['id'] ?? 0;

// Delete ONLY if owned by user
$stmt = $conn->prepare("DELETE FROM products WHERE id = ? AND seller_id = ?");
$stmt->bind_param("ii", $product_id, $user_id);

$stmt->execute();

header("Location: my-listings.php");
exit();