<?php
session_start();
include '../db.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'student')
{
    header("Location: ../login.php");
    exit();
}

$id = $_SESSION['user_id'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE id='$id'"
);

$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Student Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="container mt-5">

<h1>Student Profile</h1>

<p>
Welcome,
<b><?php echo $user['name']; ?></b>
</p>

<a href="../logout.php"
   class="btn btn-danger mb-3">
Logout
</a>

<table class="table table-bordered">

<tr>
<th>Name</th>
<td><?php echo $user['name']; ?></td>
</tr>

<tr>
<th>Email</th>
<td><?php echo $user['email']; ?></td>
</tr>

<tr>
<th>Role</th>
<td><?php echo $user['role']; ?></td>
</tr>

</table>

</div>

</body>
</html>