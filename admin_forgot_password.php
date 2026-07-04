<?php
session_start();
require_once 'db.php';

$message = "";

if (isset($_POST['check'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Invalid Email Address";
    } else {

        $query = mysqli_query(
            $conn,
            "SELECT * FROM users
             WHERE email='$email'
             AND role='admin'"
        );

        if (mysqli_num_rows($query) > 0) {

            header("Location: admin_reset_password.php?email=" . urlencode($email));
            exit();
        } else {

            $message = "Admin Email Not Found";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Forgot Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-5">

                <div class="card shadow">

                    <div class="card-header bg-danger text-white text-center">

                        <h3>Admin Forgot Password</h3>

                    </div>

                    <div class="card-body">

                        <?php
                        if ($message != "") {
                        ?>

                            <div class="alert alert-danger">
                                <?php echo $message; ?>
                            </div>

                        <?php
                        }
                        ?>

                        <form method="post">

                            <div class="mb-3">

                                <label>Email Address</label>

                                <input
                                    type="email"
                                    name="email"
                                    class="form-control"
                                    required>

                            </div>

                            <input
                                type="submit"
                                name="check"
                                value="Next"
                                class="btn btn-danger w-100">

                        </form>

                        <div class="text-center mt-3">

                            <a href="../admin_login.php">

                                Back To Login

                            </a>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>