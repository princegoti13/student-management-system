<?php
session_start();
require_once __DIR__ . '/../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$totalStudents = mysqli_num_rows(
    mysqli_query(
        $conn,
        "SELECT * FROM users WHERE role='student'"
    )
);

if (isset($_GET['search']) && $_GET['search'] != "") {
    $search = mysqli_real_escape_string(
        $conn,
        $_GET['search']
    );

    $totalStudents = mysqli_num_rows(
        mysqli_query(
            $conn,
            "SELECT * FROM users WHERE role='student'"
        )
    );

    $totalMale = mysqli_num_rows(
        mysqli_query(
            $conn,
            "SELECT * FROM users
         WHERE role='student'
         AND gender='Male'"
        )
    );

    $totalFemale = mysqli_num_rows(
        mysqli_query(
            $conn,
            "SELECT * FROM users
         WHERE role='student'
         AND gender='Female'"
        )
    );

    $result = mysqli_query(
        $conn,
        "SELECT * FROM users
         WHERE role='student'
         AND (
            name LIKE '%$search%'
            OR email LIKE '%$search%'
         )"
    );
} else {
    $totalMale = mysqli_num_rows(
        mysqli_query(
            $conn,
            "SELECT * FROM users
         WHERE role='student'
         AND gender='Male'"
        )
    );

    $totalFemale = mysqli_num_rows(
        mysqli_query(
            $conn,
            "SELECT * FROM users
         WHERE role='student'
         AND gender='Female'"
        )
    );

    $totalBCA = mysqli_num_rows(
        mysqli_query(
            $conn,
            "SELECT * FROM users
         WHERE role='student'
         AND course='BCA'"
        )
    );

    $result = mysqli_query(
        $conn,
        "SELECT * FROM users
         WHERE role='student'"
    );
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

    <div class="container mt-5">

        <h1 class="mb-3">
            Admin Dashboard
        </h1>

        <p>
            Welcome,
            <b><?php echo $_SESSION['name']; ?></b>
        </p>

        <a href="attendance.php"
            class="btn btn-success mb-3">
            Attendance
        </a>

        <a href="attendance_history.php"
            class="btn btn-info mb-3">
            Attendance History
        </a>

        <a href="../admin_login.php"
            class="btn btn-danger mb-3">
            Logout
        </a>

        <div class="row mb-4">

            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body text-center">
                        <h2><?php echo $totalStudents; ?></h2>
                        <h5>Total Students</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body text-center">
                        <h2><?php echo $totalMale; ?></h2>
                        <h5>Male Students</h5>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body text-center">
                        <h2><?php echo $totalFemale; ?></h2>
                        <h5>Female Students</h5>
                    </div>
                </div>
            </div>

        </div>

        <h3>Student Management</h3>

        <form method="GET" class="mb-3">

            <input type="text"
                name="search"
                class="form-control"
                placeholder="Search By Name Or Email"
                value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">

        </form>

        <table class="table table-bordered table-striped">

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

            <?php
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
            ?>
                <tr>

                    <td>
                        <img src="<?php echo $photoPath; ?>"
                            width="60"
                            height="60"
                            class="rounded-circle"
                            style="object-fit:cover;">
                    </td>

                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['name']; ?></td>
                    <td><?php echo $row['email']; ?></td>
                    <td><?php echo $row['gender']; ?></td>
                    <td><?php echo $row['mobile']; ?></td>
                    <td><?php echo $row['course']; ?></td>
                    <td><?php echo $row['semester']; ?></td>

                    <td>

                        <?php

                        if ($attendancePercentage >= 75) {
                            echo "<span class='badge bg-success'>";
                        } elseif ($attendancePercentage >= 50) {
                            echo "<span class='badge bg-warning'>";
                        } else {
                            echo "<span class='badge bg-danger'>";
                        }

                        echo $attendancePercentage . "%</span>";

                        ?>

                    </td>

                    <td>
                        <a href="edit_student.php?id=<?php echo $row['id']; ?>"
                            class="btn btn-warning btn-sm">
                            Edit
                        </a>

                        <a href="delete_student.php?id=<?php echo $row['id']; ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Delete Student?')">
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