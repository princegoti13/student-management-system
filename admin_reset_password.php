<?php
session_start();
require_once 'db.php';

if (!isset($_GET['email'])) {
    header("Location: admin_forgot_password.php");
    exit();
}

$message = "";
$messageType = "";

$email = mysqli_real_escape_string($conn, $_GET['email']);

if (isset($_POST['reset'])) {

    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm_password'];

    if (strlen($password) < 4) {

        $message = "Password Must Be At Least 4 Characters";
        $messageType = "danger";
    } elseif (empty($password) || empty($confirmPassword)) {

        $message = "All Fields Are Required";
        $messageType = "danger";
    } elseif ($password != $confirmPassword) {

        $message = "Passwords Do Not Match";
        $messageType = "danger";
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
                    }, 1500);
                </script>

            </body>

            </html>

<?php
            exit();
        } else {

            $message = "Something Went Wrong";
            $messageType = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Reset Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

</head>

<body>

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-5">

                <div class="card shadow">

                    <div class="card-body">

                        <h3 class="text-center mb-4">
                            Reset Admin Password
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

                                <label>New Password</label>

                                <div class="input-group">

                                    <input type="password"
                                        id="password"
                                        name="password"
                                        class="form-control"
                                        required>

                                    <button type="button"
                                        class="btn btn-outline-secondary"
                                        onclick="togglePassword('password','eye1')">

                                        <i id="eye1" class="bi bi-eye"></i>

                                    </button>

                                </div>

                            </div>

                            <div class="mb-3">

                                <label>Confirm Password</label>

                                <div class="input-group">

                                    <input type="password"
                                        id="confirm_password"
                                        name="confirm_password"
                                        class="form-control"
                                        required>

                                    <button type="button"
                                        class="btn btn-outline-secondary"
                                        onclick="togglePassword('confirm_password','eye2')">

                                        <i id="eye2" class="bi bi-eye"></i>

                                    </button>

                                </div>

                            </div>

                            <input type="submit"
                                name="reset"
                                value="Update Password"
                                class="btn btn-success w-100">

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

    <script>
        function togglePassword(inputId, eyeId) {

            const password =
                document.getElementById(inputId);

            const eye =
                document.getElementById(eyeId);

            if (password.type === "password") {

                password.type = "text";

                eye.classList.remove("bi-eye");
                eye.classList.add("bi-eye-slash");

            } else {

                password.type = "password";

                eye.classList.remove("bi-eye-slash");
                eye.classList.add("bi-eye");

            }

        }
    </script>

</body>

</html>