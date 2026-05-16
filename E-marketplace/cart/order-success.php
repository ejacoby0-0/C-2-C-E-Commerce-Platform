<?php
require_once "../config/session.php";

$pageTitle = "Order Successful";

include "../includes/header.php";

/* Prevent direct access */
if (!isset($_SESSION['success_message'])) {
    header("Location: ../pages/index.php");
    exit();
}

$message = $_SESSION['success_message'];

/* Clear after showing once */
unset($_SESSION['success_message']);
?>

<main class="success-page">

    <div class="success-card">

        <!-- Success Icon -->
        <div class="success-icon">
            ✓
        </div>

        <h1>Order Successful</h1>

        <p>
            <?php echo $message; ?>
        </p>

        <div class="success-actions">

            <a href="../user/dashboard.php" class="success-btn">
                View Orders
            </a>

            <a href="../pages/shop.php" class="continue-btn">
                Continue Shopping
            </a>

        </div>

    </div>

</main>