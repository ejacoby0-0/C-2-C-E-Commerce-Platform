<?php
require_once "../config/auth_check.php";
require_once "../config/session.php";
require_once "../config/db.php";

$pageTitle = "Checkout";

include "../includes/header.php";

$user_id = $_SESSION['user_id'];

/*SINGLE PRODUCT BUY NOW*/

if (isset($_GET['product_id'])) {

    $product_id = $_GET['product_id'];

    $stmt = $conn->prepare("
        SELECT 
            p.*,
            pi.image_path
        FROM products p

        LEFT JOIN product_images pi
        ON p.id = pi.product_id
        AND pi.is_primary = 1

        WHERE p.id = ?
    ");

    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    $cart = $stmt->get_result();

}

/*NORMAL CART CHECKOUT */
else {

    $stmt = $conn->prepare("
        SELECT 
            p.*, 
            pi.image_path,
            c.product_id
        FROM cart c

        JOIN products p 
        ON c.product_id = p.id

        LEFT JOIN product_images pi
        ON p.id = pi.product_id
        AND pi.is_primary = 1

        WHERE c.user_id = ?
    ");

    $stmt->bind_param("i", $user_id);
    $stmt->execute();

    $cart = $stmt->get_result();
}

$total = 0;
?>

<main class="checkout-page">

    <div class="checkout-layout">

        <!-- LEFT SIDE -->
        <div class="checkout-items">

            <h1>Checkout</h1>

            <?php while($item = $cart->fetch_assoc()): ?>

                <?php $total += $item['price']; ?>

                <div class="checkout-item">

                    <img 
                        src="<?php echo $item['image_path'] ?? '../assets/img/default-product.jpg'; ?>"
                        alt="<?php echo $item['title']; ?>"
                    >

                    <div class="checkout-item-info">

                        <h3><?php echo $item['title']; ?></h3>

                        <p class="checkout-condition">
                            <?php echo ucfirst($item['product_condition']); ?>
                        </p>

                        <p class="checkout-price">
                            R<?php echo number_format($item['price'], 2); ?>
                        </p>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

        <!-- RIGHT SIDE -->
        <aside class="checkout-summary">

            <h2>Order Summary</h2>

            <div class="summary-row">
                <span>Items</span>
                <span>R<?php echo number_format($total, 2); ?></span>
            </div>

            <div class="summary-row">
                <span>Delivery</span>
                <span>Free</span>
            </div>

            <div class="summary-total">
                <span>Total</span>
                <span>R<?php echo number_format($total, 2); ?></span>
            </div>

            <!-- Shipping -->
            <div class="shipping-box">

                <h3>Shipping Address</h3>

                <textarea 
                    name="shipping_address"
                    form="checkoutForm"
                    required
                    placeholder="Enter your delivery address"
                ></textarea>

            </div>

            <!-- Payment -->
            <div class="payment-box">

                <h3>Payment Method</h3>

                <label>
                    <input type="radio" name="payment_method" value="card" checked form="checkoutForm">
                    Debit / Credit Card
                </label>

                <label>
                    <input type="radio" name="payment_method" value="eft" form="checkoutForm">
                    EFT
                </label>

            </div>

            <!-- PLACE ORDER -->
            <form method="POST" action="place-order.php" id="checkoutForm">

                <?php if(isset($_GET['product_id'])): ?>

                    <input 
                        type="hidden"
                        name="product_id"
                        value="<?php echo $_GET['product_id']; ?>"
                    >

                <?php endif; ?>
                <button type="submit" class="checkout-btn">
                    Place Order
                </button>

            </form>

        </aside>

    </div>

</main>