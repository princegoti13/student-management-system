<?php
session_start();
require_once __DIR__ . '/../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

$id = $_SESSION['user_id'];

$message = "";
$messageType = "";

if (isset($_POST['change'])) {
    $currentPassword = MD5($_POST['current_password']);
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    $query = mysqli_query(
        $conn,
        "SELECT * FROM users
         WHERE id='$id'
         AND password='$currentPassword'"
    );

    if (mysqli_num_rows($query) == 0) {
        $message = "Current Password Is Incorrect";
        $messageType = "danger";
    } elseif ($newPassword != $confirmPassword) {
        $message = "New Password And Confirm Password Do Not Match";
        $messageType = "danger";
    } else {
        $newPassword = MD5($newPassword);

        mysqli_query(
            $conn,
            "UPDATE users
             SET password='$newPassword'
             WHERE id='$id'"
        );

        $message = "Password Changed Successfully";
        $messageType = "success";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Change Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-6">

                <div class="card shadow p-4">

                    <h2 class="text-center mb-4">
                        Change Password
                    </h2>

                    <?php
                    if ($message != "") {
                        echo "<div class='alert alert-$messageType'>$message</div>";
                    }
                    ?>

                    <form method="post">

                        <div class="mb-3">

                            <label>Current Password</label>

                            <input type="password"
                                name="current_password"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label>New Password</label>

                            <input type="password"
                                name="new_password"
                                class="form-control"
                                required>

                        </div>

                        <div class="mb-3">

                            <label>Confirm Password</label>

                            <input type="password"
                                name="confirm_password"
                                class="form-control"
                                required>

                        </div>

                        <input type="submit"
                            name="change"
                            value="Change Password"
                            class="btn btn-success">

                        <a href="profile.php"
                            class="btn btn-primary">
                            Back
                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <?php
    if ($message == "Password Changed Successfully") {
    ?>
        <script>
            setTimeout(function() {
                window.location.href = "profile.php";
            }, 3000);
        </script>
    <?php
    }
    ?>

</body>

</html>