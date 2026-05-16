<?php
require_once "../config/admin_check.php";
require_once "../config/db.php";

$user_id = $_GET['id'] ?? null;

// Prevent admin deleting themselves
if ($user_id == $_SESSION['user_id']) {
    header("Location: dashboard.php?msg=cannot_delete_self");
    exit();
}

if ($user_id) {

    // 1. Suspend user
    $stmt = $conn->prepare("UPDATE users SET status = 'suspended' WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    // 2. Remove their products (soft delete)
    $stmt = $conn->prepare("UPDATE products SET status = 'removed' WHERE seller_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    header("Location: dashboard.php?msg=user_removed");
    exit();
}

header("Location: dashboard.php");
exit();