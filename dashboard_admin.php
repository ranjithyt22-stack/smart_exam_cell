<?php
// public/dashboard_admin.php
require_once __DIR__.'/../inc/functions.php';
$u = current_user();
if(!$u || $u['role']!=='admin'){ header("Location: login.php"); exit; }

// fetch some stats
$res1 = $mysqli->query("SELECT COUNT(*) AS cnt FROM users WHERE role='student'");
$students_count = $res1->fetch_assoc()['cnt'];
$res2 = $mysqli->query("SELECT COUNT(*) AS cnt FROM users WHERE role='faculty'");
$faculty_count = $res2->fetch_assoc()['cnt'];
$res3 = $mysqli->query("SELECT COUNT(*) AS cnt FROM courses");
$courses_count = $res3->fetch_assoc()['cnt'];
?>
<!doctype html>
<html><head><meta charset="utf-8"><title>Admin - Dashboard</title><link rel="stylesheet" href="styles.css"></head>
<body>
<header><h1>Admin Dashboard</h1><nav><a href="students.php">Students</a> | <a href="faculty.php">Faculty</a> | <a href="courses.php">Courses</a> | <a href="exams.php">Exams</a> | <a href="results.php">Results</a> | <a href="logout.php">Logout</a></nav></header>
<main class="container">
  <div class="card stats">
    <div><h3><?= $students_count ?></h3><p>Students</p></div>
    <div><h3><?= $faculty_count ?></h3><p>Faculty</p></div>
    <div><h3><?= $courses_count ?></h3><p>Courses</p></div>
  </div>

  <section class="card">
    <h2>Recent Results</h2>
    <table>
      <thead><tr><th>Exam</th><th>Student</th><th>Marks</th><th>Grade</th></tr></thead>
      <tbody>
<?php
$r = $mysqli->query("SELECT results.*, exams.course_id, courses.title, users.full_name FROM results
JOIN exams ON results.exam_id = exams.id
JOIN courses ON courses.id = exams.course_id
JOIN users ON users.id = results.student_id
ORDER BY results.id DESC LIMIT 10");
while($row = $r->fetch_assoc()){
    echo "<tr><td>{$row['title']}</td><td>{$row['full_name']}</td><td>{$row['marks_obtained']}</td><td>{$row['grade']}</td></tr>";
}
?>
      </tbody>
    </table>
  </section>
</main>
</body></html>
