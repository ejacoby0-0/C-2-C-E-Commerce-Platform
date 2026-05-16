<?php
require_once "../config/auth_check.php";
require_once "../config/db.php";

$user_id = $_SESSION['user_id'];

$shipping_address = trim($_POST['shipping_address']);
$payment_method = $_POST['payment_method'];

if(empty($shipping_address)){
    die("Shipping address required.");
}

/* GET CART */
$stmt = $conn->prepare("
    SELECT * FROM cart WHERE user_id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$cart = $stmt->get_result();

$payment_ref = "PAY-" . time();

while ($item = $cart->fetch_assoc()) {

    /* PRODUCT INFO */
    $stmt2 = $conn->prepare("
        SELECT * FROM products WHERE id = ?
    ");

    $stmt2->bind_param("i", $item['product_id']);
    $stmt2->execute();

    $product = $stmt2->get_result()->fetch_assoc();

    /* CREATE ORDER */
    $stmt3 = $conn->prepare("
        INSERT INTO orders (
            buyer_id,
            seller_id,
            product_id,
            total_amount,
            shipping_address,
            payment_reference,
            payment_method,
            status
        )

        VALUES (?, ?, ?, ?, ?, ?, ?, 'paid')
    ");

    $stmt3->bind_param(
        "iiidsss",
        $user_id,
        $product['seller_id'],
        $product['id'],
        $product['price'],
        $shipping_address,
        $payment_ref,
        $payment_method
    );

    $stmt3->execute();

    /* MARK PRODUCT SOLD */
    $stmt4 = $conn->prepare("
        UPDATE products
        SET status = 'sold'
        WHERE id = ?
    ");

    $stmt4->bind_param("i", $product['id']);
    $stmt4->execute();
}

/* BUY NOW (single product) */

if (isset($_POST['product_id'])) {

    $product_id = $_POST['product_id'];

    $stmt = $conn->prepare("
        SELECT *
        FROM products
        WHERE id = ?
    ");

    $stmt->bind_param("i", $product_id);
    $stmt->execute();

    $product = $stmt->get_result()->fetch_assoc();

    $payment_ref = "PAY-" . time();

    $shipping_address = $_POST['shipping_address'];

    $stmt = $conn->prepare("
        INSERT INTO orders (
            buyer_id,
            seller_id,
            product_id,
            total_amount,
            shipping_address,
            payment_reference,
            status
        )
        VALUES (?, ?, ?, ?, ?, ?, 'paid')
    ");

    $stmt->bind_param(
        "iiidss",
        $user_id,
        $product['seller_id'],
        $product['id'],
        $product['price'],
        $shipping_address,
        $payment_ref
    );

    $stmt->execute();

    // Mark product sold
    $stmt = $conn->prepare("
        UPDATE products
        SET status = 'sold'
        WHERE id = ?
    ");

    $stmt->bind_param("i", $product_id);
    $stmt->execute();

}

/* CLEAR CART */
$stmt = $conn->prepare("
    DELETE FROM cart WHERE user_id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

/* SUCCESS MESSAGE */
$_SESSION['success_message'] = "Your order has been placed successfully!";


header("Location: order-success.php");
exit();
?>