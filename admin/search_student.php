<?php
session_start();
require_once __DIR__ . '/../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    exit();
}

$search = "";

if (isset($_GET['search'])) {
    $search = mysqli_real_escape_string($conn, $_GET['search']);
}

$result = mysqli_query(
    $conn,
    "SELECT * FROM users
     WHERE role='student'
     AND (
        name LIKE '%$search%'
        OR email LIKE '%$search%'
        OR mobile LIKE '%$search%'
     )
     ORDER BY name ASC"
);

echo '<table class="table table-bordered table-striped">';

echo '
<tr>
    <th>Photo</th>
    <th>ID</th>
    <th>Name</th>
    <th>Email</th>
    <th>Gender</th>
    <th>Mobile</th>
    <th>Course</th>
    <th>Semester</th>
    <th>Attendance</th>
    <th>Action</th>
</tr>
';

if (mysqli_num_rows($result) == 0) {

    echo '
    <tr>
        <td colspan="10" class="text-center text-danger">
            No Student Found
        </td>
    </tr>';
} else {

    while ($row = mysqli_fetch_assoc($result)) {

        $totalAttendance = mysqli_num_rows(
            mysqli_query(
                $conn,
                "SELECT * FROM attendance
                 WHERE student_id='" . $row['id'] . "'"
            )
        );

        $totalPresent = mysqli_num_rows(
            mysqli_query(
                $conn,
                "SELECT * FROM attendance
                 WHERE student_id='" . $row['id'] . "'
                 AND status='Present'"
            )
        );

        $attendancePercentage = 0;

        if ($totalAttendance > 0) {

            $attendancePercentage =
                round(
                    ($totalPresent / $totalAttendance) * 100,
                    2
                );
        }

        $photoPath = "/uploads/default-user.png";

        if (
            !empty($row['photo']) &&
            file_exists(__DIR__ . "/../uploads/" . $row['photo'])
        ) {

            $photoPath = "/uploads/" . $row['photo'];
        }

        echo "<tr>";

        echo "<td>
                <img src='$photoPath'
                     width='60'
                     height='60'
                     class='rounded-circle'
                     style='object-fit:cover;'>
              </td>";

        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['email']}</td>";
        echo "<td>{$row['gender']}</td>";
        echo "<td>{$row['mobile']}</td>";
        echo "<td>{$row['course']}</td>";
        echo "<td>{$row['semester']}</td>";

        echo "<td>";

        if ($attendancePercentage >= 75) {

            echo "<span class='badge bg-success'>$attendancePercentage%</span>";
        } elseif ($attendancePercentage >= 50) {

            echo "<span class='badge bg-warning'>$attendancePercentage%</span>";
        } else {

            echo "<span class='badge bg-danger'>$attendancePercentage%</span>";
        }

        echo "</td>";

        echo "<td>

                <a href='edit_student.php?id={$row['id']}'
                   class='btn btn-warning btn-sm'>
                    Edit
                </a>

                <a href='delete_student.php?id={$row['id']}'
                   class='btn btn-danger btn-sm'
                   onclick=\"return confirm('Delete Student?')\">
                    Delete
                </a>

              </td>";

        echo "</tr>";
    }
}

echo "</table>";
