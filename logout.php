<?php
session_start();
// Odnastavení session proměných
$_SESSION = array();

// zničení session
session_destroy();

// přesměrování na index
header("Location: index.php");
exit();
?>
