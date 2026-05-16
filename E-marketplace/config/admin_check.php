<?php
require_once "auth_check.php";

if($_SESSION['user_type'] !== 'admin'){
    header("Location: /pages/index.php");
    exit();

}
?>