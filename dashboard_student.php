<?php
// public/dashboard_student.php
require_once __DIR__.'/../inc/functions.php';
$u = current_user();
if(!$u || $u['role']!=='student'){ header("Location: login.php"); exit; }

$student_id = $u['id'];

// get student details
$info = $mysqli->query("SELECT * FROM students WHERE student_id=$student_id")->fetch_assoc();

// enrolled courses
$courses = $mysqli->query("SELECT c.code, c.title, c.semester FROM enrollments e
                           JOIN courses c ON e.course_id=c.id
                           WHERE e.student_id=$student_id");

// upcoming exams
$exams = $mysqli->query("SELECT e.exam_date, e.exam_type, e.venue, c.code, c.title
                         FROM exams e 
                         JOIN courses c ON e.course_id=c.id
                         JOIN enrollments en ON en.course_id=c.id
                         WHERE en.student_id=$student_id
                         ORDER BY e.exam_date ASC");

// results
$results = $mysqli->query("SELECT r.*, c.code, c.title
                           FROM results r
                           JOIN exams e ON r.exam_id=e.id
                           JOIN courses c ON e.course_id=c.id
                           WHERE r.student_id=$student_id
                           ORDER BY e.exam_date DESC");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Student Dashboard</title><link rel="stylesheet" href="styles.css"></head>
<body>
<header>
  <h1>Student Dashboard</h1>
  <nav><a href="logout.php">Logout</a></nav>
</header>
<main class="container">
  <section class="card">
    <h2>Welcome, <?= htmlspecialchars($u['full_name']) ?></h2>
    <p>Roll No: <?= htmlspecialchars($info['roll_no']) ?><br>
    Department: <?= htmlspecialchars($info['department']) ?><br>
    Semester: <?= htmlspecialchars($info['semester']) ?></p>
  </section>

  <section class="card">
    <h2>My Enrolled Courses</h2>
    <table>
      <thead><tr><th>Code</th><th>Title</th><th>Semester</th></tr></thead>
      <tbody>
      <?php while($c=$courses->fetch_assoc()): ?>
        <tr><td><?= $c['code'] ?></td><td><?= $c['title'] ?></td><td><?= $c['semester'] ?></td></tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </section>

  <section class="card">
    <h2>Upcoming Exams</h2>
    <table>
      <thead><tr><th>Course</th><th>Date</th><th>Type</th><th>Venue</th></tr></thead>
      <tbody>
      <?php while($e=$exams->fetch_assoc()): ?>
        <tr><td><?= $e['code']." - ".$e['title'] ?></td><td><?= $e['exam_date'] ?></td><td><?= $e['exam_type'] ?></td><td><?= $e['venue'] ?></td></tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </section>

  <section class="card">
    <h2>My Results</h2>
    <table>
      <thead><tr><th>Course</th><th>Marks</th><th>Grade</th><th>Remarks</th></tr></thead>
      <tbody>
      <?php while($r=$results->fetch_assoc()): ?>
        <tr><td><?= htmlspecialchars($r['code']." - ".$r['title']) ?></td><td><?= $r['marks_obtained'] ?></td><td><?= $r['grade'] ?></td><td><?= htmlspecialchars($r['remarks']) ?></td></tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </section>
</main>
</body></html>
