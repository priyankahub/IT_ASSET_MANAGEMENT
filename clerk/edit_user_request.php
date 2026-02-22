<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['role']) || $_SESSION['role']!='CLERK') {
    die("Access Denied");
}

$message="";
$userData=null;

/* ===== FETCH USERS FOR DROPDOWN ===== */
$users=mysqli_query($conn,"
    SELECT username,name,role 
    FROM users 
    WHERE role IN ('CLERK','ITJCO','USER')
");

/* ===== LOAD USER DETAILS ===== */
if(isset($_POST['load_user'])){

    $username=mysqli_real_escape_string($conn,$_POST['username']);

    $res=mysqli_query($conn,"
        SELECT * FROM users 
        WHERE username='$username'
        AND role IN ('CLERK','ITJCO','USER')
    ");

    if(mysqli_num_rows($res)==0){
        $message="❌ User not allowed for editing.";
    } else {
        $userData=mysqli_fetch_assoc($res);
    }
}

/* ===== SUBMIT EDIT REQUEST ===== */
if(isset($_POST['submit_edit'])){

    $original_username=mysqli_real_escape_string($conn,$_POST['original_username']);
    $username=mysqli_real_escape_string($conn,$_POST['username']);
    $full_name=mysqli_real_escape_string($conn,$_POST['full_name']);
    $army_no=mysqli_real_escape_string($conn,$_POST['army_no']);
    $role=mysqli_real_escape_string($conn,$_POST['role']);

    mysqli_query($conn,"
        INSERT INTO user_requests
        (request_type,full_name,username,password,army_no,role,requested_by,request_date,status)
        VALUES
        ('EDIT','$full_name','$username','',
         '$army_no','$role',
         '{$_SESSION['username']}',CURDATE(),'Pending')
    ");

    mysqli_query($conn,"
        INSERT INTO activity_logs
        (user_username,action_tag,description)
        VALUES
        ('{$_SESSION['username']}',
         'Edit User',
         'Requested EDIT for user: $original_username → $username | Role: $role')
    ");

    $message="✅ Edit request sent for Admin approval.";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit User</title>

<style>
body{
    margin:0;
    font-family:Segoe UI,sans-serif;
    background:#0f223a;
    color:#f5f7fa;
}

/* Ribbon */
.ribbon{
    width:100%;
    background:#0c1f33;
    border-bottom:3px solid #d4af37;
    padding:18px 0;
    text-align:center;
    box-shadow:0 5px 25px rgba(0,0,0,0.6);
}

.ribbon h1{
    margin:0;
    font-size:22px;
    display:flex;
    align-items:center;
    justify-content:center;
    gap:18px;
}

.ribbon img{
    height:45px;
}

/* Container */
.container{
    width:95%;
    max-width:950px;
    margin:50px auto;
}

/* Card */
.card{
    background:#162f4f;
    padding:30px;
    border-radius:6px;
    border-left:4px solid #d4af37;
    margin-bottom:30px;
    transition:0.35s ease;
    position:relative;
    overflow:hidden;
}

/* Hover Glow */
.card:hover{
    transform:translateY(-10px);
    background:#1d3a5c;
    box-shadow:
        0 0 25px rgba(212,175,55,0.6),
        0 20px 40px rgba(0,0,0,0.75);
}

/* Sweep */
.card::before{
    content:"";
    position:absolute;
    top:0;
    left:-100%;
    width:100%;
    height:100%;
    background:linear-gradient(
        120deg,
        transparent,
        rgba(255,255,255,0.08),
        transparent
    );
    transition:0.6s;
}

.card:hover::before{
    left:100%;
}

label{
    font-size:13px;
    font-weight:600;
    color:#b8c6db;
}

input,select{
    width:100%;
    padding:10px;
    margin-top:6px;
    margin-bottom:15px;
    border-radius:4px;
    border:1px solid #2c4c73;
    background:#0f223a;
    color:#ffffff;
}

input:focus,select:focus{
    border-color:#d4af37;
    outline:none;
}

button{
    width:100%;
    padding:12px;
    background:#d4af37;
    color:#0f223a;
    border:none;
    border-radius:4px;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#c39c2d;
}

.success{
    background:#1e4d2b;
    color:#a8e6a1;
    padding:12px;
    border-radius:4px;
    margin-bottom:20px;
}

.footer{
    text-align:center;
    margin-top:30px;
}

.footer a{
    text-decoration:none;
    padding:12px 24px;
    background:#d4af37;
    color:#0f223a;
    border-radius:6px;
    margin:0 10px;
}
</style>
</head>

<body>

<div class="ribbon">
    <h1>
        <img src="../images/logo.jpg">
        Edit / Update User
        <img src="../images/logo.jpg">
    </h1>
</div>

<div class="container">

<div class="card">

<?php if($message!=""){ ?>
<div class="success"><?php echo $message; ?></div>
<?php } ?>

<form method="POST">

<label>Select User</label>
<select name="username" required>
<option value="">-- Select User --</option>
<?php while($u=mysqli_fetch_assoc($users)){ ?>
<option value="<?php echo $u['username']; ?>">
<?php echo $u['username']." - ".$u['name']." (".$u['role'].")"; ?>
</option>
<?php } ?>
</select>

<button type="submit" name="load_user">Load Details</button>

</form>

</div>

<?php if($userData){ ?>

<div class="card">

<h3>Edit User Details</h3>

<form method="POST">

<input type="hidden" name="original_username" value="<?php echo $userData['username']; ?>">

<label>Username</label>
<input type="text" name="username" value="<?php echo $userData['username']; ?>" required>

<label>Full Name</label>
<input type="text" name="full_name" value="<?php echo $userData['name']; ?>" required>

<label>Army Number</label>
<input type="text" name="army_no" value="<?php echo $userData['army_no']; ?>" required>

<label>Role</label>
<select name="role" required>
    <option value="CLERK" <?php if($userData['role']=='CLERK') echo 'selected'; ?>>CLERK</option>
    <option value="ITJCO" <?php if($userData['role']=='ITJCO') echo 'selected'; ?>>ITJCO</option>
    <option value="USER" <?php if($userData['role']=='USER') echo 'selected'; ?>>USER</option>
</select>

<button type="submit" name="submit_edit">Send Edit Request</button>

</form>

</div>

<?php } ?>

<div class="footer">
<a href="../dashboard.php">⬅ Back to Dashboard</a>
<a href="../logout.php">Logout</a>
</div>

</div>

</body>
</html>