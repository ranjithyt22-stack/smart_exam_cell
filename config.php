<?php
// inc/config.php
$DB_HOST = 'localhost';
$DB_USER = 'root';
$DB_PASS = ''; // set your root password if any
$DB_NAME = 'smart_exam_cell';

$mysqli = new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME);
if ($mysqli->connect_errno) {
    die("Database connection failed: " . $mysqli->connect_error);
}
session_start();
?>
