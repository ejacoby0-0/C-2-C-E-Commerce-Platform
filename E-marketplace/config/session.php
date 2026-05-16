<?php
//THis file is used to check a user sessions
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>