<?php
include 'db.php';

if(isset($_POST['save']))
{
    $name = $_POST['name'];
    $email = $_POST['email'];

    $sql = "INSERT INTO students(name,email)
            VALUES('$name','$email')";

    if(mysqli_query($conn,$sql))
    {
        echo "<h3>Student Added Successfully!</h3>";
    }
    else
    {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<h1>Student Management System</h1>

<form method="post">
    Name:
    <input type="text" name="name">
    <br><br>

    Email:
    <input type="email" name="email">
    <br><br>

    <input type="submit" name="save" value="Add Student">
</form>

<hr>

<h2>Student List</h2>

<?php

$result = mysqli_query($conn, "SELECT * FROM students");

echo "Total Rows: " . mysqli_num_rows($result);

echo "<br><br>";

while($row = mysqli_fetch_assoc($result))
{
    echo "ID: ".$row['id'];
    echo " | Name: ".$row['name'];
    echo " | Email: ".$row['email'];
    echo "<br><br>";
}

?>