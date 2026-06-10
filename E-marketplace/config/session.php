<?php
//THis file is used to check a user sessions

//Prevents JS from stealing session cookies
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();

/* Session timeout (30 minutes) */
$timeout = 1800;

if (isset($_SESSION['last_activity'])) {

    if ((time() - $_SESSION['last_activity']) > $timeout) {

        session_unset();
        session_destroy();

        header("Location: ../auth/login.php?expired=1");
        exit();
    }
}

$_SESSION['last_activity'] = time();
?>