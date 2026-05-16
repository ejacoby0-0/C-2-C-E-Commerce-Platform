<?php

require_once "../config/admin_check.php";
require_once "../config/db.php";

$pageTitle = "Admin Dashboard";

include "../includes/header.php";

//Showing the status from the database
// Total Users
$user_result = $conn->query("SELECT COUNT(*) AS total_users FROM users");
$user_count = $user_result->fetch_assoc()['total_users'];

// Total Products
$product_result = $conn->query("SELECT COUNT(*) AS total_products FROM products");
$product_count = $product_result->fetch_assoc()['total_products'];

// Total Orders
$order_result = $conn->query("SELECT COUNT(*) AS total_orders FROM orders");
$order_count = $order_result->fetch_assoc()['total_orders'];

// Revenue (only paid orders)
$revenue_result = $conn->query("
    SELECT SUM(total_amount) AS total_revenue 
    FROM orders 
    WHERE status = 'paid'
");
$revenue = $revenue_result->fetch_assoc()['total_revenue'] ?? 0;

?>

<main>
    <div class="container">
    <div class="header">
        <a href="../auth/login.php"><button class="back-btn">← Back to Login</button></a>
        <h1>Admin Dashboard</h1>
        <p>👋 Welcome, <?php echo $_SESSION['username']; ?>!</p>
        <p>Manage your marketplace platform</p>
    </div>

    <!-- Tabs -->
    <div class="card">
        <div class="tabs">
        <button class="tab active" onclick="showTab('overview')">Overview</button>
        <button class="tab" onclick="showTab('users')">Users</button>
        <button class="tab" onclick="showTab('products')">Products</button>
        <button class="tab" onclick="showTab('orders')">Orders</button>
        </div>

        <div class="content">

            <!-- Overview -->
            <div id="overview" class="tab-content active">
                <h3>Overview</h3>
                <div class="grid">
                    <div class="stat-card">
                        <p>Total Users</p>
                        <h2><?php echo $user_count?></h2>
                    </div>
                    <div class="stat-card">
                        <p>Products</p>
                        <h2><?php echo $product_count?></h2>
                    </div>
                    <div class="stat-card">
                        <p>Orders</p>
                        <h2><?php echo $order_count?></h2>
                    </div>
                    <div class="stat-card">
                        <p>Revenue</p>
                        <h2>R<?php echo number_format($revenue, 2); ?></h2>
                    </div>
                </div>
            </div>

            

            <!-- Users Management -->
            <?php
            $users = $conn->query("SELECT id, username, email, user_type, status, created_at FROM users ORDER BY created_at DESC");
            ?>

            <div id="users" class="tab-content">
                <h3>User Management</h3>
                <input type="text" placeholder="Search users..." class="input" />

                <table>
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Role</th>
                            <th>Join Date</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                <tbody>

                <?php while($user = $users->fetch_assoc()): ?>
                <tr>
                    <td>
                        <?php echo $user['username']; ?><br>
                        <small><?php echo $user['email']; ?></small>
                    </td>

                    <td><?php echo $user['user_type']; ?></td>

                    <td><?php echo $user['created_at']; ?></td>

                    <td>
                        <?php if ($user['status'] === 'active'): ?>
                                <span class="badge green">Active</span>
                            <?php else: ?>
                                <span class="badge red">Suspended</span>
                            <?php endif; ?>
                    </td>

                    <td>
                        <?php if ($user['status'] === 'active'): ?>
                            <a href="remove_user.php?id=<?php echo $user['id']; ?>"
                            onclick="return confirm('Suspend this user?');">
                            Suspend
                            </a>
                        <?php else: ?>
                            <a href="activate_user.php?id=<?php echo $user['id']; ?>">
                            Activate
                            </a>
                        <?php endif; ?>
                    </td>

                </tr>
                <?php endwhile; ?>

                </tbody>
                </table>

            </div>

            <!-- Product Moderation-->
            <?php
            $products = $conn->query("
                SELECT p.*, u.username, c.name AS category_name
                FROM products p
                JOIN users u ON p.seller_id = u.id
                LEFT JOIN categories c ON p.category_id = c.id
                ORDER BY p.created_at DESC
            ");
            ?>


            <div id="products" class="tab-content">
                <h3>Product Moderation</h3>

                <table>
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Seller</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                <tbody>
                <?php while($product = $products->fetch_assoc()): ?>
                <tr>
                    <td><?php echo $product['title']; ?></td>
                    <td><?php echo $product['username']; ?></td>
                    <td>R<?php echo $product['price']; ?></td>

                    <td>
                        <span class="badge">
                            <?php echo strtoupper($product['status']); ?>
                        </span>
                    </td>

                    <td>
                        <?php if ($product['status'] === 'pending'): ?>
                            <a href="approve_product.php?id=<?php echo $product['id']; ?>">Approve</a>
                            |
                            <a href="remove_product.php?id=<?php echo $product['id']; ?>">Reject</a>
                        <?php elseif ($product['status'] === 'active'): ?>
                            <a href="remove_product.php?id=<?php echo $product['id']; ?>">Remove</a>
                        <?php else: ?>
                            <span>—</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endwhile; ?>
                </tbody>
                </table>
            </div>

            <!-- Orders Management-->
            <?php
            // Get all orders with related data
            $result = $conn->query("
                SELECT o.*, 
                    b.username AS buyer_name,
                    s.username AS seller_name,
                    p.title AS product_title
                FROM orders o
                JOIN users b ON o.buyer_id = b.id
                JOIN users s ON o.seller_id = s.id
                JOIN products p ON o.product_id = p.id
                ORDER BY o.created_at DESC
            ");
            ?>

            <div id="orders" class="tab-content">
                <h3>Order Management</h3>

                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Buyer</th>
                            <th>Seller</th>
                            <th>Amount</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                        <?php while($order = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $order['id']; ?></td>
                            <td><?php echo $order['product_title']; ?></td>
                            <td><?php echo $order['buyer_name']; ?></td>
                            <td><?php echo $order['seller_name']; ?></td>
                            <td>R<?php echo $order['total_amount']; ?></td>

                            <td>
                                <span class="badge <?php echo $order['status']; ?>">
                            </td>

                            <td><?php echo $order['created_at']; ?></td>

                            <td>
                                <form method="POST" action="update_order.php">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">

                                    <select name="status">
                                        <?php
                                        $statuses = ['pending','paid','shipped','delivered','cancelled'];
                                        foreach ($statuses as $s):
                                        ?>
                                            <option value="<?php echo $s; ?>" 
                                                <?php if($order['status'] === $s) echo 'selected'; ?>>
                                                <?php echo ucfirst($s); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>

                                    <button type="submit">Update</button>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<?php
//Start Scripts
include "../includes/scripts.php";
?>