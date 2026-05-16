<?php
require_once "../config/session.php"; //This session is only used for users that are not longin/register but want to look around 
require_once "../config/db.php";

include "../includes/header.php";
include "../includes/navbar.php";

/*FILTER VALUES*/
$search = $_GET['search'] ?? '';
$category = $_GET['category'] ?? '';
$condition = $_GET['condition'] ?? '';
$min_price = $_GET['min_price'] ?? '';
$max_price = $_GET['max_price'] ?? '';

/*BASE SQL*/
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

/*SEARCH FILTER*/

if (!empty($search)) {
    $sql .= " AND p.title LIKE ?";
    $params[] = "%$search%";
    $types .= "s";
}

/*CATEGORY FILTER*/

if (!empty($category)) {
    $sql .= " AND p.category_id = ?";
    $params[] = $category;
    $types .= "i";
}

/*CONDITION FILTER*/

if (!empty($condition)) {
    $sql .= " AND p.product_condition = ?";
    $params[] = $condition;
    $types .= "s";
}

/*PRICE FILTERS*/

if (!empty($min_price)) {
    $sql .= " AND p.price >= ?";
    $params[] = $min_price;
    $types .= "d";
}

if (!empty($max_price)) {
    $sql .= " AND p.price <= ?";
    $params[] = $max_price;
    $types .= "d";
}

/*ORDER*/
$sql .= " ORDER BY p.created_at DESC";

/* PREPARE*/
$stmt = $conn->prepare($sql);

if (!empty($params)) {
    $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$result = $stmt->get_result();

/*GET CATEGORIES*/
$categories = $conn->query("
    SELECT * FROM categories ORDER BY name ASC
");

/*Suggestion after no products relating to the search*/ 
$stmt = $conn->prepare("
    SELECT p.*, pi.image_path
    FROM products p

    LEFT JOIN product_images pi
    ON p.id = pi.product_id
    AND pi.is_primary = 1

    WHERE p.status = 'active'

    ORDER BY RAND()

    LIMIT 4
");

$stmt->execute();

$suggested = $stmt->get_result();
?>

<main>

<!-- Sidebar/menu -->
<div class="shop-container">

    <!-- Sidebar -->
    <aside class="shop-sidebar">

        <form method="GET" action="shop.php">

            <h2>Filters</h2>

            <!-- Search -->
            <div class="filter-group">
                <h3>Search</h3>

                <input
                    type="text"
                    name="search"
                    placeholder="Search products..."
                    value="<?php echo htmlspecialchars($search); ?>"
                >
            </div>

            <!-- Categories -->
            <div class="filter-group">

                <h3>Categories</h3>

                <select name="category">

                    <option value="">All Categories</option>

                    <?php while($cat = $categories->fetch_assoc()): ?>

                        <option
                            value="<?php echo $cat['id']; ?>"
                            <?php if($category == $cat['id']) echo 'selected'; ?>
                        >
                            <?php echo $cat['name']; ?>
                        </option>

                    <?php endwhile; ?>

                </select>

            </div>

            <!-- Condition -->
            <div class="filter-group">

                <h3>Condition</h3>

                <select name="condition">

                    <option value="">All</option>

                    <option value="new"
                        <?php if($condition == 'new') echo 'selected'; ?>>
                        New
                    </option>

                    <option value="like new"
                        <?php if($condition == 'like new') echo 'selected'; ?>>
                        Like New
                    </option>

                    <option value="good"
                        <?php if($condition == 'good') echo 'selected'; ?>>
                        Good
                    </option>

                    <option value="fair"
                        <?php if($condition == 'fair') echo 'selected'; ?>>
                        Fair
                    </option>

                </select>

            </div>

            <!-- Price -->
            <div class="filter-group">

                <h3>Price</h3>

                <input
                    type="number"
                    name="min_price"
                    placeholder="Min"
                    value="<?php echo htmlspecialchars($min_price); ?>"
                >

                <input
                    type="number"
                    name="max_price"
                    placeholder="Max"
                    value="<?php echo htmlspecialchars($max_price); ?>"
                >

            </div>

            <!-- Buttons -->
            <div class="filter-group">
                <button type="submit" class="filter-btn">
                    Apply Filters
                </button>
                <a href="shop.php" class="clear-btn">Clear</a>
            </div>
        </form>

    </aside>
    <!-- Products -->

    <!-- Main Page Content -->
    <section class="shop-products">
        <h1>Welcome to the product page</h1>

        <?php if(!empty($search)): ?>

            <div class="search-results-header">

                <h2>
                    Search Results for:
                    "<?php echo htmlspecialchars($search); ?>"
                </h2>

            </div>

        <?php endif; ?>
    
        <?php if($result->num_rows > 0): ?>

            <div class="products-grid">
                <!-- Product Card -->
                <?php while($product = $result->fetch_assoc()): ?>
                    <a href="../pages/product.php?id=<?php echo $product['id']; ?>" class="product-card">
                        <img src="<?php echo $product['image_path'] ?? '../assets/img/default-product.jpg'; ?>" width="150">
                        <h3><?php echo $product['title']; ?></h3>
                        <p>R<?php echo $product['price']; ?></p>
                    </a>
                <?php endwhile; ?>
            </div>

        <?php else: ?>

            <div class="no-results">

                <h2>No products found</h2>

                <p>
                    Try searching for something else.
                </p>
            </div>


            <div class="suggested-products">

                <h3>You may also like</h3>

                <div class="products-grid">

                    <?php while($item = $suggested->fetch_assoc()): ?>

                        <a href="product.php?id=<?php echo $item['id']; ?>"
                            class="product-card">

                            <img src="<?php echo $item['image_path']; ?>">

                            <h4><?php echo $item['title']; ?></h4>

                            <p>R<?php echo $item['price']; ?></p>

                        </a>
                    <?php endwhile; ?>
                </div>
            </div>
        <?php endif; ?>
    </section>
</main>

   
<?php
include "../includes/footer.php";
include "../includes/scripts.php";
?>