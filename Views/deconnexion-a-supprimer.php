<?php
session_start();
unset($_SESSION['token']);
session_destroy();
header("location:../public/index.php?controller=creation&action=index");
?>