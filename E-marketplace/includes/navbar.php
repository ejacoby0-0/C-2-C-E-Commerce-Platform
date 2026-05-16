<?php
//THis counts how many product on in the cart
$cart_count = 0;

if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT COUNT(*) as count FROM cart WHERE user_id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $cart_count = $stmt->get_result()->fetch_assoc()['count'];
}


$sql = "
    SELECT p.*, pi.image_path
    FROM products p

    LEFT JOIN product_images pi
    ON p.id = pi.product_id
    AND pi.is_primary = 1

    WHERE p.status = 'active'
";

$params = [];
$types = "";

$sql .= " ORDER BY p.created_at DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();

$products = $stmt->get_result();
?>

<main>
    <nav class="navbar">
       
        <a class="logo" href="../pages/index.php">
            <img src="../assets/img/Logo.png" width="200px">
        </a>
        
        <ul class="nav-links">        
            <li class="nav-item">       
                <a class="nav-link" href="../pages/index.php">Home</a>          
            </li>         
            <li class="nav-item">      
                <a class="nav-link" href="../pages/about.php">About</a>       
            </li>       
            <li class="nav-item">
                <a class="nav-link" href="../pages/shop.php">Shop</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="../pages/contact.php">Contact</a>
            </li>
        </ul>

        <ul class="nav-icons">

            <li class="nav-item">
                <!--Search bar-->
                <form class="search-container" action="../pages/shop.php" method="GET">

                    <button type="submit" class="search-btn">
                        <i class="fa fa-search search-icon"></i>
                    </button>

                    <input 
                        type="text"
                        name="search"
                        placeholder="Search products..."
                        class="search-input"
                        value="<?php echo htmlspecialchars($search ?? ''); ?>"
                    >

                </form>
            </li>

            <li class="nav-item">       
                <!--Cart--> 
                <a href="../cart/index.php" class="btn-cart">        
                    <i class="fa fa-fw fa-cart-arrow-down text-dark mr-1"></i>   
                    <!--Updates the cart icon-->
                    <span class="cart-badge">
                         <?php if ($cart_count > 0) echo $cart_count; ?>
                    </span>
                </a>
            </li>

            <li class="nav-item">
                <!--User login or registor-->
                <!-- User Icon Button -->
                <button class="btn-user" id="userIcon" onclick="openModal()">     
                    <i class="fa fa-user" aria-hidden="true"></i>    
                    <span class="icon-label"></span>
                </button>
                <!-- The Modal (Hidden by default) -->
                <div id="loginModal" class="modal">
                    <!-- Modal Content -->
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        
                        <!--Dynamic navbar showing the users who login-->
                        <?php if (isset($_SESSION['user_id'])): ?>

                            <!--Welcome message to the user-->
                            <p>👋 Welcome, <?php echo $_SESSION['username']; ?>!</p>

                            <!-- user options -->
                            <a href="../user/dashboard.php"><button class="cta-button">My profile</button></a>
                            <a href="../user/create-listing.php"><button class="cta-button">Sell Item</button></a>
                            <a href="../auth/logout.php"><button class="cta-button">Logout</button></a>

                        <?php else: ?>

                            <p>Login or registor to get started</p>
                            <a class="nav-link" href="../auth/login.php"><button class="cta-button">Login</button></a>
                            <a class="nav-link" href="../auth/register.php"><button class="cta-button">Register</button></a>

                        <?php endif; ?>
                    </div>
                </div>
             </li>
        </ul>
    </nav>
</main>