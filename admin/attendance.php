<?php
session_start();
require_once __DIR__ . '/../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$message = "";

if (isset($_POST['save'])) {
    $date = date("Y-m-d");

    $students = mysqli_query(
        $conn,
        "SELECT * FROM users
         WHERE role='student'"
    );

    while ($student = mysqli_fetch_assoc($students)) {
        $student_id = $student['id'];

        $status = isset($_POST['attendance'][$student_id])
            ? "Present"
            : "Absent";

        mysqli_query(
            $conn,
            "INSERT INTO attendance
            (student_id,attendance_date,status)
            VALUES
            (
            '$student_id',
            '$date',
            '$status'
            )"
        );
    }

    $message = "Attendance Saved Successfully";
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>Attendance Management</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="container mt-5">

        <h2>Today's Attendance</h2>

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

            <table class="table table-bordered">

                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Present</th>
                </tr>

                <?php

                $students = mysqli_query(
                    $conn,
                    "SELECT * FROM users
     WHERE role='student'"
                );

                while ($row = mysqli_fetch_assoc($students)) {
                ?>

                    <tr>

                        <td><?php echo $row['id']; ?></td>

                        <td><?php echo $row['name']; ?></td>

                        <td>

                            <input type="checkbox"
                                name="attendance[<?php echo $row['id']; ?>]">

                        </td>

                    </tr>

                <?php
                }
                ?>

            </table>

            <input type="submit"
                name="save"
                value="Save Attendance"
                class="btn btn-success">

            <a href="dashboard.php"
                class="btn btn-primary">
                Back
            </a>

        </form>

    </div>

</body>

</html>