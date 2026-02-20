<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['rank']) || $_SESSION['rank'] != 'ADMIN') {
    die("Access Denied");
}

/* ================= FETCH USER FOR EDIT ================= */
$editUser = null;

if (isset($_GET['edit'])) {
    $editId = mysqli_real_escape_string($conn, $_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM users WHERE id='$editId'");
    $editUser = mysqli_fetch_assoc($result);
}

/* ================= UPDATE USER ================= */
if (isset($_POST['update_user'])) {

    $id       = mysqli_real_escape_string($conn, $_POST['id']);
    $name     = mysqli_real_escape_string($conn, $_POST['name']);
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $rank     = mysqli_real_escape_string($conn, $_POST['rank']);
    $army_no  = mysqli_real_escape_string($conn, $_POST['army_no']);

    mysqli_query($conn,"
        UPDATE users
        SET name='$name',
            username='$username',
            rank='$rank',
            army_no='$army_no'
        WHERE id='$id'
    ");

    header("Location: manage_users.php?msg=updated");
    exit;
}

/* ================= DELETE USER ================= */
if (isset($_POST['delete_user'])) {

    $id = mysqli_real_escape_string($conn, $_POST['id']);

    mysqli_query($conn,"
        DELETE FROM users
        WHERE id='$id'
    ");

    header("Location: manage_users.php");
    exit;
}

/* ================= FETCH ALL USERS ================= */
$users = mysqli_query($conn,"
    SELECT * FROM users
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Users</title>

<style>
body{
    margin:0;
    font-family:Arial,sans-serif;
    background:linear-gradient(135deg,#1e3c72,#2a5298);
}
.container{
    width:95%;
    max-width:1100px;
    margin:40px auto;
}
.card{
    background:#fff;
    padding:25px;
    border-radius:12px;
    box-shadow:0 6px 20px rgba(0,0,0,0.2);
}
h2{
    color:#2a5298;
}
table{
    width:100%;
    border-collapse:collapse;
}
th, td{
    padding:12px;
    text-align:center;
}
th{
    background:#f2f2f2;
}
tr:nth-child(even){
    background:#fafafa;
}
.edit-btn{
    background:#ffc107;
    padding:6px 10px;
    border:none;
    border-radius:5px;
    cursor:pointer;
}
.delete-btn{
    background:#dc3545;
    color:#fff;
    padding:6px 10px;
    border:none;
    border-radius:5px;
    cursor:pointer;
}
.edit-card{
    background:#fff;
    padding:20px;
    border-radius:10px;
    margin-bottom:20px;
    box-shadow:0 4px 12px rgba(0,0,0,0.15);
}
.edit-card input,
.edit-card select{
    width:100%;
    padding:8px;
    margin:8px 0 12px;
}
.edit-card button{
    background:#2a5298;
    color:#fff;
    padding:10px;
    border:none;
    border-radius:6px;
}
.message{
    background:#d4edda;
    color:#155724;
    padding:10px;
    border-radius:6px;
    margin-bottom:15px;
}
.footer{
    margin-top:20px;
    text-align:center;
}
.footer a{
    color:#fff;
    text-decoration:none;
    padding:8px 15px;
    background:rgba(255,255,255,0.2);
    border-radius:20px;
}
</style>
</head>

<body>

<div class="container">

<div class="card">

<h2>Manage Users</h2>

<?php if (isset($_GET['msg']) && $_GET['msg']=='updated') { ?>
<div class="message">✅ User updated successfully.</div>
<?php } ?>

<?php if ($editUser) { ?>
<div class="edit-card">
<h3>Edit User</h3>

<form method="POST">
<input type="hidden" name="id" value="<?php echo $editUser['id']; ?>">

<label>Full Name</label>
<input type="text" name="name" value="<?php echo $editUser['name']; ?>" required>

<label>Username</label>
<input type="text" name="username" value="<?php echo $editUser['username']; ?>" required>

<label>Army Number</label>
<input type="text" name="army_no" value="<?php echo $editUser['army_no']; ?>" required>

<label>Rank</label>
<select name="rank" required>
<option value="ADMIN" <?php if($editUser['rank']=='ADMIN') echo 'selected'; ?>>ADMIN</option>
<option value="CO" <?php if($editUser['rank']=='CO') echo 'selected'; ?>>CO</option>
<option value="ITJCO" <?php if($editUser['rank']=='ITJCO') echo 'selected'; ?>>ITJCO</option>
<option value="CLERK" <?php if($editUser['rank']=='CLERK') echo 'selected'; ?>>CLERK</option>
<option value="USER" <?php if($editUser['rank']=='USER') echo 'selected'; ?>>USER</option>
</select>

<button type="submit" name="update_user">Save Changes</button>
</form>
</div>
<?php } ?>

<table>
<tr>
<th>Full Name</th>
<th>Username</th>
<th>Rank</th>
<th>Army Number</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($users)) { ?>
<tr>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['username']; ?></td>
<td><?php echo $row['rank']; ?></td>
<td><?php echo $row['army_no']; ?></td>
<td>
<a href="manage_users.php?edit=<?php echo $row['id']; ?>">
<button class="edit-btn">Edit</button>
</a>

<form method="POST" style="display:inline;">
<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
<button type="submit" name="delete_user" class="delete-btn">Delete</button>
</form>
</td>
</tr>
<?php } ?>

</table>

</div>

<div class="footer">
<a href="../dashboard.php">⬅ Back to Dashboard</a>
</div>

</div>

</body>
</html>