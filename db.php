<?php

$host = "mysql-db";
$user = "root";
$password = "root123";
$database = "studentdb";

$conn = mysqli_connect($host, $user, $password, $database);

if(!$conn){
    die("Connection Failed: " . mysqli_connect_error());
}
?>