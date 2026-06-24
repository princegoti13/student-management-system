<?php
session_start();
include 'db.php';

$message = "";
$messageType = "";

if (isset($_POST['login'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = MD5($_POST['password']);

    if (
        !filter_var($email, FILTER_VALIDATE_EMAIL)
        ||
        !preg_match('/\.[a-zA-Z]{2,}$/', $email)
    ) {
        $message = "Invalid Email Address";
        $messageType = "danger";
    } else {

        $query = mysqli_query(
            $conn,
            "SELECT * FROM users
         WHERE email='$email'
         AND password='$password'
         AND role='admin'"
        );

        if (mysqli_num_rows($query) == 1) {
            $user = mysqli_fetch_assoc($query);

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];

            $message = "Admin Login Successful";
            $messageType = "success";
        } else {
            $message = "Invalid Admin Credentials";
            $messageType = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Admin Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-5">

                <div class="card shadow p-4">

                    <h2 class="text-center mb-4">
                        Admin Login
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
                                pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$"
                                title="Enter Valid Email Address"
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
                                    onclick="togglePassword()">

                                    👁

                                </button>

                            </div>

                        </div>

                        <input type="submit"
                            name="login"
                            value="Admin Login"
                            class="btn btn-danger w-100">

                    </form>

                </div>

            </div>

        </div>

    </div>

    <?php
    if ($message == "Admin Login Successful") {
    ?>

        <script>
            setTimeout(function() {
                window.location.href = "admin/dashboard.php";
            }, 3000);
        </script>

    <?php
    }
    ?>

    <script>
        function togglePassword() {
            var password =
                document.getElementById("password");

            if (password.type === "password") {
                password.type = "text";
            } else {
                password.type = "password";
            }
        }
    </script>

</body>

</html>