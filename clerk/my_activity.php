<?php
session_start();
include("../config/db.php");

if ($_SESSION['rank'] != 'CLERK') die("Access Denied");

$res = mysqli_query($conn,"
    SELECT * FROM user_requests
    WHERE requested_by='{$_SESSION['username']}'
    ORDER BY request_date DESC
");
?>

<h2>My Activity Log</h2>

<table border="1" cellpadding="5">
<tr>
<th>Type</th>
<th>Username</th>
<th>Status</th>
<th>Approved By</th>
<th>Date</th>
</tr>

<?php
while($r=mysqli_fetch_assoc($res)){
echo "<tr>
<td>{$r['request_type']}</td>
<td>{$r['username']}</td>
<td>{$r['status']}</td>
<td>{$r['approved_by']}</td>
<td>{$r['request_date']}</td>
</tr>";
}
?>
</table>
