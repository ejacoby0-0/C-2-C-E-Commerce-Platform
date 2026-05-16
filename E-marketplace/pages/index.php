<?php
//THis page doesn't need protect because it frist want the user to see hwat there is before loging in to make use of its features
require_once "../config/session.php"; //This session is only used for users that are not longin/register but want to look around 
require_once "../config/db.php";

$pageTitle = "Home";

include "../includes/header.php";
include "../includes/navbar.php";



// Get ONLY approved products (For recently added projects)
$result = $conn->query("
    SELECT p.*, pi.image_path 
    FROM products p
    LEFT JOIN product_images pi 
        ON p.id = pi.product_id AND pi.is_primary = 1
    WHERE p.status = 'active'
    ORDER BY p.created_at DESC
    LIMIT 12
");


?>


<main>
     <!-- Start Categories of The Month -->
    <section class="categories">
        <h1>Categories</h1>
        <div class="category-container">
            <a href="../pages/shop.php" class="category-card">
                <div class="image-box">
                    <img src="../assets/img/category_img_01.jpg" alt="Women clothes">
                    <div class="overlay">
                        <span>Go shop</span>
                    </div>
                </div>
                <h5>Women Clothes</h5>
            </a>

            <a href="../pages/shop.php" class="category-card">
                <div class="image-box">
                    <img src="../assets/img/category_img_02.jpg" alt="Men clothes">
                    <div class="overlay">
                        <span>Go shop</span>
                    </div>
                </div>
                <h5>Men Clothes</h5>
            </a>

            <a href="../pages/shop.php" class="category-card">
                <div class="image-box">
                    <img src="../assets/img/category_img_03.jpg" alt="Tech">
                    <div class="overlay">
                        <span>Go shop</span>
                    </div>
                </div>
                <h5>Tech</h5>
            </a>

            <a href="../pages/shop.php" class="category-card">
                <div class="image-box">
                    <img src="../assets/img/category_img_04.jpg" alt="Books">
                    <div class="overlay">
                        <span>Go shop</span>
                    </div>
                </div>
                <h5>Books</h5>
            </a>  
                
            <a href="../pages/shop.php" class="category-card">
                <div class="image-box">
                    <img src="../assets/img/category_img_05.jpg" alt="Sports">
                    <div class="overlay">
                        <span>Go shop</span>
                    </div>
                </div>
                <h5>Sports</h5>
            </a>

            <a href="../pages/shop.php" class="category-card">
                <div class="image-box">
                    <img src="../assets/img/category_img_06.jpg" alt="Kids">
                    <div class="overlay">
                        <span>Go shop</span>
                    </div>
                </div>
                <h5>Kids</h5>
            </a>

            <a href="../pages/shop.php" class="category-card">
                <div class="image-box">
                    <img src="../assets/img/category_img_07.jpg" alt="Home">
                    <div class="overlay">
                        <span>Go shop</span>
                    </div>
                </div>
                <h5>Home</h5>
            </a>
           
            <a href="../pages/shop.php" class="category-card">
                <div class="image-box">
                    <img src="../assets/img/category_img_08.jpg" alt="Other">
                    <div class="overlay">
                        <span>Go shop</span>
                    </div>
                </div>
                <h5>Other</h5>
            </a>             
        </div>
    </section>
    <!-- End Categories of The Month -->




    <!-- Start recently added Products -->
     
    <section class="recently-added">
        <h1>Recently Added</h1>
        <div class="products">

            <?php while($product = $result->fetch_assoc()): ?>

                <a href="../pages/product.php?id=<?php echo $product['id']; ?>" class="product-card">
                    
                    <div class="product-image">
                        <img src="<?php echo $product['image_path'] ?? '../assets/img/default-product.jpg'; ?>" alt="">
                    </div>

                    <h2><?php echo $product['title']; ?></h2>
                    <p class="price">R<?php echo $product['price']; ?></p>

                </a>

            <?php endwhile; ?>
        </div>
    </section>
    <!-- End recently added Product -->
</main>



<?php
//Start Footer
include "../includes/footer.php";

//Start Scripts
include "../includes/scripts.php";
?>

