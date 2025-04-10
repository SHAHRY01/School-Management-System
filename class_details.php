<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the stylesheet -->
</head>
<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header("Location: classes.php");
    exit();
}

$class_id = $_GET['id'];
$page_title = "Class Details";
include 'header.php';

// Get class details
$class = $conn->query("
    SELECT c.*, t.teacher_id, CONCAT(t.first_name, ' ', t.last_name) AS teacher_name
    FROM classes c
    LEFT JOIN teachers t ON c.teacher_id = t.teacher_id
    WHERE c.class_id = $class_id
")->fetch_assoc();

if (!$class) {
    echo '<div class="alert alert-danger">Class not found!</div>';
    include 'footer.php';
    exit();
}

// Get pupils in this class
$pupils = $conn->query("
    SELECT p.* 
    FROM pupils p
    WHERE p.class_id = $class_id
    ORDER BY p.last_name, p.first_name
");

// Get teaching assistants for this class
$tas = $conn->query("
    SELECT ta.* 
    FROM teaching_assistants ta
    WHERE ta.class_id = $class_id
    ORDER BY ta.last_name, ta.first_name
");
?>

<h1 class="section-title">Class Details: <?php echo htmlspecialchars($class['class_name']); ?></h1>

<div class="card">
    <div class="card-header">Class Information</div>
    <div class="p-3">
        <p><strong>Class Name:</strong> <?php echo htmlspecialchars($class['class_name']); ?></p>
        <p><strong>Capacity:</strong> <?php echo $class['capacity']; ?></p>
        <p><strong>Current Pupils:</strong> <?php echo $pupils->num_rows; ?></p>
        <p><strong>Teacher:</strong> <?php echo $class['teacher_name'] ? htmlspecialchars($class['teacher_name']) : 'Not assigned'; ?></p>
    </div>
</div>

<div class="card">
    <div class="card-header">Pupils in this Class (<?php echo $pupils->num_rows; ?>/<?php echo $class['capacity']; ?>)</div>
    <?php if ($pupils->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Date of Birth</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pupil = $pupils->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pupil['first_name'] . ' ' . $pupil['last_name']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($pupil['date_of_birth'])); ?></td>
                        <td>
                            <a href="pupil_details.php?id=<?php echo $pupil['pupil_id']; ?>" class="btn btn-sm">View</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="p-3">No pupils in this class.</div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">Teaching Assistants</div>
    <?php if ($tas->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($ta = $tas->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ta['first_name'] . ' ' . $ta['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($ta['email']); ?></td>
                        <td><?php echo htmlspecialchars($ta['phone']); ?></td>
                        <td>
                            <a href="ta_details.php?id=<?php echo $ta['ta_id']; ?>" class="btn btn-sm">View</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="p-3">No teaching assistants assigned to this class.</div>
    <?php endif; ?>
</div>

<div class="text-center mt-3">
    <a href="classes.php" class="btn">Back to Classes</a>
</div>

<?php include 'footer.php'; ?>