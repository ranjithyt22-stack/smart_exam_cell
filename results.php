<?php
// public/results.php
require_once __DIR__.'/../inc/functions.php';
$u = current_user();
if(!$u || $u['role']!=='admin'){ header("Location: login.php"); exit; }

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['add_result'])){
    $exam_id = intval($_POST['exam_id']);
    $student_id = intval($_POST['student_id']);
    $marks = floatval($_POST['marks_obtained']);
    $grade = $mysqli->real_escape_string($_POST['grade']);
    $remarks = $mysqli->real_escape_string($_POST['remarks']);
    $mysqli->begin_transaction();
    try{
        $mysqli->query("INSERT INTO results (exam_id,student_id,marks_obtained,grade,remarks)
                        VALUES ($exam_id,$student_id,$marks,'$grade','$remarks')
                        ON DUPLICATE KEY UPDATE marks_obtained=$marks, grade='$grade', remarks='$remarks'");
        $mysqli->query("INSERT INTO audit_log (action,performed_by,details) VALUES 
                        ('add_or_update_result',{$_SESSION['user_id']},'Exam=$exam_id Student=$student_id Marks=$marks')");
        $mysqli->commit();
    }catch(Exception $e){
        $mysqli->rollback();
        $error = $e->getMessage();
    }
}

if(isset($_GET['action']) && $_GET['action']==='delete'){
    $id = (int)$_GET['id'];
    $mysqli->query("DELETE FROM results WHERE id=$id");
    header("Location: results.php");
    exit;
}

$exams = $mysqli->query("SELECT id, id AS exam_id, (SELECT code FROM courses WHERE id=exams.course_id) AS course_code, exam_date FROM exams ORDER BY exam_date DESC");
$students = $mysqli->query("SELECT s.student_id,u.full_name,s.roll_no FROM students s JOIN users u ON s.student_id=u.id ORDER BY u.full_name");
$results = $mysqli->query("SELECT r.*, e.exam_date, c.code AS course_code, u.full_name AS student_name
                           FROM results r
                           JOIN exams e ON r.exam_id=e.id
                           JOIN courses c ON e.course_id=c.id
                           JOIN users u ON u.id=r.student_id
                           ORDER BY r.id DESC LIMIT 200");
?>
<!doctype html>
<html>
<head><meta charset="utf-8"><title>Results</title><link rel="stylesheet" href="styles.css"></head>
<body>
<header><a href="dashboard_admin.php">Dashboard</a> | <a href="logout.php">Logout</a></header>
<main class="container">
<h1>Results</h1>
<?php if(!empty($error)): ?><div class="alert"><?= htmlspecialchars($error) ?></div><?php endif; ?>

<section class="card">
<h2>Add / Update Result</h2>
<form method="post">
  <label>Exam
    <select name="exam_id" required>
      <option value="">--select--</option>
      <?php while($e=$exams->fetch_assoc()): ?>
        <option value="<?= $e['exam_id'] ?>"><?= htmlspecialchars($e['course_code']." (".$e['exam_date'].")") ?></option>
      <?php endwhile; ?>
    </select>
  </label>
  <label>Student
    <select name="student_id" required>
      <option value="">--select--</option>
      <?php while($s=$students->fetch_assoc()): ?>
        <option value="<?= $s['student_id'] ?>"><?= htmlspecialchars($s['roll_no']." - ".$s['full_name']) ?></option>
      <?php endwhile; ?>
    </select>
  </label>
  <label>Marks<input name="marks_obtained" type="number" step="0.5" required></label>
  <label>Grade<input name="grade" maxlength="2" placeholder="A+"></label>
  <label>Remarks<input name="remarks"></label>
  <button name="add_result" type="submit">Save Result</button>
</form>
</section>

<section class="card">
<h2>All Results</h2>
<table>
<thead><tr><th>ID</th><th>Exam</th><th>Student</th><th>Marks</th><th>Grade</th><th>Remarks</th><th>Action</th></tr></thead>
<tbody>
<?php while($r=$results->fetch_assoc()): ?>
<tr>
<td><?= $r['id'] ?></td>
<td><?= htmlspecialchars($r['course_code'].' ('.$r['exam_date'].')') ?></td>
<td><?= htmlspecialchars($r['student_name']) ?></td>
<td><?= htmlspecialchars($r['marks_obtained']) ?></td>
<td><?= htmlspecialchars($r['grade']) ?></td>
<td><?= htmlspecialchars($r['remarks']) ?></td>
<td><a href="?action=delete&id=<?= $r['id'] ?>" onclick="return confirm('Delete result?')">Delete</a></td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</section>
</main>
</body></html>
