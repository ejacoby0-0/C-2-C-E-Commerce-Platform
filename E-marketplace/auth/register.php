<?php
$pageTitle = "Register";

// Stylesheets
include "../includes/header.php";
include "../config/db.php";

$errors = [];
$success = "";

$username = "";
$email = "";
$phone = "";
$address = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    // Trim spaces
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $password = $_POST['password'] ?? '';

    /*VALIDATION*/
    if (empty($username)) {
        $errors[] = "Username is required.";
    }

    if (empty($email)) {
        $errors[] = "Email is required.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($phone)) {
        $errors[] = "Phone number is required.";
    }

    if (empty($address)) {
        $errors[] = "Address is required.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }
    elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters long.";
    }

    /*CHECK EMAIL EXISTS*/
    if (empty($errors)) {

        $check = $conn->prepare("
            SELECT id 
            FROM users 
            WHERE email = ?
        ");

        $check->bind_param("s", $email);
        $check->execute();
        $check->store_result();

        if ($check->num_rows > 0) {
            $errors[] = "Email already exists.";
        }
    }

    /*REGISTER USER*/
    if (empty($errors)) {

        // Hash password AFTER validation
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("
            INSERT INTO users 
            (username, email, phone, address, password)
            VALUES (?, ?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "sssss",
            $username,
            $email,
            $phone,
            $address,
            $hashedPassword
        );

        if ($stmt->execute()) {
            $success = "Registration successful!";
        } else {
            $errors[] = "Something went wrong. Please try again.";
        }
    }
}
?>

<main class="auth-page">

    <div class="register-form-container">

        <h1>Register</h1>

        <!-- SUCCESS MESSAGE -->
        <?php if (!empty($success)): ?>

            <div class="success-box">
                <?php echo $success; ?>
            </div>

        <?php endif; ?>

        <!-- ERROR MESSAGES -->
        <?php if (!empty($errors)): ?>

            <div class="error-box">

                <?php foreach ($errors as $error): ?>

                    <p class="error-message">
                        <?php echo $error; ?>
                    </p>

                <?php endforeach; ?>

            </div>

        <?php endif; ?>

        <form id="registerForm" method="POST" action="./register.php">

            <!-- Username -->
            <div class="register-form-group">

                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Username"
                    value="<?php echo htmlspecialchars($username); ?>"
                >

            </div>

            <!-- Email -->
            <div class="register-form-group">

                <input
                    type="text"
                    id="email"
                    name="email"
                    placeholder="Email"
                    value="<?php echo htmlspecialchars($email); ?>"
                >

            </div>

            <!-- Phone -->
            <div class="register-form-group">

                <input
                    type="tel"
                    id="phone"
                    name="phone"
                    placeholder="Phone Number"
                    value="<?php echo htmlspecialchars($phone); ?>"
                >

            </div>

            <!-- Address -->
            <div class="register-form-group">

                <input
                    type="text"
                    id="address"
                    name="address"
                    placeholder="Address"
                    value="<?php echo htmlspecialchars($address); ?>"
                >

            </div>

            <!-- Password -->
            <div class="register-form-group">

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Password"
                >

            </div>

            <button type="submit" class="register-btn">
                Register
            </button>

        </form>

        <div class="switch-link">
            Already have an account?
            <a href="login.php">Login</a>
        </div>

    </div>

</main>