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
     ORDER BY
        attendance_date DESC,
        subject ASC,
        lecture_no ASC,
        users.name ASC"
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
            $currentSubject = "";

            while ($row = mysqli_fetch_assoc($result)) {
                // Date Change
                if ($currentDate != $row['attendance_date']) {
                    $currentDate = $row['attendance_date'];
                    $currentSubject = "";
            ?>

                    <tr class="table-dark">

                        <td colspan="5">

                            <h4 class="mb-0">
                                📅 Date : <?php echo $currentDate; ?>
                            </h4>

                        </td>

                    </tr>

                <?php
                }

                // Subject Change
                if ($currentSubject != $row['subject']) {
                    $currentSubject = $row['subject'];
                ?>

                    <tr class="table-primary">

                        <td colspan="5">

                            <div class="d-flex justify-content-between align-items-center flex-wrap">

                                <h5 class="mb-0">
                                    📚 Subject : <?php echo $currentSubject; ?>
                                </h5>

                                <a href="delete_subject_attendance.php?date=<?php echo $currentDate; ?>&subject=<?php echo urlencode($currentSubject); ?>"
                                    class="btn btn-danger btn-sm px-3"
                                    onclick="return confirm(
                                    '⚠️ WARNING!\n\n' +
                                    'Are You Sure You Want To Delete All Attendance Records?\n\n' +
                                    'Date : <?php echo $currentDate; ?>\n' +
                                    'Subject : <?php echo $currentSubject; ?>\n\n' +
                                    'This Action Cannot Be Undone!'
                                    )">
                                    Delete Subject
                                </a>

                            </div>

                        </td>

                    </tr>

                    <tr>

                        <th>ID</th>
                        <th>Student</th>
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