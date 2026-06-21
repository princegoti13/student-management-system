<?php
session_start();
include '../db.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'student')
{
    header("Location: ../login.php");
    exit();
}

$id = $_SESSION['user_id'];

$message = "";

if(isset($_POST['update']))
{
    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $gender = $_POST['gender'];
    $course = $_POST['course'];
    $semester = $_POST['semester'];
    $address = $_POST['address'];

    $sql = "UPDATE users SET
            name='$name',
            email='$email',
            mobile='$mobile',
            gender='$gender',
            course='$course',
            semester='$semester',
            address='$address'
            WHERE id='$id'";

    if(mysqli_query($conn,$sql))
    {
        $message = "Profile Updated Successfully";
    }
    else
    {
        $message = "Error : " . mysqli_error($conn);
    }
}

$query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE id='$id'"
);

$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>
<head>
<title>Edit Profile</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body>

<div class="container mt-5">

<h2>Edit Profile</h2>

<?php
if($message != "")
{
    echo "<div class='alert alert-success'>$message</div>";
}
?>

<form method="post">

<div class="mb-3">
<label>Name</label>
<input type="text"
       name="name"
       class="form-control"
       value="<?php echo $user['name']; ?>"
       required>
</div>

<div class="mb-3">
<label>Email</label>
<input type="email"
       name="email"
       class="form-control"
       value="<?php echo $user['email']; ?>"
       required>
</div>

<div class="mb-3">
<label>Mobile</label>
<input type="text"
       name="mobile"
       class="form-control"
       value="<?php echo $user['mobile']; ?>">
</div>

<div class="mb-3">
<label>Gender</label>

<select name="gender" class="form-control">

<option value="Male"
<?php if($user['gender']=="Male") echo "selected"; ?>>
Male
</option>

<option value="Female"
<?php if($user['gender']=="Female") echo "selected"; ?>>
Female
</option>

</select>

</div>

<div class="mb-3">
<label>Course</label>
<input type="text"
       name="course"
       class="form-control"
       value="<?php echo $user['course']; ?>">
</div>

<div class="mb-3">
<label>Semester</label>
<input type="text"
       name="semester"
       class="form-control"
       value="<?php echo $user['semester']; ?>">
</div>

<div class="mb-3">
<label>Address</label>

<textarea name="address"
          class="form-control"><?php echo $user['address']; ?></textarea>

</div>

<input type="submit"
       name="update"
       value="Update Profile"
       class="btn btn-success">

<a href="profile.php"
   class="btn btn-primary">
Back
</a>

</form>

</div>

</body>
</html>