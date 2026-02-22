<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'ADMIN') {
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
    $role     = mysqli_real_escape_string($conn, $_POST['role']);
    $army_no  = mysqli_real_escape_string($conn, $_POST['army_no']);

    mysqli_query($conn,"
        UPDATE users
        SET name='$name',
            username='$username',
            role='$role',
            army_no='$army_no'
        WHERE id='$id'
    ");

    header("Location: manage_users.php?msg=updated");
    exit;
}

/* ================= DELETE USER ================= */
if (isset($_POST['delete_user'])) {

    $id = mysqli_real_escape_string($conn, $_POST['id']);

    // EXTRA SECURITY: Prevent deleting ADMIN & CO from backend also
    $checkRank = mysqli_query($conn,"SELECT role FROM users WHERE id='$id'");
    $roleData = mysqli_fetch_assoc($checkRank);

    if ($roleData['role'] != 'ADMIN' && $roleData['role'] != 'CO') {
        mysqli_query($conn,"DELETE FROM users WHERE id='$id'");
    }

    header("Location: manage_users.php");
    exit;
}

/* ================= FETCH ALL USERS ================= */
$users = mysqli_query($conn,"SELECT * FROM users ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Manage Users</title>

<style>
body{
    margin:0;
    font-family:Segoe UI, sans-serif;
    background:#0f223a;
    color:#f5f7fa;
}

/* HEADER */
.header{
    padding:25px 0;
    font-size:22px;
    font-weight:600;
    background:#122944;
    border-bottom:3px solid #d4af37;
    text-align:center;
}

/* CONTAINER */
.container{
    width:95%;
    max-width:1200px;
    margin:50px auto;
}

/* CARD */
.card{
    background:#162f4f;
    padding:30px;
    border-radius:6px;
    border-left:4px solid #d4af37;
}

h2{
    margin-bottom:25px;
    font-weight:600;
}

/* MESSAGE */
.message{
    background:#1e4d2b;
    color:#a8e6a1;
    padding:12px;
    border-radius:4px;
    margin-bottom:20px;
}

/* EDIT CARD */
.edit-card{
    background:#1b3557;
    padding:20px;
    border-radius:6px;
    margin-bottom:30px;
    border-left:3px solid #d4af37;
}

.edit-card input,
.edit-card select{
    width:100%;
    padding:10px;
    margin-top:6px;
    border-radius:4px;
    border:1px solid #2c4c73;
    background:#0f223a;
    color:#fff;
}

.edit-card button{
    margin-top:15px;
    padding:10px 18px;
    background:#d4af37;
    color:#0f223a;
    border:none;
    border-radius:4px;
    font-weight:600;
    cursor:pointer;
}

table{
    width:100%;
    border-collapse:collapse;
}

th{
    background:#122944;
    padding:12px;
    text-align:center;
}

td{
    padding:12px;
    text-align:center;
    border-bottom:1px solid #2c4c73;
}

tr:hover{
    background:#1a355a;
}

/* BUTTONS */
.edit-btn{
    background:#ffd166;
    color:#000;
    padding:6px 12px;
    border:none;
    border-radius:4px;
    cursor:pointer;
    font-weight:600;
}

.edit-btn:hover{
    background:#f4b942;
    transform:scale(1.05);
}

.delete-btn{
    background:#ff4d6d;
    color:#fff;
    padding:6px 12px;
    border:none;
    border-radius:4px;
    cursor:pointer;
    font-weight:600;
}

.delete-btn:hover{
    background:#ff1e4d;
    transform:scale(1.05);
}

.footer{
    margin-top:40px;
    text-align:center;
}

.footer a{
    text-decoration:none;
    padding:12px 24px;
    background:#d4af37;
    color:#0f223a;
    border-radius:6px;
    font-weight:600;
}
</style>

<script>
// DELETE CONFIRMATION
function confirmDelete() {
    return confirm("⚠ WARNING!\n\nAre you sure you want to perform DELETE?\nThis action is dangerous and CANNOT be reversed!");
}

// EDIT CONFIRMATION
function confirmEdit() {
    return confirm("⚠ Attention!\n\nYou are going to EDIT an existing user.\nPlease double-check the details before proceeding.\n\nAre you sure?");
}
</script>

</head>

<body>

<div class="header">
INF BN – USER MANAGEMENT CONTROL
</div>

<div class="container">
<div class="card">

<h2>Manage Users</h2>

<?php if (isset($_GET['msg']) && $_GET['msg']=='updated') { ?>
<div class="message">User updated successfully.</div>
<?php } ?>

<?php if ($editUser) { ?>
<div class="edit-card">
<h3>Edit User</h3>

<form method="POST" onsubmit="return confirmEdit();">
<input type="hidden" name="id" value="<?php echo $editUser['id']; ?>">

<label>Full Name</label>
<input type="text" name="name" value="<?php echo $editUser['name']; ?>" required>

<label>Username</label>
<input type="text" name="username" value="<?php echo $editUser['username']; ?>" required>

<label>Army Number</label>
<input type="text" name="army_no" value="<?php echo $editUser['army_no']; ?>" required>

<label>Role</label>
<select name="role" required>
<option value="ADMIN" <?php if($editUser['role']=='ADMIN') echo 'selected'; ?>>ADMIN</option>
<option value="CO" <?php if($editUser['role']=='CO') echo 'selected'; ?>>CO</option>
<option value="ITJCO" <?php if($editUser['role']=='ITJCO') echo 'selected'; ?>>ITJCO</option>
<option value="CLERK" <?php if($editUser['role']=='CLERK') echo 'selected'; ?>>CLERK</option>
<option value="USER" <?php if($editUser['role']=='USER') echo 'selected'; ?>>USER</option>
</select>

<button type="submit" name="update_user">Save Changes</button>
</form>
</div>
<?php } ?>

<table>
<tr>
<th>Full Name</th>
<th>Username</th>
<th>Role</th>
<th>Army Number</th>
<th>Action</th>
</tr>

<?php while($row = mysqli_fetch_assoc($users)) { ?>
<tr>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['username']; ?></td>
<td><?php echo $row['role']; ?></td>
<td><?php echo $row['army_no']; ?></td>
<td>

<a href="manage_users.php?edit=<?php echo $row['id']; ?>">
<button class="edit-btn">Edit</button>
</a>

<?php if($row['role'] != 'ADMIN' && $row['role'] != 'CO') { ?>
<form method="POST" style="display:inline;" onsubmit="return confirmDelete();">
<input type="hidden" name="id" value="<?php echo $row['id']; ?>">
<button type="submit" name="delete_user" class="delete-btn">Delete</button>
</form>
<?php } ?>

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