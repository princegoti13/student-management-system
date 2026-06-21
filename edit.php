<?php
include 'db.php';

$id = $_GET['id'];

$result = mysqli_query($conn, "SELECT * FROM students WHERE id=$id");
$row = mysqli_fetch_assoc($result);

if(isset($_POST['update']))
{
    $name = $_POST['name'];
    $email = $_POST['email'];

    mysqli_query($conn,
        "UPDATE students
         SET name='$name', email='$email'
         WHERE id=$id");

    header("Location: index.php");
}
?>

<h2>Edit Student</h2>

<form method="post">

    Name:
    <input type="text" name="name"
        value="<?php echo $row['name']; ?>">

    <br><br>

    Email:
    <input type="email" name="email"
        value="<?php echo $row['email']; ?>">

    <br><br>

    <input type="submit"
        name="update"
        value="Update Student">

</form>