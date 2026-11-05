<?php
session_start();

$_SESSION = [];
session_destroy();

header("Location: login.php?message=logged_out");
exit();
?>
