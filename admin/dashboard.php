<?php
session_start();
include '../db.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin')
{
    header("Location: ../login.php");
    exit();
}

$result = mysqli_query($conn,"SELECT * FROM users WHERE role='student'");
?>

<!DOCTYPE html>
<html>
<head>
<title>Admin Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="container mt-5">

<h1>Admin Dashboard</h1>

<p>
Welcome,
<b><?php echo $_SESSION['name']; ?></b>
</p>

<a href="../logout.php" class="btn btn-danger mb-3">
Logout
</a>

<h3>All Students</h3>

<table class="table table-bordered">

<tr>
<th>ID</th>
<th>Name</th>
<th>Email</th>
</tr>

<?php

while($row = mysqli_fetch_assoc($result))
{
?>

<tr>
<td><?php echo $row['id']; ?></td>
<td><?php echo $row['name']; ?></td>
<td><?php echo $row['email']; ?></td>
</tr>

<?php
}
?>

</table>

</div>

</body>
</html>