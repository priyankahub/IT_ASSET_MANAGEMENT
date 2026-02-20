<?php
session_start();
include("../config/db.php");

if ($_SESSION['rank'] != 'CLERK') die("Access Denied");

if(isset($_POST['submit'])){
foreach($_POST['equipment_ids'] as $eid){
mysqli_query($conn,"
INSERT INTO condemnation_requests
(equipment_id, requested_by, request_date)
VALUES
('$eid','{$_SESSION['username']}',CURDATE())
");
}
}
?>

<h2>Raise Condemnation Request</h2>

<form method="POST">
<?php
$res=mysqli_query($conn,"
SELECT * FROM equipment WHERE status!='Condemned'
");
while($e=mysqli_fetch_assoc($res)){
echo "<input type='checkbox' name='equipment_ids[]'
value='{$e['id']}'> {$e['type']} - {$e['make']}<br>";
}
?>
<br>
<button name="submit">Send for Approval</button>
</form>
