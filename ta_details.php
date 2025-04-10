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
    header("Location: teaching_assistants.php");
    exit();
}

$ta_id = $_GET['id'];
$page_title = "Teaching Assistant Details";
include 'header.php';

// Get TA details
$ta = $conn->query("
    SELECT ta.*, c.class_name
    FROM teaching_assistants ta
    LEFT JOIN classes c ON ta.class_id = c.class_id
    WHERE ta.ta_id = $ta_id
")->fetch_assoc();

if (!$ta) {
    echo '<div class="alert alert-danger">Teaching Assistant not found!</div>';
    include 'footer.php';
    exit();
}

// Calculate years of service
$hire_date = new DateTime($ta['hire_date']);
$today = new DateTime();
$years_of_service = $hire_date->diff($today)->y;

// Get class history
$class_history = $conn->query("
    SELECT c.class_name, ch.start_date, ch.end_date
    FROM ta_class_history ch
    JOIN classes c ON ch.class_id = c.class_id
    WHERE ch.ta_id = $ta_id
    ORDER BY ch.start_date DESC
");
?>

<h1 class="section-title">Teaching Assistant Details: <?php echo htmlspecialchars($ta['first_name'] . ' ' . $ta['last_name']); ?></h1>

<div class="card">
    <div class="card-header">Personal Information</div>
    <div class="p-3">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($ta['first_name'] . ' ' . $ta['last_name']); ?></p>
        <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($ta['address'])); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($ta['phone']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($ta['email']); ?></p>
    </div>
</div>

<div class="card">
    <div class="card-header">Employment Information</div>
    <div class="p-3">
        <p><strong>Salary:</strong> £<?php echo number_format($ta['salary'], 2); ?></p>
        <p><strong>Hire Date:</strong> <?php echo date('d/m/Y', strtotime($ta['hire_date'])); ?></p>
        <p><strong>Years of Service:</strong> <?php echo $years_of_service; ?></p>
        <p><strong>Background Check:</strong> <?php echo $ta['background_check'] ? 'Completed' : 'Pending'; ?></p>
        <p><strong>Current Class:</strong> <?php echo $ta['class_name'] ? htmlspecialchars($ta['class_name']) : 'Not assigned'; ?></p>
    </div>
</div>

<div class="card">
    <div class="card-header">Class History</div>
    <?php if ($class_history->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Class</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($history = $class_history->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($history['class_name']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($history['start_date'])); ?></td>
                        <td><?php echo $history['end_date'] ? date('d/m/Y', strtotime($history['end_date'])) : 'Current'; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="p-3">No class history available.</div>
    <?php endif; ?>
</div>

<div class="text-center mt-3">
    <a href="teaching_assistants.php" class="btn">Back to Teaching Assistants</a>
</div>

<?php include 'footer.php'; ?>