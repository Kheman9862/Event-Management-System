<?php
session_start();

session_destroy();
// unset($_SESSION['user']);

echo "<script type='text/javascript'>window.top.location='./login.php';</script>"; exit;
?>