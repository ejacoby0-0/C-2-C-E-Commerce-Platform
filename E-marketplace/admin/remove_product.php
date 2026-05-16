<?php
require_once "../config/admin_check.php";
require_once "../config/db.php";

$id = $_GET['id'] ?? null;

if ($id) {
    $stmt = $conn->prepare("UPDATE products SET status = 'removed' WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php?msg=product_removed");
    } else {
        header("Location: dashboard.php?msg=error");
    }
    exit();
}

header("Location: dashboard.php");
exit();