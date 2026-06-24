<?php
session_start();
require_once __DIR__ . '/../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];

$query = mysqli_query(
    $conn,
    "SELECT * FROM attendance
     WHERE id='$id'"
);

$row = mysqli_fetch_assoc($query);

$message = "";

if (isset($_POST['update'])) {
    $status = $_POST['status'];

    mysqli_query(
        $conn,
        "UPDATE attendance
         SET status='$status'
         WHERE id='$id'"
    );

    $message = "Attendance Updated Successfully";

    echo "
<script>
setTimeout(function(){
    window.location='attendance_history.php';
},1000);
</script>
";

    $query = mysqli_query(
        $conn,
        "SELECT * FROM attendance
         WHERE id='$id'"
    );

    $row = mysqli_fetch_assoc($query);
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Edit Attendance</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="container mt-5">

        <h2>Edit Attendance</h2>

        <?php
        if ($message != "") {
        ?>
            <div class="alert alert-success">
                <?php echo $message; ?>
            </div>
        <?php
        }
        ?>

        <form method="post">

            <div class="mb-3">

                <label>Status</label>

                <select name="status"
                    class="form-control">

                    <option value="Present"
                        <?php if ($row['status'] == "Present") echo "selected"; ?>>
                        Present
                    </option>

                    <option value="Absent"
                        <?php if ($row['status'] == "Absent") echo "selected"; ?>>
                        Absent
                    </option>

                </select>

            </div>

            <input type="submit"
                name="update"
                value="Update Attendance"
                class="btn btn-success">

            <a href="attendance_history.php"
                class="btn btn-primary">
                Back
            </a>

        </form>

    </div>

</body>

</html>