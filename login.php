<?php
session_start();
include 'db.php';

$message = "";
$messageType = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = MD5($_POST['password']);

    $query = mysqli_query(
        $conn,
        "SELECT * FROM users
        WHERE email='$email'
        AND password='$password'
        AND role='student'"
    );

    if (mysqli_num_rows($query) > 0) {
        $user = mysqli_fetch_assoc($query);

        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];

        $message = "Login Successful";
        $messageType = "success";

        if ($user['role'] == 'admin') {
            $redirectPage = "admin/dashboard.php";
        } else {
            $redirectPage = "student/profile.php";
        }
    } else {
        $message = "Invalid Email Or Password";
        $messageType = "danger";
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <style>
        .form-link {

            text-decoration: none;
            color: #0d6efd;
            font-weight: 600;
            transition: .3s;

        }

        .form-link:hover {

            color: #084298;
            text-decoration: underline;

        }
    </style>

</head>

<body>

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-5">

                <div class="card shadow p-4">

                    <h2 class="text-center mb-4">
                        Student Login
                    </h2>

                    <?php
                    if ($message != "") {
                        echo "<div class='alert alert-$messageType'>$message</div>";
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

                        <div class="mb-3">

                            <label>Password</label>

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

                        <div class="text-end mt-2">
                            <a href="forgot_password.php" class="form-link">
                                Forgot Password?
                            </a>
                        </div>

                        <input type="submit"
                            name="login"
                            value="Login"
                            class="btn btn-primary w-100">

                        <br><br>

                        <a href="register.php"
                            class="btn btn-success w-100">
                            Register
                        </a>

                    </form>

                </div>

            </div>

        </div>

    </div>

    <script>
        <?php
        if (isset($redirectPage)) {
        ?>

            setTimeout(function() {
                window.location.href = "<?php echo $redirectPage; ?>";
            }, 3000);

        <?php
        }
        ?>

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