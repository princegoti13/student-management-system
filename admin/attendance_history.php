<?php
session_start();
require_once __DIR__ . '/../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$result = mysqli_query(
    $conn,
    "SELECT attendance.*,
            users.name
     FROM attendance
     INNER JOIN users
     ON attendance.student_id = users.id
     ORDER BY attendance_date DESC,
              lecture_no ASC"
);
?>

<!DOCTYPE html>
<html>

<head>

    <title>Attendance History</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="container mt-5">

        <h2>Attendance History</h2>

        <a href="attendance.php"
            class="btn btn-success mb-3">
            Add Attendance
        </a>

        <a href="dashboard.php"
            class="btn btn-primary mb-3">
            Back
        </a>

        <table class="table table-bordered table-striped">

            <?php

            $currentDate = "";

            while ($row = mysqli_fetch_assoc($result)) {
                if ($currentDate != $row['attendance_date']) {
                    $currentDate = $row['attendance_date'];
            ?>

                    <tr class="table-dark">

                        <td colspan="6">

                            <h5 class="mb-0">
                                📅 Date :
                                <?php echo $currentDate; ?>
                            </h5>

                        </td>

                    </tr>

                    <tr>

                        <th>ID</th>
                        <th>Student</th>
                        <th>Subject</th>
                        <th>Lecture</th>
                        <th>Status</th>
                        <th>Action</th>

                    </tr>

                <?php
                }
                ?>

                <tr>

                    <td><?php echo $row['id']; ?></td>

                    <td><?php echo $row['name']; ?></td>

                    <td><?php echo $row['subject']; ?></td>

                    <td>
                        Lecture <?php echo $row['lecture_no']; ?>
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

                    <td>

                        <a href="edit_attendance.php?id=<?php echo $row['id']; ?>"
                            class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <a href="delete_attendance.php?id=<?php echo $row['id']; ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Delete Attendance?')">
                            Delete
                        </a>

                    </td>

                </tr>

            <?php
            }
            ?>

        </table>

    </div>

</body>

</html>