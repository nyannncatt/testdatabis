<?php
// logout.php - Logout script
session_start();
session_unset();
session_destroy();
header("Location: index.php");
exit;
?>
