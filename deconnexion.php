<?php
session_start();
session_destroy();
header("Location: catalogue.php");
exit();
?>
