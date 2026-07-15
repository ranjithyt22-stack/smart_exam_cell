<?php
// public/index.php
require_once __DIR__.'/../inc/functions.php';

if(is_logged_in()){
  $u = current_user();
  if($u['role']==='admin') header("Location: dashboard_admin.php");
  elseif($u['role']==='faculty') header("Location: dashboard_faculty.php");
  elseif($u['role']==='student') header("Location: dashboard_student.php");
  exit;
}
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Smart Exam Cell</title><link rel="stylesheet" href="styles.css"></head>
<body>
<main class="container">
  <div class="card" style="text-align:center; padding:40px;">
    <h1>Welcome to Smart Exam Cell Management System</h1>
    <p>Manage students, exams, courses, and results efficiently.</p>
    <a href="login.php"><button>Login</button></a>
  </div>
</main>
</body>
</html>
