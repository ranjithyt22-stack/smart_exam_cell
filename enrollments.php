<?php
// public/enrollments.php
require_once __DIR__.'/../inc/functions.php';
$u = current_user();
if(!$u || $u['role']!=='admin'){ header("Location: login.php"); exit; }

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['enroll'])){
    $student_id = intval($_POST['student_id']);
    $course_id = intval($_POST['course_id']);
    $mysqli->query("INSERT IGNORE INTO enrollments (student_id, course_id) VALUES ($student_id,$course_id)");
    header("Location: enrollments.php");
    exit;
}

if(isset($_GET['action']) && $_GET['action']==='delete'){
    $id = (int)$_GET['id'];
    $mysqli->query("DELETE FROM enrollments WHERE id=$id");
    header("Location: enrollments.php");
    exit;
}

$students = $mysqli->query("SELECT s.student_id,u.full_name,s.roll_no FROM students s JOIN users u ON s.student_id=u.id ORDER BY u.full_name");
$courses = $mysqli->query("SELECT id,code,title FROM courses ORDER BY code");
$enrollments = $mysqli->query("SELECT e.id, u.full_name, s.roll_no, c.code, c.title
                               FROM enrollments e
                               JOIN students s ON e.student_id=s.student_id
                               JOIN users u ON u.id=s.student_id
                               JOIN courses c ON e.course_id=c.id
                               ORDER BY e.id DESC");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Enrollments</title><link rel="stylesheet" href="styles.css"></head>
<body>
<header><a href="dashboard_admin.php">Dashboard</a> | <a href="logout.php">Logout</a></header>
<main class="container">
<h1>Enrollments</h1>

<section class="card">
<h2>Enroll a Student</h2>
<form method="post">
  <label>Student
    <select name="student_id" required>
      <option value="">--select--</option>
      <?php while($s=$students->fetch_assoc()): ?>
        <option value="<?= $s['student_id'] ?>"><?= htmlspecialchars($s['roll_no']." - ".$s['full_name']) ?></option>
      <?php endwhile; ?>
    </select>
  </label>
  <label>Course
    <select name="course_id" required>
      <option value="">--select--</option>
      <?php while($c=$courses->fetch_assoc()): ?>
        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['code']." - ".$c['title']) ?></option>
      <?php endwhile; ?>
    </select>
  </label>
  <button name="enroll" type="submit">Enroll</button>
</form>
</section>

<section class="card">
<h2>All Enrollments</h2>
<table>
<thead><tr><th>ID</th><th>Roll No</th><th>Student</th><th>Course</th><th>Action</th></tr></thead>
<tbody>
<?php while($e=$enrollments->fetch_assoc()): ?>
<tr>
<td><?= $e['id'] ?></td>
<td><?= $e['roll_no'] ?></td>
<td><?= htmlspecialchars($e['full_name']) ?></td>
<td><?= htmlspecialchars($e['code']." - ".$e['title']) ?></td>
<td><a href="?action=delete&id=<?= $e['id'] ?>" onclick="return confirm('Remove enrollment?')">Delete</a></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</section>
</main>
</body></html>
