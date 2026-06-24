<?php
session_start();
require_once __DIR__ . '/../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'student') {
    header("Location: ../login.php");
    exit();
}

$student_id = $_SESSION['user_id'];

$attendance = mysqli_query(
    $conn,
    "SELECT *
     FROM attendance
     WHERE student_id='$student_id'
     ORDER BY attendance_date DESC"
);

$totalAttendance = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT *
         FROM attendance
         WHERE student_id='$student_id'"
    )
);

$totalPresent = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT *
         FROM attendance
         WHERE student_id='$student_id'
         AND status='Present'"
    )
);

$totalAbsent = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT *
         FROM attendance
         WHERE student_id='$student_id'
         AND status='Absent'"
    )
);

$percentage = 0;

if ($totalAttendance > 0) {
    $percentage = round(
        ($totalPresent / $totalAttendance) * 100,
        2
    );
}
?>

<!DOCTYPE html>
<html>

<head>

    <title>My Attendance</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="container mt-5">

        <h2 class="mb-4">
            My Attendance
        </h2>

        <div class="row mb-4">

            <div class="col-md-4">

                <div class="card p-3 text-center">

                    <h5>Present</h5>

                    <h2>
                        <?php echo $totalPresent; ?>
                    </h2>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card p-3 text-center">

                    <h5>Absent</h5>

                    <h2>
                        <?php echo $totalAbsent; ?>
                    </h2>

                </div>

            </div>

            <div class="col-md-4">

                <div class="card p-3 text-center">

                    <h5>Attendance %</h5>

                    <h2>
                        <?php echo $percentage; ?>
                    </h2>

                </div>

            </div>

        </div>

        <table class="table table-bordered table-striped">

            <tr>

                <th>Date</th>
                <th>Subject</th>
                <th>Lecture</th>
                <th>Status</th>

            </tr>

            <?php

            while ($row = mysqli_fetch_assoc($attendance)) {
            ?>

                <tr>

                    <td>
                        <?php echo $row['attendance_date']; ?>
                    </td>

                    <td>
                        <?php echo $row['subject']; ?>
                    </td>

                    <td>
                        <?php echo $row['lecture_no']; ?>
                    </td>

                    <td>

                        <?php

                        if ($row['status'] == "Present") {
                            echo "<span class='badge bg-success'>Present</span>";
                        } else {
                            echo "<span class='badge bg-danger'>Absent</span>";
                        }

                        ?>

                    </td>

                </tr>

            <?php
            }
            ?>

        </table>

        <a href="profile.php"
            class="btn btn-primary">
            Back
        </a>

    </div>

</body>

</html>