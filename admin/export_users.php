<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADMIN') {
    die("Access Denied");
}

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=users_list.xls");

echo "Full Name\tUsername\tRank\tID Number\n";

$result = mysqli_query($conn, "
    SELECT name, username, role, army_no FROM users
");

while($row = mysqli_fetch_assoc($result)){
    echo $row['name']."\t".
         $row['username']."\t".
         $row['role']."\t".
         $row['army_no']."\n";
}
exit;
?>
