<?php
session_start();
include("../config/db.php");

if ($_SESSION['rank'] != 'ADMIN') die("Access Denied");

if(isset($_POST['approve'])){
$id=$_POST['id'];

$req=mysqli_fetch_assoc(mysqli_query($conn,"
SELECT * FROM condemnation_requests WHERE id='$id'
"));

mysqli_query($conn,"
UPDATE equipment SET status='Condemned'
WHERE id='{$req['equipment_id']}'
");

mysqli_query($conn,"
INSERT INTO disposal(equipment_id, disposal_date, reason)
VALUES('{$req['equipment_id']}',CURDATE(),
'Condemned by {$_SESSION['username']} on request of {$req['requested_by']}')
");

mysqli_query($conn,"
UPDATE condemnation_requests
SET status='Approved',
approved_by='{$_SESSION['username']}',
approval_date=CURDATE()
WHERE id='$id'
");
}
?>

<h2>Approve Condemn Requests</h2>

<table border="1">
<?php
$res=mysqli_query($conn,"
SELECT c.id,e.type,e.make
FROM condemnation_requests c
JOIN equipment e ON c.equipment_id=e.id
WHERE c.status='Pending'
");
while($r=mysqli_fetch_assoc($res)){
echo "<tr>
<td>{$r['type']} - {$r['make']}</td>
<td>
<form method='POST'>
<input type='hidden' name='id' value='{$r['id']}'>
<button name='approve'>Approve</button>
</form>
</td>
</tr>";
}
?>
</table>
