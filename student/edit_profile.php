<?php
error_reporting(0);
ini_set('display_errors', 0);
session_start();
require_once __DIR__ . '/../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

$id = $_SESSION['user_id'];

$message = "";

$query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE id='$id'"
);

$user = mysqli_fetch_assoc($query);

if (isset($_POST['update'])) {

    $name = $_POST['name'];
    $email = $_POST['email'];
    $mobile = $_POST['mobile'];
    $gender = $_POST['gender'];
    $course = $_POST['course'];
    $semester = $_POST['semester'];
    $address = $_POST['address'];

    $photoName = $user['photo'];

    if (!empty($_FILES['photo']['name'])) {

        $newPhotoName =
            time() . "_" .
            basename($_FILES['photo']['name']);

        $uploadDir = __DIR__ . "/../uploads/";

        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        if (
            move_uploaded_file(
                $_FILES['photo']['tmp_name'],
                $uploadDir . $newPhotoName
            )
        ) {

            // જૂની photo delete કરો (default સિવાય)
            if (
                !empty($user['photo']) &&
                $user['photo'] != "default-user.png" &&
                file_exists($uploadDir . $user['photo'])
            ) {
                unlink($uploadDir . $user['photo']);
            }

            $photoName = $newPhotoName;
        } else {
            $message = "Photo Upload Failed";
        }
    }

    if (empty(trim($address))) {
        $message = "Address Is Required";
    } elseif (
        !filter_var($email, FILTER_VALIDATE_EMAIL)
        ||
        !preg_match('/\.[a-zA-Z]{2,}$/', $email)
    ) {
        $message = "Invalid Email Address";
    } elseif (!preg_match('/^[0-9]{10}$/', $mobile)) {
        $message = "Mobile Number Must Be 10 Digits";
    } else {

        $sql = "UPDATE users SET
            name='$name',
            email='$email',
            mobile='$mobile',
            gender='$gender',
            course='$course',
            semester='$semester',
            address='$address',
            photo='$photoName'
            WHERE id='$id'";

        if (mysqli_query($conn, $sql)) {

            $message = "Profile Updated Successfully";

            echo "
<script>
setTimeout(function(){
    window.location='profile.php';
},3000);
</script>
";
        } else {
            $message = mysqli_error($conn);
        }
    }
}

$query = mysqli_query(
    $conn,
    "SELECT * FROM users WHERE id='$id'"
);

$user = mysqli_fetch_assoc($query);
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Profile</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="container mt-5">

        <h2>Edit Profile</h2>

        <?php if ($message != "") { ?>

            <div class="alert <?php echo strpos($message, 'Successfully') !== false ? 'alert-success' : 'alert-danger'; ?>">

                <?php echo $message; ?>

            </div>

        <?php } ?>

        <form method="post" enctype="multipart/form-data">

            <div class="mb-3">
                <label>Name</label>
                <input type="text"
                    name="name"
                    class="form-control"
                    value="<?php echo $user['name']; ?>"
                    required>
            </div>

            <div class="mb-3">
                <label>Email</label>
                <input type="email"
                    name="email"
                    class="form-control"
                    value="<?php echo $user['email']; ?>"
                    required>
            </div>

            <div class="mb-3">
                <label>Mobile</label>
                <input type="text"
                    name="mobile"
                    class="form-control"
                    maxlength="10"
                    pattern="[0-9]{10}"
                    value="<?php echo $user['mobile']; ?>"
                    required>
            </div>

            <div class="mb-3">
                <label>Gender</label>

                <select name="gender" class="form-control" required>

                    <option value="Male"
                        <?php if ($user['gender'] == "Male") echo "selected"; ?>>
                        Male
                    </option>

                    <option value="Female"
                        <?php if ($user['gender'] == "Female") echo "selected"; ?>>
                        Female
                    </option>

                </select>

            </div>

            <div class="mb-3">
                <label>Course</label>

                <select name="course" class="form-control" required>

                    <option value="BCA"
                        <?php if ($user['course'] == "BCA") echo "selected"; ?>>
                        BCA
                    </option>

                    <option value="BBA"
                        <?php if ($user['course'] == "BBA") echo "selected"; ?>>
                        BBA
                    </option>

                    <option value="BCom"
                        <?php if ($user['course'] == "BCom") echo "selected"; ?>>
                        BCom
                    </option>

                    <option value="BA"
                        <?php if ($user['course'] == "BA") echo "selected"; ?>>
                        BA
                    </option>

                    <option value="BSc"
                        <?php if ($user['course'] == "BSc") echo "selected"; ?>>
                        BSc
                    </option>

                    <option value="BTech"
                        <?php if ($user['course'] == "BTech") echo "selected"; ?>>
                        BTech
                    </option>

                </select>
            </div>

            <div class="mb-3">
                <label>Semester</label>

                <select name="semester" class="form-control" required>

                    <option value="1"
                        <?php if ($user['semester'] == "1") echo "selected"; ?>>
                        Semester 1
                    </option>

                    <option value="2"
                        <?php if ($user['semester'] == "2") echo "selected"; ?>>
                        Semester 2
                    </option>

                    <option value="3"
                        <?php if ($user['semester'] == "3") echo "selected"; ?>>
                        Semester 3
                    </option>

                    <option value="4"
                        <?php if ($user['semester'] == "4") echo "selected"; ?>>
                        Semester 4
                    </option>

                    <option value="5"
                        <?php if ($user['semester'] == "5") echo "selected"; ?>>
                        Semester 5
                    </option>

                    <option value="6"
                        <?php if ($user['semester'] == "6") echo "selected"; ?>>
                        Semester 6
                    </option>

                </select>
            </div>

            <div class="mb-3">
                <label>Address</label>

                <textarea name="address"
                    class="form-control"
                    required><?php echo $user['address']; ?></textarea>

            </div>

            <div class="mb-3">

                <label>Profile Photo</label>

                <input type="file"
                    name="photo"
                    class="form-control">

            </div>

            <input type="submit"
                name="update"
                value="Update Profile"
                class="btn btn-success">

            <a href="profile.php"
                class="btn btn-primary">
                Back
            </a>

        </form>

    </div>

</body>

</html>