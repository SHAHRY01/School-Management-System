<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the stylesheet -->
</head>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the stylesheet -->
</head>
<body>
    <!-- Your content here -->
</body>
</html>


<?php
require_once 'config.php';

if (!isset($_GET['id'])) {
    header("Location: parents.php");
    exit();
}

$parent_id = $_GET['id'];
$page_title = "Parent/Guardian Details";
include 'header.php';

// Get parent details
$parent = $conn->query("
    SELECT * FROM parents
    WHERE parent_id = $parent_id
")->fetch_assoc();

if (!$parent) {
    echo '<div class="alert alert-danger">Parent/Guardian not found!</div>';
    include 'footer.php';
    exit();
}

// Get pupils associated with this parent
$pupils = $conn->query("
    SELECT p.*, c.class_name
    FROM pupils p
    LEFT JOIN classes c ON p.class_id = c.class_id
    WHERE p.parent1_id = $parent_id OR p.parent2_id = $parent_id
    ORDER BY p.last_name, p.first_name
");
?>

<h1 class="section-title">Parent/Guardian Details: <?php echo htmlspecialchars($parent['first_name'] . ' ' . $parent['last_name']); ?></h1>

<div class="card">
    <div class="card-header">Contact Information</div>
    <div class="p-3">
        <p><strong>Name:</strong> <?php echo htmlspecialchars($parent['first_name'] . ' ' . $parent['last_name']); ?></p>
        <p><strong>Relationship:</strong> <?php echo htmlspecialchars($parent['relationship']); ?></p>
        <p><strong>Address:</strong> <?php echo nl2br(htmlspecialchars($parent['address'])); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($parent['phone']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($parent['email']); ?></p>
    </div>
</div>

<div class="card">
    <div class="card-header">Children at School</div>
    <?php if ($pupils->num_rows > 0): ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Class</th>
                    <th>Date of Birth</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($pupil = $pupils->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($pupil['first_name'] . ' ' . $pupil['last_name']); ?></td>
                        <td><?php echo $pupil['class_name'] ? htmlspecialchars($pupil['class_name']) : 'Not assigned'; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($pupil['date_of_birth'])); ?></td>
                        <td>
                            <a href="pupil_details.php?id=<?php echo $pupil['pupil_id']; ?>" class="btn btn-sm">View</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="p-3">No children currently enrolled at this school.</div>
    <?php endif; ?>
</div>

<div class="text-center mt-3">
    <a href="parents.php" class="btn">Back to Parents</a>
</div>

<?php include 'footer.php'; ?>

