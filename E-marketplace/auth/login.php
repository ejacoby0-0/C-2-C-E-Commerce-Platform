<?php
$pageTitle = "Login";

// Stylesheets
include "../includes/header.php";

session_start();
include "../config/db.php";

$errors = [];

$email = "";

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    /*VALIDATION */

    if (empty($email)) {
        $errors[] = "Email is required.";
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    }

    /*LOGIN USER */

    if (empty($errors)) {

        $stmt = $conn->prepare("
            SELECT id, username, password, user_type, status
            FROM users
            WHERE email = ?
        ");

        $stmt->bind_param("s", $email);

        $stmt->execute();

        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {

            /* ACCOUNT SUSPENDED */

            if ($user['status'] === 'suspended') {
                $errors[] = "Your account has been suspended.";
            }

            /* PASSWORD CHECK */

            elseif (password_verify($password, $user['password'])) {

                session_regenerate_id(true); // Session management

                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['user_type'] = $user['user_type'];

                /* ROLE LOGIC */

                switch ($user['user_type']) {

                    case 'admin':
                        header("Location: ../admin/dashboard.php");
                        break;

                    default:
                        header("Location: ../pages/index.php");
                        break;
                }

                exit();
            }

            else {
                $errors[] = "Incorrect password.";
            }

        }

        else {
            $errors[] = "No account found with that email.";
        }
    }
}
?>

<main class="auth-page">

    <div class="login-form-container">

        <h1>Login</h1>

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

        <form id="loginForm" method="POST" action="login.php">

            <!-- Email -->
            <div class="login-form-group">

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Email"
                    value="<?php echo htmlspecialchars($email); ?>"
                >

            </div>

            <!-- Password -->
            <div class="login-form-group">

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Password"
                >

            </div>

            <button type="submit" class="login-btn">
                Login
            </button>

        </form>

        <div class="switch-link">
            Don't have an account?
            <a href="register.php">Register</a>
        </div>

    </div>

</main>