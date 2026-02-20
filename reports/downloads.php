<?php
session_start();
if($_SESSION['rank']!='ADMIN') die("Access Denied");
echo "Reports downloadable as PDF/Excel";
?>
