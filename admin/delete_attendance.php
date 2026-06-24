<?php
session_start();
require_once __DIR__ . '/../db.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$id = $_GET['id'];

mysqli_query(
    $conn,
    "DELETE FROM attendance
     WHERE id='$id'"
);

header("Location: attendance_history.php");
exit();
