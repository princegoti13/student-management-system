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
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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

                            <input type="password"
                                name="password"
                                class="form-control"
                                required>
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
    </script>

</body>

</html>