<?php
require_once "../config/auth_check.php";
require_once "../config/db.php";

$pageTitle = "Order Details";

include "../includes/header.php";

$order_id = $_GET['id'] ?? 0;
$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT 
        o.*, 
        p.title,
        p.description,
        p.price,
        pi.image_path,

        b.username AS buyer_name,
        s.username AS seller_name

    FROM orders o

    JOIN products p 
    ON o.product_id = p.id

    LEFT JOIN product_images pi
    ON p.id = pi.product_id
    AND pi.is_primary = 1

    JOIN users b 
    ON o.buyer_id = b.id

    JOIN users s 
    ON o.seller_id = s.id

    WHERE o.id = ?
");

$stmt->bind_param("i", $order_id);
$stmt->execute();
$order = $stmt->get_result()->fetch_assoc();

if (!$order) {
    die("Order not found.");
}

// SECURITY: only buyer or seller can view
if ($order['buyer_id'] != $user_id && $order['seller_id'] != $user_id) {
    die("Access denied.");
}
?>

<main class="order-page">

    <div class="order-layout">

        <!-- LEFT SIDE -->
        <div class="order-gallery">

            <div class="order-image-wrapper">

                <img 
                    src="<?php echo $order['image_path'] ?? '../assets/img/default-product.jpg'; ?>"
                    class="order-product-image"
                >

            </div>

        </div>

        <!-- RIGHT SIDE -->
        <div class="order-details-card">

            <!-- Title -->
            <h1 class="order-title">
                <?php echo $order['title']; ?>
            </h1>

            <!-- Status -->
            <div class="order-status">
                <?php echo ucfirst($order['status']); ?>
            </div>

            <!-- Price -->
            <p class="order-price">
                R<?php echo number_format($order['total_amount'], 2); ?>
            </p>

            <!-- Buyer/Seller -->
            <div class="order-users">

                <div class="order-user-box">
                    <h4>Buyer</h4>
                    <p><?php echo $order['buyer_name']; ?></p>
                </div>

                <div class="order-user-box">
                    <h4>Seller</h4>
                    <p><?php echo $order['seller_name']; ?></p>
                </div>

            </div>

            <!-- Order Meta -->
            <div class="order-meta">

                <div class="meta-item">
                    <span>Payment Reference</span>
                    <strong><?php echo $order['payment_reference']; ?></strong>
                </div>

                <div class="meta-item">
                    <span>Shipping Address</span>
                    <strong><?php echo $order['shipping_address']; ?></strong>
                </div>

                <div class="meta-item">
                    <span>Order Date</span>
                    <strong>
                        <?php echo date('d M Y', strtotime($order['created_at'])); ?>
                    </strong>
                </div>

            </div>

            <!-- Description -->
            <div class="order-description">

                <h3>Product Description</h3>

                <p>
                    <?php echo nl2br($order['description']); ?>
                </p>

            </div>

            <!-- Actions -->
            <div class="order-actions">

                <a href="../user/dashboard.php"
                    class="back-orders-btn">

                    Back to Dashboard

                </a>

            </div>

        </div>

    </div>

</main>