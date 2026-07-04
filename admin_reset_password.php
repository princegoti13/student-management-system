<?php
session_start();
require_once 'db.php';

$message = "";

if (!isset($_GET['email'])) {
    header("Location: admin_forgot_password.php");
    exit();
}

$email = mysqli_real_escape_string($conn, $_GET['email']);

if (isset($_POST['reset'])) {

    $password = $_POST['password'];
    $confirm = $_POST['confirm'];

    if (strlen($password) < 4) {

        $message = "Password Must Be At Least 4 Characters";
    } elseif ($password != $confirm) {

        $message = "Passwords Do Not Match";
    } else {

        $password = md5($password);

        $sql = "UPDATE users
                SET password='$password'
                WHERE email='$email'
                AND role='admin'";

        if (mysqli_query($conn, $sql)) {
?>
            <!DOCTYPE html>
            <html>

            <head>

                <title>Password Reset</title>

                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

            </head>

            <body>

                <div class="container mt-5">

                    <div class="alert alert-success text-center">

                        <h4>✅ Password Reset Successfully</h4>

                        <p>Redirecting To Admin Login...</p>

                    </div>

                </div>

                <script>
                    setTimeout(function() {
                        window.location = "../admin_login.php";
                    }, 3000);
                </script>

            </body>

            </html>

<?php
            exit();
        } else {

            $message = "Something Went Wrong";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Reset Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-5">

                <div class="card shadow">

                    <div class="card-header bg-danger text-white text-center">

                        <h3>Reset Admin Password</h3>

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

                                <label>New Password</label>

                                <input
                                    type="password"
                                    name="password"
                                    class="form-control"
                                    required>

                            </div>

                            <div class="mb-3">

                                <label>Confirm Password</label>

                                <input
                                    type="password"
                                    name="confirm"
                                    class="form-control"
                                    required>

                            </div>

                            <input
                                type="submit"
                                name="reset"
                                value="Reset Password"
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