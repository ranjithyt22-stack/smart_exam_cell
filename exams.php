<?php
// public/exams.php
require_once __DIR__.'/../inc/functions.php';
$u = current_user();
if(!$u || $u['role']!=='admin'){ header("Location: login.php"); exit; }

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['create_exam'])){
    $course_id = intval($_POST['course_id']);
    $exam_date = $mysqli->real_escape_string($_POST['exam_date']);
    $total = intval($_POST['total_marks']);
    $type = $mysqli->real_escape_string($_POST['exam_type']);
    $inv = intval($_POST['invigilator_id']);
    $venue = $mysqli->real_escape_string($_POST['venue']);
    $mysqli->query("INSERT INTO exams (course_id,exam_date,total_marks,exam_type,invigilator_id,venue)
                    VALUES ($course_id,'$exam_date',$total,'$type',$inv,'$venue')");
    header("Location: exams.php");
    exit;
}

if(isset($_GET['action']) && $_GET['action']==='delete'){
    $id = (int)$_GET['id'];
    $mysqli->query("DELETE FROM exams WHERE id=$id");
    header("Location: exams.php");
    exit;
}

$courses = $mysqli->query("SELECT id,code,title FROM courses ORDER BY code");
$faculty = $mysqli->query("SELECT f.faculty_id,u.full_name FROM faculty f JOIN users u ON f.faculty_id=u.id ORDER BY u.full_name");
$exams = $mysqli->query("SELECT e.*,c.code,c.title,u.full_name AS invigilator FROM exams e 
                         JOIN courses c ON e.course_id=c.id 
                         LEFT JOIN users u ON e.invigilator_id=u.id 
                         ORDER BY e.exam_date DESC");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Exams</title><link rel="stylesheet" href="styles.css"></head>
<body>
<header><a href="dashboard_admin.php">Dashboard</a> | <a href="logout.php">Logout</a></header>
<main class="container">
<h1>Exams</h1>
<section class="card">
<h2>Schedule Exam</h2>
<form method="post">
  <label>Course
    <select name="course_id" required>
      <option value="">--select--</option>
      <?php while($c=$courses->fetch_assoc()): ?>
        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['code']." - ".$c['title']) ?></option>
      <?php endwhile; ?>
    </select>
  </label>
  <label>Date<input name="exam_date" type="date" required></label>
  <label>Total Marks<input name="total_marks" type="number" value="100"></label>
  <label>Exam Type
    <select name="exam_type">
      <option>External</option><option>Internal</option><option>Supplementary</option>
    </select>
  </label>
  <label>Invigilator
    <select name="invigilator_id">
      <option value="">--none--</option>
      <?php while($f=$faculty->fetch_assoc()): ?>
        <option value="<?= $f['faculty_id'] ?>"><?= htmlspecialchars($f['full_name']) ?></option>
      <?php endwhile; ?>
    </select>
  </label>
  <label>Venue<input name="venue"></label>
  <button name="create_exam" type="submit">Add Exam</button>
</form>
</section>

<section class="card">
<h2>All Exams</h2>
<table>
<thead><tr><th>ID</th><th>Course</th><th>Date</th><th>Type</th><th>Total</th><th>Invigilator</th><th>Venue</th><th>Action</th></tr></thead>
<tbody>
<?php while($e=$exams->fetch_assoc()): ?>
<tr>
<td><?= $e['id'] ?></td>
<td><?= htmlspecialchars($e['code']." - ".$e['title']) ?></td>
<td><?= htmlspecialchars($e['exam_date']) ?></td>
<td><?= htmlspecialchars($e['exam_type']) ?></td>
<td><?= htmlspecialchars($e['total_marks']) ?></td>
<td><?= htmlspecialchars($e['invigilator']) ?></td>
<td><?= htmlspecialchars($e['venue']) ?></td>
<td><a href="?action=delete&id=<?= $e['id'] ?>" onclick="return confirm('Delete exam?')">Delete</a></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</section>
</main>
</body></html>
