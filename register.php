<?php
include 'db.php';

$message = "";
$messageType = "";

if (isset($_POST['register'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = MD5($_POST['password']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $course = mysqli_real_escape_string($conn, $_POST['course']);
    $semester = mysqli_real_escape_string($conn, $_POST['semester']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);

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
        $check = mysqli_query(
            $conn,
            "SELECT * FROM users
             WHERE email='$email'"
        );

        if (mysqli_num_rows($check) > 0) {
            $message = "Email Already Exists";
            $messageType = "danger";
        } else {
            $insert = mysqli_query(
                $conn,
                "INSERT INTO users
    (
        name,
        email,
        password,
        role,
        mobile,
        gender,
        course,
        semester,
        address
    )
    VALUES
    (
        '$name',
        '$email',
        '$password',
        'student',
        '$mobile',
        '$gender',
        '$course',
        '$semester',
        '$address'
    )"
            );

            if ($insert) {
                $message = "Registration Successful";
                $messageType = "success";
            } else {
                $message = "Error: " . mysqli_error($conn);
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
                    if ($message != "") {
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
                            <label>Mobile Number</label>

                            <input type="text"
                                name="mobile"
                                class="form-control"
                                pattern="[0-9]{10}"
                                maxlength="10"
                                minlength="10"
                                title="Enter 10 Digit Mobile Number"
                                required>
                        </div>

                        <div class="mb-3">
                            <label>Gender</label>

                            <select name="gender"
                                class="form-control"
                                required>

                                <option value="">Select Gender</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>

                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Course</label>

                            <select name="course" class="form-control" required>

                                <option value="">Select Course</option>

                                <option value="BCA">BCA</option>
                                <option value="BBA">BBA</option>
                                <option value="BCom">BCom</option>
                                <option value="BA">BA</option>
                                <option value="BSc">BSc</option>
                                <option value="BTech">BTech</option>
                                <option value="BEd">BEd</option>
                                <option value="BPharm">BPharm</option>
                                <option value="BHM">BHM</option>
                                <option value="BSW">BSW</option>

                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Semester</label>

                            <select name="semester" class="form-control" required>

                                <option value="">Select Semester</option>

                                <option value="1">Semester 1</option>
                                <option value="2">Semester 2</option>
                                <option value="3">Semester 3</option>
                                <option value="4">Semester 4</option>
                                <option value="5">Semester 5</option>
                                <option value="6">Semester 6</option>

                            </select>
                        </div>

                        <div class="mb-3">
                            <label>Address</label>

                            <textarea name="address"
                                class="form-control"
                                rows="3"
                                required></textarea>
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
    <script>
        <?php
        if ($message == "Registration Successful") {
        ?>

            setTimeout(function() {
                window.location.href = "login.php";
            }, 1500);

        <?php
        }
        ?>

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