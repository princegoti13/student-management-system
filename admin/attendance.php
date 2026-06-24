<?php
session_start();
require_once __DIR__ . '/../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$message = "";

if (isset($_POST['save'])) {

    $attendance_date = $_POST['attendance_date'];
    $subject = $_POST['subject'];
    $lecture_no = $_POST['lecture_no'];

    $students = mysqli_query(
        $conn,
        "SELECT * FROM users WHERE role='student'"
    );

    while ($student = mysqli_fetch_assoc($students)) {

        $student_id = $student['id'];

        $status = isset($_POST['attendance'][$student_id])
            ? "Present"
            : "Absent";

        mysqli_query(
            $conn,
            "INSERT INTO attendance
            (
                student_id,
                attendance_date,
                status,
                subject,
                lecture_no
            )
            VALUES
            (
                '$student_id',
                '$attendance_date',
                '$status',
                '$subject',
                '$lecture_no'
            )"
        );
    }

    $message = "Attendance Saved Successfully";

    echo "
<script>
setTimeout(function(){
    window.location='dashboard.php';
},3000);
</script>
";
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

            <div class="row">

                <div class="col-md-4 mb-3">

                    <label>Date</label>

                    <input type="date"
                        name="attendance_date"
                        class="form-control"
                        required>

                </div>

                <div class="col-md-4 mb-3">

                    <label>Subject</label>

                    <select name="subject"
                        class="form-control"
                        required>

                        <option value="">Select Subject</option>

                        <option value="PHP">PHP</option>
                        <option value="Java">Java</option>
                        <option value="Python">Python</option>
                        <option value="DBMS">DBMS</option>
                        <option value="Cloud Computing">Cloud Computing</option>

                    </select>

                </div>

                <div class="col-md-4 mb-3">

                    <label>Lecture No</label>

                    <select name="lecture_no"
                        class="form-control"
                        required>

                        <option value="1">Lecture 1</option>
                        <option value="2">Lecture 2</option>
                        <option value="3">Lecture 3</option>
                        <option value="4">Lecture 4</option>
                        <option value="5">Lecture 5</option>
                        <option value="6">Lecture 6</option>

                    </select>

                </div>

            </div>

            <button type="button"
                class="btn btn-success mb-3"
                onclick="allPresent()">
                All Present
            </button>

            <button type="button"
                class="btn btn-danger mb-3"
                onclick="allAbsent()">
                All Absent
            </button>

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

    <script>
        function allPresent() {
            document
                .querySelectorAll(
                    'input[type="checkbox"]'
                )
                .forEach(
                    checkbox => checkbox.checked = true
                );
        }

        function allAbsent() {
            document
                .querySelectorAll(
                    'input[type="checkbox"]'
                )
                .forEach(
                    checkbox => checkbox.checked = false
                );
        }
    </script>

</body>

</html>