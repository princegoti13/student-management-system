<?php
session_start();
require_once 'db.php';

$message = "";
$messageType = "";

if (isset($_POST['verify'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);

    if (
        !filter_var($email, FILTER_VALIDATE_EMAIL)
        ||
        !preg_match('/\.[a-zA-Z]{2,}$/', $email)
    ) {

        $message = "Invalid Email Address";
        $messageType = "danger";
    } elseif (!preg_match('/^[0-9]{10}$/', $mobile)) {

        $message = "Mobile Number Must Be 10 Digits";
        $messageType = "danger";
    } else {

        $query = mysqli_query(
            $conn,
            "SELECT * FROM users
             WHERE email='$email'
             AND mobile='$mobile'"
        );

        if (mysqli_num_rows($query) == 1) {

            $user = mysqli_fetch_assoc($query);

            $_SESSION['reset_user_id'] = $user['id'];

            header("Location: reset_password.php");
            exit();
        } else {

            $message = "Email Or Mobile Number Is Incorrect";
            $messageType = "danger";
        }
    }
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Forgot Password</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-5">

                <div class="card shadow">

                    <div class="card-body">

                        <h3 class="text-center mb-4">
                            Forgot Password
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

                            <div class="mb-3">

                                <label>Mobile Number</label>

                                <input type="text"
                                    name="mobile"
                                    class="form-control"
                                    maxlength="10"
                                    pattern="[0-9]{10}"
                                    required>

                            </div>

                            <input type="submit"
                                name="verify"
                                value="Verify"
                                class="btn btn-primary w-100">

                            <a href="login.php"
                                class="btn btn-secondary w-100 mt-2">
                                Back To Login
                            </a>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

</body>

</html>