<?php
require_once "../config/auth_check.php";
require_once "../config/db.php";

$pageTitle = "My Profile";

include "../includes/header.php";

$user_id = $_SESSION['user_id'];

//Showing the status from the database
// Total Listings (seller products only)
$stmt = $conn->prepare("
    SELECT COUNT(*) AS total_products
    FROM products
    WHERE seller_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$product_count = $stmt->get_result()->fetch_assoc()['total_products'];


// Orders made by buyer
$stmt = $conn->prepare("
    SELECT COUNT(*) AS total_orders
    FROM orders
    WHERE buyer_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$order_count = $stmt->get_result()->fetch_assoc()['total_orders'];


// Sales made by seller
$stmt = $conn->prepare("
    SELECT COUNT(*) AS total_sales
    FROM orders
    WHERE seller_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$my_sales = $stmt->get_result()->fetch_assoc()['total_sales'];


// Seller revenue
$stmt = $conn->prepare("
    SELECT SUM(total_amount) AS total_revenue
    FROM orders
    WHERE seller_id = ?
    AND status IN ('paid', 'shipped', 'delivered')
");
$stmt->bind_param("i", $user_id);
$stmt->execute();

$revenue = $stmt->get_result()->fetch_assoc()['total_revenue'] ?? 0;

?>



<main>
    <div class="container">
        <div class="header">
            <a href="../pages/index.php"><button class="back-btn">← Back to E-Marketplace</button></a>

            <h1>Users Dashboard</h1>
            <p>👋 Welcome, <?php echo $_SESSION['username']; ?>!</p>
            <p>Manage your profile</p>
        </div>

        <!-- Tabs -->
        <div class="card">
            <div class="tabs">
            <button class="tab active" onclick="showTab('overview')">Overview</button>
            <button class="tab" onclick="showTab('myListings')">My Listings</button>
            <button class="tab" onclick="showTab('myOrders')">My orders</button>
            <button class="tab" onclick="showTab('mySales')">My Sales</button>
            <button class="tab" onclick="showTab('myProfile')">My Profile</button>
            </div>

            <div class="content">

                <!-- Overview -->
                <div id="overview" class="tab-content active">
                    <h3>Overview</h3>
                    <div class="grid">
                        <div class="stat-card">
                            <p>Total listings</p>
                            <h2><?php echo $product_count?></h2>
                        </div>
                        <div class="stat-card">
                            <p>Orders</p>
                            <h2><?php echo $order_count?></h2>
                        </div>
                        <div class="stat-card">
                            <p>Sales</p>
                            <h2><?php echo $my_sales?></h2>
                        </div>
                        <div class="stat-card">
                            <p>Revenue</p>
                            <h2>R<?php echo number_format($revenue, 2); ?></h2>
                        </div>
                    </div>
                </div>

                

                <!-- My Listings -->
                <?php
                $user_id = $_SESSION['user_id'];

                // Get this user's products
                $stmt = $conn->prepare("
                    SELECT p.*, c.name AS category_name
                    FROM products p
                    LEFT JOIN categories c ON p.category_id = c.id
                    WHERE p.seller_id = ?
                    ORDER BY p.created_at DESC
                ");

                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                ?>

                <div id="myListings" class="tab-content">
                    <h3>My Listings</h3>
                    <input type="text" placeholder="Search item..." class="input" />

                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Condition</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while($product = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo $product['title']; ?></td>
                                <td><?php echo $product['category_name'] ?? 'None'; ?></td>
                                <td><?php echo ucfirst($product['product_condition']); ?></td>
                                <td>R<?php echo $product['price']; ?></td>

                                <td>
                                    <?php
                                    $status = $product['status'];
                                    echo strtoupper($status);
                                    ?>
                                </td>

                                <td><?php echo $product['created_at']; ?></td>

                                
                                <td>
                                    <a href="edit-product.php?id=<?php echo $product['id']; ?>">Edit</a> |
                                    <a href="delete-product.php?id=<?php echo $product['id']; ?>"onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>    
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- My Orders-->
                <?php
                $user_id = $_SESSION['user_id'];

                $stmt = $conn->prepare("
                    SELECT 
                        o.*,
                        p.title,
                        u.username AS seller_name

                    FROM orders o

                    JOIN products p 
                    ON o.product_id = p.id

                    JOIN users u 
                    ON o.seller_id = u.id

                    WHERE o.buyer_id = ?

                    ORDER BY o.created_at DESC
                ");

                $stmt->bind_param("i", $user_id);
                $stmt->execute();

                $result = $stmt->get_result();
                ?>


                <div id="myOrders" class="tab-content">
                    <h3>My Orders</h3>

                    <table>
                        <thead>
                            <tr>
                                <th>Title</th>
                                <th>Seller</th>
                                <th>Status</th>
                                <th>Amount</th>
                                <th>Details</th>

                            </tr>
                        </thead>

                        <tbody>
                            <?php while($order = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $order['title']; ?></td>
                                    <td><?php echo $order['seller_name']; ?></td>
                                    <td>
                                        <span class="status-badge">
                                            <?php echo ucfirst($order['status']); ?>
                                        </span>
                                    </td>                                    
                                    <td>R<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td>
                                        <a href="order-details.php?id=<?php echo $order['id']; ?>">
                                            View Details
                                        </a>
                                    </td>
                                    
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <!-- My Sales-->
                <?php
                $stmt = $conn->prepare("
                    SELECT o.*, p.title, u.username AS buyer_name
                    FROM orders o
                    JOIN products p ON o.product_id = p.id
                    JOIN users u ON o.buyer_id = u.id
                    WHERE o.seller_id = ?
                    ORDER BY o.created_at DESC
                ");

                $stmt->bind_param("i", $user_id);
                $stmt->execute();

                $sales = $stmt->get_result();
                ?>

                <div id="mySales" class="tab-content">

                    <h3>My Sales</h3>

                    <table>
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Buyer</th>
                                <th>Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>

                        <?php while($sale = $sales->fetch_assoc()): ?>

                            <tr>

                                <td><?php echo $sale['title']; ?></td>

                                <td><?php echo $sale['buyer_name']; ?></td>

                                <td>R<?php echo $sale['total_amount']; ?></td>

                                <td><?php echo $sale['status']; ?></td>

                            </tr>

                        <?php endwhile; ?>

                        </tbody>
                    </table>

                </div>

                <!-- My Profile-->
                <?php
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
                ?>

                <div id="myProfile" class="tab-content">
                    <h3>My Profile</h3>

                    <!-- PROFILE HEADER -->
                    <section class="profile-header">
                        <form action="update-profile.php" method="POST" enctype="multipart/form-data" class="profile-form">

                            <!-- Profile Image -->
                            <div class="profile-image-preview">

                                <img
                                    src="<?php echo !empty($user['profile_image'])
                                        ? $user['profile_image']
                                        : '../assets/img/default-avatar.jpg'; ?>"
                                    alt="Profile"
                                >
                            </div>

                            <div class="form-group">
                                <label>Profile Image</label>
                                <input type="file" name="profile_image">
                            </div>

                            <div class="form-group">
                                <label>Username</label>

                                <input
                                    type="text"
                                    name="username"
                                    value="<?php echo htmlspecialchars($user['username']); ?>"
                                    required
                                >
                            </div>

                            <div class="form-group">
                                <label>Bio</label>

                                <textarea name="bio"><?php
                                    echo htmlspecialchars($user['bio'] ?? '');
                                ?></textarea>
                            </div>

                            <div class="form-group">
                                <label>Phone Number</label>

                                <input
                                    type="text"
                                    name="phone"
                                    value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>"
                                >
                            </div>

                            <div class="form-group">
                                <label>Location</label>

                                <input
                                    type="text"
                                    name="location"
                                    value="<?php echo htmlspecialchars($user['location'] ?? ''); ?>"
                                >
                            </div>

                            <button type="submit" class="save-btn">
                                Save Changes
                            </button>

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
                </div>
            </div>
        </div>
    </div>
</main>

<?php
//Start Scripts
include "../includes/scripts.php";
?>

