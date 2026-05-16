<?php
require_once "../config/auth_check.php";
require_once "../config/db.php";

$pageTitle = "Cart";

include "../includes/header.php";
echo "<link rel='stylesheet' href='./assets/pages/cart.css'>";


$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("
    SELECT p.*, pi.image_path
    FROM cart c
    JOIN products p ON c.product_id = p.id
    LEFT JOIN product_images pi 
        ON p.id = pi.product_id AND pi.is_primary = 1
    WHERE c.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
?>


<main class="cart-page">

    <h1 class="cart-title">Your Shopping Cart</h1>

    <?php if ($result->num_rows === 0): ?>

        <div class="empty-cart">
            <h2>Your cart is empty</h2>

            <a href="../pages/shop.php" class="continue-shopping-btn">
                Continue Shopping
            </a>
        </div>

    <?php else: ?>

        <?php
        $total = 0;
        ?>

        <div class="cart-layout">

            <!-- LEFT SIDE -->
            <div class="cart-items">

                <?php while($item = $result->fetch_assoc()): ?>

                    <?php
                    $total += $item['price'];
                    ?>

                    <div class="cart-item">

                        <!-- Product Image -->
                        <div class="cart-image">

                            <img
                                src="<?php echo $item['image_path']
                                    ?? '../assets/img/default-product.jpg'; ?>"
                                alt="<?php echo $item['title']; ?>"
                            >

                        </div>

                        <!-- Product Info -->
                        <div class="cart-info">

                            <h3><?php echo $item['title']; ?></h3>

                            <p class="cart-condition">
                                Condition:
                                <?php echo ucfirst($item['product_condition']); ?>
                            </p>

                            <p class="cart-price">
                                R<?php echo number_format($item['price'], 2); ?>
                            </p>

                            <a
                                href="remove.php?id=<?php echo $item['id']; ?>"
                                class="remove-btn"
                            >
                                Remove
                            </a>

                        </div>

                    </div>

                <?php endwhile; ?>

            </div>

            <!-- RIGHT SIDE -->
            <aside class="cart-summary">

                <h2>Order Summary</h2>

                <div class="summary-row">
                    <span>Items</span>
                    <span><?php echo $result->num_rows; ?></span>
                </div>

                <div class="summary-row">
                    <span>Delivery</span>
                    <span>Free</span>
                </div>

                <div class="summary-total">
                    <span>Total</span>

                    <span>
                        R<?php echo number_format($total, 2); ?>
                    </span>
                </div>

                <!-- Checkout -->
                <form method="POST" action="checkout.php">

                    <button type="submit" class="checkout-btn">
                        Proceed to Checkout
                    </button>

                </form>

                <a href="../pages/shop.php" class="continue-shopping">
                    Continue Shopping
                </a>

            </aside>

        </div>

    <?php endif; ?>

</main>