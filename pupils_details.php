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
    header("Location: pupils.php");
    exit();
}

$pupil_id = $_GET['id'];
$page_title = "Pupil Details";
include 'header.php';

// Get pupil details
$pupil = $conn->query("
    SELECT p.*, c.class_name,
           CONCAT(pa1.first_name, ' ', pa1.last_name) AS parent1_name,
           CONCAT(pa2.first_name, ' ', pa2.last_name) AS parent2_name
    FROM pupils p
    LEFT JOIN classes c ON p.class_id = c.class_id
    LEFT JOIN parents pa1 ON p.parent1_id = pa1.parent_id
    LEFT JOIN parents pa2 ON p.parent2_id = pa2.parent_id
    WHERE p.pupil_id = $pupil_id
")->fetch_assoc();

if (!$pupil) {
    echo '<div class="alert alert-danger">Pupil not found!</div>';
    include 'footer.php';
    exit();
}

// Get current book loans
$loans = $conn->query("
    SELECT bl.*, b.title, b.author, bl.due_date,
           DATEDIFF(bl.due_date, CURDATE()) AS days_remaining
    FROM book_loans bl
    JOIN library_books b ON bl.book_id = b.book_id
    WHERE bl.pupil_id = $pupil_id AND bl.return_date IS NULL
    ORDER BY bl.due_date
");

// Get dinner money payments
$payments = $conn->query("
    SELECT * FROM dinner_money
    WHERE pupil_id = $pupil_id
    ORDER BY payment_date DESC
    LIMIT 5
");
?>

<h1 class="section-title">Pupil Details: <?php echo htmlspecialchars($pupil['first_name'] . ' ' . $pupil['last_name']); ?></h1>

<div class="card">
    <div class="card-header">Personal Information</div>
    <div class="p-3">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($pupil['first_name'] . ' ' . $pupil['last_name']); ?></p>
        <p><strong>Date of Birth:</strong> <?php echo date('d/m/Y', strtotime($pupil['date_of_birth'])); ?></p>
        <p><strong>Age:</strong> <?php echo date_diff(date_create($pupil['date_of_birth']), date_create('today'))->y; ?> years</p>
        <p><strong>Class:</strong> <?php echo $pupil['class_name'] ? htmlspecialchars($pupil['class_name']) : 'Not assigned'; ?></p>
    </div>
</div>

<div class="card">
    <div class="card-header">Contact Information</div>
    <div class="p-3">
        <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($pupil['address'])); ?></p>
        <p><strong>Primary Parent/Guardian:</strong> <?php echo $pupil['parent1_name'] ? htmlspecialchars($pupil['parent1_name']) : 'None'; ?></p>
        <p><strong>Secondary Parent/Guardian:</strong> <?php echo $pupil['parent2_name'] ? htmlspecialchars($pupil['parent2_name']) : 'None'; ?></p>
    </div>
</div>

<div class="card">
    <div class="card-header">Medical Information</div>
    <div class="p-3">
        <?php echo $pupil['medical_info'] ? nl2br(htmlspecialchars($pupil['medical_info'])) : 'No medical information recorded.'; ?>
    </div>
</div>

<div class="card">
    <div class="card-header">Current Book Loans</div>
    <?php if ($loans->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Book</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($loan = $loans->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($loan['title']); ?> by <?php echo htmlspecialchars($loan['author']); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($loan['due_date'])); ?></td>
                        <td>
                            <?php if ($loan['days_remaining'] > 0): ?>
                                <span class="badge badge-success">Due in <?php echo $loan['days_remaining']; ?> days</span>
                            <?php else: ?>
                                <span class="badge badge-danger">Overdue by <?php echo abs($loan['days_remaining']); ?> days</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="p-3">No current book loans.</div>
    <?php endif; ?>
</div>

<div class="card">
    <div class="card-header">Recent Dinner Money Payments</div>
    <?php if ($payments->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Amount</th>
                    <th>Payment Date</th>
                    <th>Method</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($payment = $payments->fetch_assoc()): ?>
                    <tr>
                        <td>£<?php echo number_format($payment['amount'], 2); ?></td>
                        <td><?php echo date('d/m/Y', strtotime($payment['payment_date'])); ?></td>
                        <td><?php echo htmlspecialchars($payment['payment_method']); ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="p-3">No payment history.</div>
    <?php endif; ?>
</div>

<div class="text-center mt-3">
    <a href="pupils.php" class="btn">Back to Pupils</a>
</div>

<?php include 'footer.php'; ?>