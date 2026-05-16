<?php
require_once "../config/auth_check.php";
require_once "../config/db.php";

$user_id = $_SESSION['user_id'];
$product_id = $_POST['product_id'] ?? null;

if (!$product_id) {
    header("Location: ../pages/index.php");
    exit();
}

// Prevent duplicates (your DB already enforces this)
$stmt = $conn->prepare("
    INSERT IGNORE INTO cart (user_id, product_id)
    VALUES (?, ?)
");
$stmt->bind_param("ii", $user_id, $product_id);
$stmt->execute();

// Redirect back to product page
header("Location: ../pages/product.php?id=" . $product_id);
exit();