<?php
include 'db.php';

$message = "";
$messageType = "";

if(isset($_POST['save']))
{
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);

    if(
        !filter_var($email, FILTER_VALIDATE_EMAIL)
        ||
        !preg_match('/\.[a-zA-Z]{2,}$/', $email)
    )
    {
        $message = "Invalid Email Address!";
        $messageType = "danger";
    }
    else
    {
        $check = mysqli_query(
            $conn,
            "SELECT * FROM students WHERE email='$email'"
        );

        if(mysqli_num_rows($check) > 0)
        {
            $message = "Email Already Exists!";
            $messageType = "danger";
        }
        else
        {
            $sql = "INSERT INTO students(name,email)
                    VALUES('$name','$email')";

            if(mysqli_query($conn,$sql))
            {
                $message = "Student Added Successfully!";
                $messageType = "success";
            }
            else
            {
                $message = "Error : " . mysqli_error($conn);
                $messageType = "danger";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Management System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<div class="container mt-5">

<h1 class="text-center mb-4">
    Student Management System
</h1>

<?php if($message != "") { ?>

<div id="msgBox" class="alert alert-<?php echo $messageType; ?>">
    <?php echo $message; ?>
</div>

<?php } ?>

<div class="card p-4 mb-4">

<form method="post">

<div class="mb-3">
<label class="form-label">Name</label>

<input type="text"
       name="name"
       class="form-control"
       required>
</div>

<div class="mb-3">
<label class="form-label">Email</label>

<input type="email"
       name="email"
       class="form-control"
       required>
</div>

<input type="submit"
       name="save"
       value="Add Student"
       class="btn btn-primary">

</form>

</div>

<h2>Student List</h2>

<form method="GET" class="mb-3">

<input type="text"
       name="search"
       class="form-control"
       placeholder="Search Student By Name Or Email"
       value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">

</form>

<?php

if(isset($_GET['search']) && $_GET['search'] != "")
{
    $search = $_GET['search'];

    $result = mysqli_query(
        $conn,
        "SELECT * FROM students
         WHERE name LIKE '%$search%'
         OR email LIKE '%$search%'"
    );
}
else
{
    $result = mysqli_query(
        $conn,
        "SELECT * FROM students"
    );
}

echo "<p><strong>Total Rows:</strong> "
     . mysqli_num_rows($result)
     . "</p>";

?>

<table class="table table-bordered table-striped">

<tr>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Action</th>
</tr>

<?php

while($row = mysqli_fetch_assoc($result))
{
    echo "<tr>";

    echo "<td>".$row['id']."</td>";
    echo "<td>".$row['name']."</td>";
    echo "<td>".$row['email']."</td>";

    echo "<td>

    <a href='edit.php?id=".$row['id']."'
       class='btn btn-warning btn-sm'>
       Edit
    </a>

    <a href='delete.php?id=".$row['id']."'
       class='btn btn-danger btn-sm'>
       Delete
    </a>

    </td>";

    echo "</tr>";
}

?>

</table>

</div>

<script>

setTimeout(function()
{
    let msg = document.getElementById("msgBox");

    if(msg)
    {
        msg.remove();
    }

},3000);

</script>

</body>
</html>