<?php
require_once "../config/db.php";
require_once "../config/session.php";

$user_id = $_GET['id'] ?? 0;

/* USER INFO */
$stmt = $conn->prepare("
    SELECT *
    FROM users
    WHERE id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

if(!$user){
    die("User not found");
}

/* USER PRODUCTS */
$stmt = $conn->prepare("
    SELECT p.*, pi.image_path
    FROM products p

    LEFT JOIN product_images pi
    ON p.id = pi.product_id
    AND pi.is_primary = 1

    WHERE p.seller_id = ?
    AND p.status IN ('active', 'sold')

    ORDER BY p.created_at DESC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$products = $stmt->get_result();

include "../includes/header.php";
?>

<main class="profile-page">

    <!-- PROFILE HEADER -->
    <section class="profile-header">

        <form class="profile-form">
            <div class="profile-image-preview">
                <img
                    src="<?php echo !empty($user['profile_image'])
                        ? $user['profile_image']
                        : '../assets/img/default-avatar.jpg'; ?>"
                    alt="Profile"
                >
            </div>

            <div class="profile-details">

                <h1><?php echo $user['username']; ?></h1>

                <p class="profile-location">
                    📍 <?php echo $user['location'] ?: 'Location not added'; ?>
                </p>

                <p class="profile-joined">
                    Joined <?php echo date('F Y', strtotime($user['created_at'])); ?>
                </p>

                <?php if($user['bio']): ?>
                    <p class="profile-bio">
                        <?php echo nl2br($user['bio']); ?>
                    </p>
                <?php endif; ?>

                <div class="profile-contact">

                    <?php if($user['phone']): ?>
                        <p>📞 <?php echo $user['phone']; ?></p>
                    <?php endif; ?>

                    <?php if($user['instagram']): ?>
                        <p>📸 @<?php echo $user['instagram']; ?></p>
                    <?php endif; ?>

                </div>

            </div>
        </form>
    </section>

    <!-- SELLER PRODUCTS -->
    <section class="profile-products">

        <h2>Listings</h2>

        <div class="products-grid">

            <?php while($product = $products->fetch_assoc()): ?>

                <a href="../pages/product.php?id=<?php echo $product['id']; ?>"
                    class="product-card <?php echo $product['status'] === 'sold' ? 'sold' : ''; ?>">

                    <div class="product-card-image-wrapper">

                        <img src="<?php echo $product['image_path'] ?? '../assets/img/default-product.jpg'; ?>">

                        <?php if($product['status'] === 'sold'): ?>

                            <span class="sold-badge">
                                SOLD
                            </span>

                        <?php endif; ?>

                    </div>

                    <h3><?php echo $product['title']; ?></h3>

                    <p>R<?php echo $product['price']; ?></p>

                </a>

            <?php endwhile; ?>

        </div>

    </section>

</main>
