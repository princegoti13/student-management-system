<?php
include 'db.php';

$message = "";
$messageType = "";

if(isset($_POST['register']))
{
    $name = mysqli_real_escape_string($conn,$_POST['name']);
    $email = mysqli_real_escape_string($conn,$_POST['email']);
    $password = MD5($_POST['password']);

    if(
    !filter_var($email,FILTER_VALIDATE_EMAIL)
    ||
    !preg_match('/\.[a-zA-Z]{2,}$/', $email)
)
{
    $message = "Invalid Email Address";
    $messageType = "danger";
}
    else
    {
        $check = mysqli_query(
            $conn,
            "SELECT * FROM users
             WHERE email='$email'"
        );

        if(mysqli_num_rows($check) > 0)
        {
            $message = "Email Already Exists";
            $messageType = "danger";
        }
        else
        {
            $insert = mysqli_query(
                $conn,
                "INSERT INTO users
                (name,email,password,role)
                VALUES
                (
                '$name',
                '$email',
                '$password',
                'student'
                )"
            );

            if($insert)
            {
                $message = "Registration Successful";
                $messageType = "success";
            }
            else
            {
                $message = "Error: ".mysqli_error($conn);
                $messageType = "danger";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-5">

    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow p-4">

                <h2 class="text-center mb-4">
                    Student Registration
                </h2>

                <?php
                if($message != "")
                {
                    echo "<div class='alert alert-$messageType'>$message</div>";
                }
                ?>

                <form method="post">

                    <div class="mb-3">
                        <label>Name</label>

                        <input type="text"
                               name="name"
                               class="form-control"
                               required>
                    </div>

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

                        <input type="password"
                               name="password"
                               class="form-control"
                               required>
                    </div>

                    <input type="submit"
                           name="register"
                           value="Register"
                           class="btn btn-success w-100">

                    <br><br>

                    <a href="login.php"
                       class="btn btn-primary w-100">
                        Login
                    </a>

                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>