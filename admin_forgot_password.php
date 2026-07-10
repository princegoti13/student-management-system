<?php
session_start();
require_once 'db.php';

$message = "";
$messageType = "";

if (isset($_POST['verify'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Invalid Email Address";
        $messageType = "danger";
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
            $messageType = "danger";
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

                    <div class="card-body">

                        <h3 class="text-center mb-4">
                            Forgot Admin Password
                        </h3>

                        <?php
                        if ($message != "") {
                        ?>

                            <div class="alert alert-<?php echo $messageType; ?>">
                                <?php echo $message; ?>
                            </div>

                        <?php
                        }
                        ?>

                        <form method="post">

                            <div class="mb-3">

                                <label>Email</label>

                                <input type="email"
                                    name="email"
                                    class="form-control"
                                    required>

                            </div>

                            <input type="submit"
                                name="verify"
                                value="Verify"
                                class="btn btn-primary w-100">

                            <a href="admin_login.php"
                                class="btn btn-secondary w-100 mt-2">
                                Back To Admin Login
                            </a>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>