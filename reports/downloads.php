<?php
session_start();
if($_SESSION['role']!='ADJQM') die("Access Denied");
echo "Reports downloadable as PDF/Excel";
?>
