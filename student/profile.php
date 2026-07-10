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
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (!empty($_FILES["photo"]["name"])) {

        $photoName = time() . "_" . basename($_FILES["photo"]["name"]);

        $target = __DIR__ . "/../uploads/" . $photoName;

        if (move_uploaded_file($_FILES["photo"]["tmp_name"], $target)) {

            mysqli_query(
                $conn,
                "UPDATE users
                 SET photo='$photoName'
                 WHERE id='$id'"
            );

            header("Location: profile.php");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Student Profile</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .profile-section {

            display: flex;

            align-items: center;

            gap: 25px;

            margin-bottom: 25px;

        }

        .profile-image {

            position: relative;

            width: 150px;

            height: 150px;

            flex-shrink: 0;

        }

        .profile-photo {

            width: 150px;

            height: 150px;

            border-radius: 50%;

            object-fit: cover;

            border: 4px solid #0d6efd;

            box-shadow: 0 8px 20px rgba(0, 0, 0, .15);

        }

        .camera-btn {

            position: absolute;

            top: 5px;

            right: 5px;

            width: 34px;

            height: 34px;

            background: #fff;

            color: #0d6efd;

            border: 2px solid #0d6efd;

            border-radius: 50%;

            display: flex;

            justify-content: center;

            align-items: center;

            cursor: pointer;

            transition: .3s;

            box-shadow: 0 4px 10px rgba(0, 0, 0, .2);

        }

        .camera-btn:hover {

            background: #0d6efd;

            color: #fff;

            transform: scale(1.1);

        }

        .profile-info h5 {

            margin-bottom: 5px;

            font-weight: 700;

        }

        .profile-info p {

            margin: 0;

            color: #6c757d;

        }
    </style>

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

        <!-- <div class="mb-4"> -->

        <form method="POST" enctype="multipart/form-data">

            <div class="profile-image">

                <img src="<?php echo $photoPath; ?>"
                    class="profile-photo">

                <label for="photo" class="camera-btn">
                    <img src="../uploads/camera.png" alt="Upload" class="camera-icon">
                </label>

                <input
                    type="file"
                    id="photo"
                    name="photo"
                    hidden
                    onchange="this.form.submit()">

            </div>

        </form>

        <!-- </div> -->

        <p>
            Welcome,
            <b><?php echo $user['name']; ?></b>
        </p>

        <a href="edit_profile.php"
            class="btn btn-warning mb-3">
            Edit Profile
        </a>

        <a href="attendance.php"
            class="btn btn-info mb-3">
            My Attendance
        </a>

        <a href="change_password.php"
            class="btn btn-secondary mb-3">
            Change Password
        </a>

        <a href="../login.php"
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