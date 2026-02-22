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
        $message="❌ User not allowed for deletion.";
    } else {
        $userData=mysqli_fetch_assoc($res);
    }
}

/* ===== SUBMIT DELETE REQUEST ===== */
if(isset($_POST['submit_delete'])){

    $username = mysqli_real_escape_string($conn, $_POST['username']);

    /* ===== Fetch user details ===== */
    $userCheck = mysqli_query($conn,"
        SELECT * FROM users 
        WHERE username='$username'
        AND role IN ('USER','CLERK','ITJCO')
    ");

    if(mysqli_num_rows($userCheck)==0){
        $message="❌ Invalid user selection.";
    }
    else{

        $userData = mysqli_fetch_assoc($userCheck);

        /* ===== Prevent duplicate pending delete request ===== */
        $checkPending = mysqli_query($conn,"
            SELECT id FROM user_requests
            WHERE username='$username'
            AND request_type='DELETE'
            AND status='Pending'
        ");

        if(mysqli_num_rows($checkPending)>0){
            $message="⚠ Delete request already pending for this user.";
        }
        else{

            /* ===== Insert DELETE request ===== */
            mysqli_query($conn,"
                INSERT INTO user_requests
                (request_type,full_name,username,password,army_no,role,requested_by,request_date,status)
                VALUES
                ('DELETE',
                 '{$userData['name']}',
                 '{$userData['username']}',
                 '',
                 '{$userData['army_no']}',
                 '{$userData['role']}',
                 '{$_SESSION['username']}',
                 CURDATE(),
                 'Pending')
            ");

            /* ===== Activity Log ===== */
            mysqli_query($conn,"
                INSERT INTO activity_logs
                (user_username,action_tag,description)
                VALUES
                ('{$_SESSION['username']}',
                 'Delete User',
                 'Requested DELETE for user: $username | Role: {$userData['role']}')
            ");

            $message="⚠ Delete request sent to Admin for approval.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Delete User</title>

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
    border-left:4px solid #ff5252;
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
        0 0 25px rgba(255,82,82,0.6),
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

button{
    width:100%;
    padding:12px;
    background:#ff5252;
    color:#ffffff;
    border:none;
    border-radius:4px;
    font-weight:600;
    cursor:pointer;
    transition:.3s;
}

button:hover{
    background:#d32f2f;
}

.success{
    background:#4d1e1e;
    color:#ffb3b3;
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
        Delete User Request
        <img src="../images/logo.jpg">
    </h1>
</div>

<div class="container">

<div class="card">

<?php if($message!=""){ ?>
<div class="success"><?php echo $message; ?></div>
<?php } ?>

<form method="POST">

<label>Select User to Delete</label>
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

<h3>Confirm Delete</h3>

<form method="POST">

<input type="hidden" name="username" value="<?php echo $userData['username']; ?>">
<input type="hidden" name="full_name" value="<?php echo $userData['name']; ?>">
<input type="hidden" name="army_no" value="<?php echo $userData['army_no']; ?>">
<input type="hidden" name="role" value="<?php echo $userData['role']; ?>">

<p><strong>Username:</strong> <?php echo $userData['username']; ?></p>
<p><strong>Full Name:</strong> <?php echo $userData['name']; ?></p>
<p><strong>Role:</strong> <?php echo $userData['role']; ?></p>
<p><strong>Army No:</strong> <?php echo $userData['army_no']; ?></p>

<button type="submit" name="submit_delete">
Send Delete Request
</button>

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