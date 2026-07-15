<?php
// public/dashboard_faculty.php
require_once __DIR__.'/../inc/functions.php';
$u = current_user();
if(!$u || $u['role']!=='faculty'){ header("Location: login.php"); exit; }

$faculty_id = $u['id'];

// fetch faculty info
$info = $mysqli->query("SELECT * FROM faculty WHERE faculty_id=$faculty_id")->fetch_assoc();

// exams invigilated by this faculty
$exams = $mysqli->query("SELECT e.*, c.code, c.title 
                         FROM exams e 
                         JOIN courses c ON e.course_id=c.id
                         WHERE e.invigilator_id=$faculty_id
                         ORDER BY e.exam_date DESC");

// recent results of their exams
$results = $mysqli->query("SELECT r.*, u.full_name, c.code, c.title 
                           FROM results r 
                           JOIN exams e ON r.exam_id=e.id
                           JOIN courses c ON e.course_id=c.id
                           JOIN users u ON u.id=r.student_id
                           WHERE e.invigilator_id=$faculty_id
                           ORDER BY r.id DESC LIMIT 20");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Faculty Dashboard</title><link rel="stylesheet" href="styles.css"></head>
<body>
<header>
  <h1>Faculty Dashboard</h1>
  <nav><a href="logout.php">Logout</a></nav>
</header>
<main class="container">
  <section class="card">
    <h2>Welcome, <?= htmlspecialchars($u['full_name']) ?></h2>
    <p>Department: <?= htmlspecialchars($info['department']) ?><br>
    Employee No: <?= htmlspecialchars($info['employee_no']) ?><br>
    Phone: <?= htmlspecialchars($info['phone']) ?></p>
  </section>

  <section class="card">
    <h2>Exams Invigilated</h2>
    <table>
      <thead><tr><th>Course</th><th>Date</th><th>Type</th><th>Venue</th></tr></thead>
      <tbody>
      <?php while($e=$exams->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($e['code']." - ".$e['title']) ?></td>
          <td><?= htmlspecialchars($e['exam_date']) ?></td>
          <td><?= htmlspecialchars($e['exam_type']) ?></td>
          <td><?= htmlspecialchars($e['venue']) ?></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </section>

  <section class="card">
    <h2>Recent Results (Your Exams)</h2>
    <table>
      <thead><tr><th>Course</th><th>Student</th><th>Marks</th><th>Grade</th></tr></thead>
      <tbody>
      <?php while($r=$results->fetch_assoc()): ?>
        <tr>
          <td><?= htmlspecialchars($r['code']." - ".$r['title']) ?></td>
          <td><?= htmlspecialchars($r['full_name']) ?></td>
          <td><?= htmlspecialchars($r['marks_obtained']) ?></td>
          <td><?= htmlspecialchars($r['grade']) ?></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </section>
</main>
</body></html>
