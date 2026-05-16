<?php
require_once "../config/auth_check.php";
require_once "../config/db.php";

$pageTitle = "Create Listing";

include "../includes/header.php";

//Initalising the category varible
$categories = $conn->query("SELECT id, name FROM categories");

//Making sure that if the server session is on 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $product_condition = $_POST['product_condition'];
    $price = $_POST['price'];
    $location = $_POST['location'];
    $category_id = $_POST['category_id'];

    $seller_id = $_SESSION['user_id'];

    // Insert product
    $stmt = $conn->prepare("
        INSERT INTO products (seller_id, category_id, title, description, product_condition, price, location, status)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending')
    ");

    $stmt->bind_param("iisssds", $seller_id, $category_id, $title, $description, $product_condition, $price, $location);

    $stmt->execute();

    $product_id = $stmt->insert_id;

    //Handle image upload
    if (!empty($_FILES['images']['name'][0])) {

        $uploadDir = "../uploads/products/";

        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {

            $fileName = time() . "_" . $_FILES['images']['name'][$key];
            $filePath = $uploadDir . $fileName;

            if (move_uploaded_file($tmp_name, $filePath)) {

                $isPrimary = ($key === 0) ? 1 : 0;

                $stmtImg = $conn->prepare("
                    INSERT INTO product_images (product_id, image_path, is_primary)
                    VALUES (?, ?, ?)
                ");

                $stmtImg->bind_param("isi", $product_id, $filePath, $isPrimary);
                $stmtImg->execute();
            }
        }
    }

    echo "Product submitted for approval!";
}
?>


<main class="listing-page">

    <!-- HERO -->
    <section class="listing-hero">

        <h1>Create Listing</h1>

        <p>
            Add your product details and start selling to buyers across South Africa.
        </p>

    </section>

    <!-- FORM SECTION -->
    <section class="listing-container">

        <div class="listing-form-box">

            <form 
                id="create-listingForm"
                class="listing-form"
                method="POST"
                enctype="multipart/form-data"
            >

                <!-- Images -->
                <div class="listing-group">

                    <label>Product Images</label>

                    <input 
                        type="file"
                        name="images[]"
                        multiple
                    >

                    <small>
                        Upload high-quality photos for better visibility.
                    </small>

                </div>

                <!-- Title -->
                <div class="listing-group">

                    <label>Product Title</label>

                    <input
                        type="text"
                        name="title"
                        placeholder="Enter product title"
                        required
                    >

                </div>

                <!-- Description -->
                <div class="listing-group">

                    <label>Description</label>

                    <textarea
                        name="description"
                        placeholder="Describe your item..."
                        required
                    ></textarea>

                </div>

                <!-- Condition -->
                <div class="listing-group">

                    <label>Condition</label>

                    <select name="product_condition" required>

                        <option value="">Select condition</option>
                        <option value="new">New</option>
                        <option value="like new">Like New</option>
                        <option value="good">Good</option>
                        <option value="fair">Fair</option>

                    </select>

                </div>

                <!-- Price -->
                <div class="listing-group">

                    <label>Price (R)</label>

                    <input
                        type="number"
                        step="0.01"
                        name="price"
                        placeholder="Enter price"
                        required
                    >

                </div>

                <!-- Location -->
                <div class="listing-group">

                    <label>Location</label>

                    <input
                        type="text"
                        name="location"
                        placeholder="Cape Town, Johannesburg..."
                    >

                </div>

                <!-- Category -->
                <div class="listing-group">

                    <label>Category</label>

                    <select name="category_id" required>

                        <option value="">Select category</option>

                        <?php while($cat = $categories->fetch_assoc()): ?>

                            <option value="<?php echo $cat['id']; ?>">

                                <?php echo $cat['name']; ?>

                            </option>

                        <?php endwhile; ?>

                    </select>

                </div>

                <!-- Submit -->
                <button type="submit" class="create-btn">

                    Create Listing

                </button>

            </form>

        </div>

    </section>

</main>