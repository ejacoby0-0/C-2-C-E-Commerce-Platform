<?php
require_once "../config/auth_check.php";
require_once "../config/db.php";

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username']);
    $bio = trim($_POST['bio']);
    $phone = trim($_POST['phone']);
    $location = trim($_POST['location']);

    // Default image
    $profile_image = null;

    // Upload image
    if (!empty($_FILES['profile_image']['name'])) {

        $uploadDir = "../uploads/profiles/";

        // Create folder if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = time() . "_" . basename($_FILES['profile_image']['name']);

        $targetFile = $uploadDir . $filename;

        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
            $profile_image = $targetFile;
        }
    }

    // Update without image
    if ($profile_image === null) {

        $stmt = $conn->prepare("
            UPDATE users
            SET username = ?, bio = ?, phone = ?, location = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "ssssi",
            $username,
            $bio,
            $phone,
            $location,
            $user_id
        );

    } else {

        // Update with image
        $stmt = $conn->prepare("
            UPDATE users
            SET username = ?, bio = ?, phone = ?, location = ?, profile_image = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "sssssi",
            $username,
            $bio,
            $phone,
            $location,
            $profile_image,
            $user_id
        );
    }

    $stmt->execute();

    // Update session username
    $_SESSION['username'] = $username;

    header("Location: user-dashboard.php");
    exit();
}
?>