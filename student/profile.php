<?php
session_start();
require_once __DIR__ . '/../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
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

        <?php

        $photoPath = "../uploads/default-user.png";

        if (
            !empty($user['photo']) &&
            file_exists(__DIR__ . "/../uploads/" . $user['photo'])
        ) {
            $photoPath = "../uploads/" . $user['photo'];
        }
        ?>

        <div class="mb-3 text-center">
            <img src="<?php echo $photoPath; ?>"
                width="220"
                height="220"
                class="rounded-circle"
                style="object-fit:cover;border:4px solid #ccc;">
        </div>
        <p>
            Welcome,
            <b><?php echo $user['name']; ?></b>
        </p>

        <a href="edit_profile.php"
            class="btn btn-warning mb-3">
            Edit Profile
        </a>

        <a href="change_password.php"
            class="btn btn-secondary mb-3">
            Change Password
        </a>

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

            <tr>
                <th>Mobile</th>
                <td><?php echo $user['mobile']; ?></td>
            </tr>

            <tr>
                <th>Gender</th>
                <td><?php echo $user['gender']; ?></td>
            </tr>

            <tr>
                <th>Course</th>
                <td><?php echo $user['course']; ?></td>
            </tr>

            <tr>
                <th>Semester</th>
                <td><?php echo $user['semester']; ?></td>
            </tr>

            <tr>
                <th>Address</th>
                <td><?php echo $user['address']; ?></td>
            </tr>

        </table>

    </div>

</body>

</html>