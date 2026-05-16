<?php
require_once "../config/auth_check.php";
require_once "../config/db.php";

$user_id = $_SESSION['user_id'];
$product_id = $_GET['id'] ?? 0;

// Get product (ONLY if owned by user)
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ? AND seller_id = ?");
$stmt->bind_param("ii", $product_id, $user_id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();

if (!$product) {
    die("Product not found or not yours.");
}

// Update logic
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $price = $_POST['price'];
    $description = $_POST['description'];

    $stmt = $conn->prepare("
        UPDATE products 
        SET title=?, price=?, description=? 
        WHERE id=? AND seller_id=?
    ");
    $stmt->bind_param("sdsii", $title, $price, $description, $product_id, $user_id);

    if ($stmt->execute()) {
        header("Location: my-listings.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
</head>
<body>

    <h1>Edit Product</h1>

    <form method="POST">
        <input type="text" name="title" value="<?php echo $product['title']; ?>" required>
        <input type="number" step="0.01" name="price" value="<?php echo $product['price']; ?>" required>
        <textarea name="description"><?php echo $product['description']; ?></textarea>

        <button type="submit">Update</button>
        
    </form>
</body>
</html>