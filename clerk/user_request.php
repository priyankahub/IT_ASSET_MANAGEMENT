<?php
session_start();
include("../config/db.php");

if (!isset($_SESSION['rank']) || $_SESSION['rank'] != 'CLERK') {
    die("Access Denied");
}

$message = "";

if (isset($_POST['submit_request'])) {

    $request_type = $_POST['request_type'];
    $full_name    = mysqli_real_escape_string($conn, $_POST['full_name']);
    $username     = mysqli_real_escape_string($conn, $_POST['username']);
    $army_no      = mysqli_real_escape_string($conn, $_POST['army_no']);
    $rank         = mysqli_real_escape_string($conn, $_POST['rank']);
    $requested_by = $_SESSION['username'];
    $today        = date('Y-m-d');

    // Default password for clerk-created users
    $password = "Password@#123";

    // Username uniqueness
    $checkUser = mysqli_query($conn,"
        SELECT id FROM users WHERE username='$username'
        UNION
        SELECT id FROM user_requests WHERE username='$username'
    ");

    // Army No uniqueness
    $checkArmy = mysqli_query($conn,"
        SELECT id FROM users WHERE army_no='$army_no'
        UNION
        SELECT id FROM user_requests WHERE army_no='$army_no'
    ");

    if (mysqli_num_rows($checkUser) > 0) {
        $message = "❌ Username already exists.";
    }
    elseif (mysqli_num_rows($checkArmy) > 0) {
        $message = "❌ Army Number already exists.";
    }
    else {

        mysqli_query($conn,"
            INSERT INTO user_requests
            (request_type, full_name, username, password, army_no, rank, requested_by, request_date, status)
            VALUES
            ('$request_type','$full_name','$username','$password','$army_no','$rank','$requested_by','$today','Pending')
        ");

        $message = "✅ Request sent to Admin for approval.";
    }
}
?>
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
    max-width:950px;
    margin:50px auto;
}

/* CARD */
.card{
    background:#162f4f;
    padding:30px;
    border-radius:6px;
    border-left:4px solid #d4af37;
    margin-bottom:30px;
    transition:all 0.35s ease;
    position:relative;
    overflow:hidden;
}

/* Lift + Glow */
.card:hover{
    transform:translateY(-10px) scale(1.01);
    background:#1d3a5c;
    box-shadow:
        0 0 25px rgba(212,175,55,0.6),
        0 20px 40px rgba(0,0,0,0.75);
}

/* Subtle highlight sweep animation */
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

/* TITLE */
h2{
    color:#ffffff;
    margin-bottom:20px;
    font-weight:600;
}

/* LABELS */
label{
    font-size:13px;
    font-weight:600;
    color:#b8c6db;
}

/* INPUT FIELDS */
input,
select{
    width:100%;
    padding:10px;
    margin-top:6px;
    margin-bottom:15px;
    border-radius:4px;
    border:1px solid #2c4c73;
    background:#0f223a;
    color:#ffffff;
}

input:focus,
select:focus{
    border-color:#d4af37;
    outline:none;
}

/* BUTTON */
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

/* SUCCESS MESSAGE */
.success{
    background:#1e4d2b;
    color:#a8e6a1;
    padding:12px;
    border-radius:4px;
    margin-bottom:20px;
}

/* FOOTER ACTIONS */
.footer-actions{
    text-align:center;
    margin-top:30px;
}

.footer-actions a{
    text-decoration:none;
    padding:12px 24px;
    background:#d4af37;
    color:#0f223a;
    border-radius:6px;
    font-weight:600;
    margin:0 10px;
}

.footer-actions a:hover{
    background:#c39c2d;
}
</style>

<div class="container">

<div class="card">

<h2>User Management Request (Clerk)</h2>

<?php if($message!=""){ ?>
<div class="success"><?php echo $message; ?></div>
<?php } ?>

<form method="POST">

<label>Request Type</label>
<select name="request_type" required>
    <option value="CREATE">Create User</option>
    <option value="EDIT">Edit User</option>
    <option value="DELETE">Delete User</option>
</select>

<label>Full Name</label>
<input type="text" name="full_name" required>

<label>Username</label>
<input type="text" name="username" required>

<label>Army Number</label>
<input type="text" name="army_no" required>

<label>Rank</label>
<select name="rank" required>
    <option value="ADMIN">ADMIN</option>
    <option value="CO">CO</option>
    <option value="ITJCO">ITJCO</option>
    <option value="CLERK">CLERK</option>
    <option value="USER">USER</option>
</select>

<button type="submit" name="submit_request">
Submit Request
</button>

</form>

</div>

<div class="footer-actions">
<a href="../dashboard.php">⬅ Back to Dashboard</a>
<a href="../logout.php">Logout</a>
</div>

</div>

</body>
</html>
