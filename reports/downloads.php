<?php
session_start();
if($_SESSION['role']!='ADMIN') die("Access Denied");
echo "Reports downloadable as PDF/Excel";
?>
