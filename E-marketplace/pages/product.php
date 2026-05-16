<?php
require_once "../config/db.php";
session_start();

$pageTitle = "Product";

include "../includes/header.php";

$product_id = $_GET['id'] ?? 0;

// Get product + seller + category
$stmt = $conn->prepare("
    SELECT 
        p.*, 
        u.username,
        u.profile_image,
        c.name AS category_name
    FROM products p
    JOIN users u ON p.seller_id = u.id
    LEFT JOIN categories c ON p.category_id = c.id
    WHERE p.id = ? AND p.status = 'active'
");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die("Product not found.");
}

// Get images
$stmt = $conn->prepare("
    SELECT * FROM product_images WHERE product_id = ?
");
$stmt->bind_param("i", $product_id);
$stmt->execute();
$images = $stmt->get_result();
?>

<main class="product-page">

    <div class="product-layout">

        <!-- LEFT SIDE -->
        <div class="product-gallery">

            <?php while($img = $images->fetch_assoc()): ?>

                <div class="main-image-wrapper">

                    <img 
                        src="<?php echo $img['image_path']; ?>" 
                        alt="<?php echo $product['title']; ?>"
                        class="main-product-image"
                    >

                </div>

            <?php endwhile; ?>

        </div>

        <!-- RIGHT SIDE -->
        <div class="product-details">

            <!-- Title -->
            <h1 class="product-title">
                <?php echo $product['title']; ?>
            </h1>

            <!-- Price -->
            <p class="product-price">
                R<?php echo number_format($product['price'], 2); ?>
            </p>

            <!-- Seller -->
            <div class="seller-box">

                <div class="profile-image-preview">

                    <a href="../user/profile.php?id=<?php echo $product['seller_id']; ?>">

                    <img
                        src="<?php echo !empty($product['profile_image'])
                            ? $product['profile_image']
                            : '../assets/img/default-avatar.jpg'; ?>"
                        alt="Profile"
                    >

                        <div>
                            <h4><?php echo $product['username']; ?></h4>
                            <p>View Seller Profile</p>
                        </div>

                    </a>
                </div>

            </div>

            <!-- Category -->
            <div class="product-meta">
                <span>
                    Category: <?php echo $product['category_name']; ?>
                </span>

                <span>
                    Location: <?php echo $product['location']; ?>
                </span>

                <span>
                    Condition: <?php echo $product['product_condition']; ?>
                </span>
            </div>

            <!-- Description -->
            <div class="description-box">

                <h3>Description</h3>

                <p>
                    <?php echo nl2br($product['description']); ?>
                </p>

            </div>

            <!-- ACTION BUTTONS -->
            <div class="product-actions">

                <?php if (isset($_SESSION['user_id'])): ?>

                        <?php if (
                            $_SESSION['user_id'] != $product['seller_id']
                            && $product['status'] !== 'sold'
                        ): ?>

                        <!-- Buy -->
                        <form method="GET" action="../cart/checkout.php">

                            <input 
                                type="hidden"
                                name="product_id"
                                value="<?php echo $product['id']; ?>"
                            >

                            <button type="submit" class="buy-btn">
                                Buy Now
                            </button>

                        </form>

                        <!-- Add to cart -->
                        <form method="POST" action="../cart/add.php">

                            <input 
                                type="hidden" 
                                name="product_id"
                                value="<?php echo $product['id']; ?>"
                            >

                            <button type="submit" class="cart-btn">
                                Add to Cart
                            </button>

                        </form>

                    <?php else: ?>

                        <div class="own-product-box">
                            This is your product.
                        </div>

                    <?php endif; ?>

                <?php else: ?>

                    <a href="../auth/login.php" class="login-buy-btn">
                        Login to Buy
                    </a>

                <?php endif; ?>
            </div>
        </div>
    </div>
</main>