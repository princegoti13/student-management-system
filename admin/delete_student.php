<?php
session_start();
include '../db.php';

if(!isset($_SESSION['role']) || $_SESSION['role'] != 'admin')
{
    header("Location: ../login.php");
    exit();
}

if(isset($_GET['id']))
{
    $id = $_GET['id'];

    mysqli_query(
        $conn,
        "DELETE FROM users
         WHERE id='$id'
         AND role='student'"
    );
}

header("Location: dashboard.php");
exit();
?>