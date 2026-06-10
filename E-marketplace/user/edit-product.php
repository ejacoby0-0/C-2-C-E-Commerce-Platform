<?php
require_once "../config/auth_check.php";
require_once "../config/db.php";

$pageTitle = "Edit Product";

include "../includes/header.php";

$user_id = $_SESSION['user_id'];
$product_id = $_GET['id'] ?? 0;

/* Get Product */
$stmt = $conn->prepare("
    SELECT *
    FROM products
    WHERE id = ? AND seller_id = ?
");
$stmt->bind_param("ii", $product_id, $user_id);
$stmt->execute();

$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die("Product not found or not yours.");
}

/* Update Product */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $product_condition = $_POST['product_condition'];
    $location = $_POST['location'];

    $stmt = $conn->prepare("
        UPDATE products
        SET title = ?,
            price = ?,
            description = ?,
            product_condition = ?,
            location = ?
        WHERE id = ?
        AND seller_id = ?
    ");

    $stmt->bind_param(
        "sdsssii",
        $title,
        $price,
        $description,
        $product_condition,
        $location,
        $product_id,
        $user_id
    );

    if ($stmt->execute()) {

        header("Location: my-listings.php");
        exit();
    }
}
?>

<main class="listing-page">

    <!-- Hero -->
    <section class="listing-hero">

        <h1>Edit Listing</h1>

        <p>
            Update your product information and keep your listing accurate.
        </p>

    </section>

    <!-- Form -->
    <section class="listing-container">

        <div class="listing-form-box">

            <form method="POST" class="listing-form">

                <!-- Product Title -->
                <div class="listing-group">

                    <label>Product Title</label>

                    <input
                        type="text"
                        name="title"
                        value="<?php echo htmlspecialchars($product['title']); ?>"
                        required
                    >

                </div>

                <!-- Description -->
                <div class="listing-group">

                    <label>Description</label>

                    <textarea
                        name="description"
                        required
                    ><?php echo htmlspecialchars($product['description']); ?></textarea>

                </div>

                <!-- Condition -->
                <div class="listing-group">

                    <label>Condition</label>

                    <select name="product_condition" required>

                        <option value="new"
                            <?php if($product['product_condition'] == 'new') echo 'selected'; ?>>
                            New
                        </option>

                        <option value="like new"
                            <?php if($product['product_condition'] == 'like new') echo 'selected'; ?>>
                            Like New
                        </option>

                        <option value="good"
                            <?php if($product['product_condition'] == 'good') echo 'selected'; ?>>
                            Good
                        </option>

                        <option value="fair"
                            <?php if($product['product_condition'] == 'fair') echo 'selected'; ?>>
                            Fair
                        </option>

                    </select>

                </div>

                <!-- Price -->
                <div class="listing-group">

                    <label>Price (R)</label>

                    <input
                        type="number"
                        step="0.01"
                        name="price"
                        value="<?php echo $product['price']; ?>"
                        required
                    >

                </div>

                <!-- Location -->
                <div class="listing-group">

                    <label>Location</label>

                    <input
                        type="text"
                        name="location"
                        value="<?php echo htmlspecialchars($product['location']); ?>"
                    >

                </div>

                <!-- Submit -->
                <button type="submit" class="create-btn">

                    Update Listing

                </button>

            </form>

        </div>

    </section>

</main>

<?php include "../includes/footer.php"; ?>