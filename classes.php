<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the stylesheet -->
    <title><?php echo $page_title; ?> - UA92 School</title>
</head>

<?php
require_once 'config.php';
$page_title = "Class Management";
include 'header.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_class'])) {
        $class_name = $_POST['class_name'];
        $capacity = $_POST['capacity'];
        $teacher_id = $_POST['teacher_id'];
        
        $stmt = $conn->prepare("INSERT INTO classes (class_name, capacity, teacher_id) VALUES (?, ?, ?)");
        $stmt->bind_param("sii", $class_name, $capacity, $teacher_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Class added successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error adding class: ' . $conn->error . '</div>';
        }
    } elseif (isset($_POST['update_class'])) {
        $class_id = $_POST['class_id'];
        $class_name = $_POST['class_name'];
        $capacity = $_POST['capacity'];
        $teacher_id = $_POST['teacher_id'];
        
        $stmt = $conn->prepare("UPDATE classes SET class_name=?, capacity=?, teacher_id=? WHERE class_id=?");
        $stmt->bind_param("siii", $class_name, $capacity, $teacher_id, $class_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Class updated successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error updating class: ' . $conn->error . '</div>';
        }
    }
} elseif (isset($_GET['delete'])) {
    $class_id = $_GET['delete'];
    
    // First, check if there are pupils in the class
    $check = $conn->query("SELECT COUNT(*) FROM pupils WHERE class_id=$class_id")->fetch_row()[0];
    
    if ($check > 0) {
        echo '<div class="alert alert-danger">Cannot delete class with pupils assigned!</div>';
    } else {
        $stmt = $conn->prepare("DELETE FROM classes WHERE class_id=?");
        $stmt->bind_param("i", $class_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Class deleted successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error deleting class: ' . $conn->error . '</div>';
        }
    }
}

// Get all classes with teacher information
$classes = $conn->query("
    SELECT c.class_id, c.class_name, c.capacity, 
           t.teacher_id, CONCAT(t.first_name, ' ', t.last_name) AS teacher_name,
           (SELECT COUNT(*) FROM pupils WHERE class_id = c.class_id) AS pupil_count
    FROM classes c
    LEFT JOIN teachers t ON c.teacher_id = t.teacher_id
    ORDER BY c.class_name
");

// Get all teachers for dropdown
$teachers = $conn->query("SELECT teacher_id, CONCAT(first_name, ' ', last_name) AS name FROM teachers ORDER BY last_name");
?>

<h1 class="section-title">Class Management</h1>

<div class="card">
    <div class="card-header">Add New Class</div>
    <form method="POST">
        <div class="form-group">
            <label class="form-label" for="class_name">Class Name</label>
            <input type="text" class="form-control" id="class_name" name="class_name" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="capacity">Capacity</label>
            <input type="number" class="form-control" id="capacity" name="capacity" min="1" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="teacher_id">Teacher</label>
            <div class="select-wrapper">
                <select class="form-control" id="teacher_id" name="teacher_id" required>
                    <option value="">Select Teacher</option>
                    <?php while ($teacher = $teachers->fetch_assoc()): ?>
                        <option value="<?php echo $teacher['teacher_id']; ?>"><?php echo $teacher['name']; ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <button type="submit" name="add_class" class="btn">Add Class</button>
    </form>
</div>

<div class="card">
    <div class="card-header">Class List</div>
    <table class="table">
        <thead>
            <tr>
                <th>Class Name</th>
                <th>Teacher</th>
                <th>Pupils</th>
                <th>Capacity</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($class = $classes->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($class['class_name']); ?></td>
                    <td><?php echo $class['teacher_name'] ? htmlspecialchars($class['teacher_name']) : 'Not assigned'; ?></td>
                    <td><?php echo $class['pupil_count']; ?>/<?php echo $class['capacity']; ?></td>
                    <td><?php echo $class['capacity']; ?></td>
                    <td>
                        <a href="class_details.php?id=<?php echo $class['class_id']; ?>" class="btn btn-sm">View</a>
                        <button onclick="openEditModal(
                            <?php echo $class['class_id']; ?>,
                            '<?php echo htmlspecialchars($class['class_name'], ENT_QUOTES); ?>',
                            <?php echo $class['capacity']; ?>,
                            <?php echo $class['teacher_id'] ? $class['teacher_id'] : 'null'; ?>
                        )" class="btn btn-sm">Edit</button>
                        <a href="classes.php?delete=<?php echo $class['class_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Edit Class Modal -->
<div id="editModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editModal')">&times;</span>
        <h2>Edit Class</h2>
        <form method="POST">
            <input type="hidden" id="edit_class_id" name="class_id">
            <div class="form-group">
                <label class="form-label" for="edit_class_name">Class Name</label>
                <input type="text" class="form-control" id="edit_class_name" name="class_name" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_capacity">Capacity</label>
                <input type="number" class="form-control" id="edit_capacity" name="capacity" min="1" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_teacher_id">Teacher</label>
                <div class="select-wrapper">
                    <select class="form-control" id="edit_teacher_id" name="teacher_id" required>
                        <option value="">Select Teacher</option>
                        <?php 
                        $teachers->data_seek(0); // Reset pointer
                        while ($teacher = $teachers->fetch_assoc()): ?>
                            <option value="<?php echo $teacher['teacher_id']; ?>"><?php echo $teacher['name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <button type="submit" name="update_class" class="btn">Update Class</button>
        </form>
    </div>
</div>

<!-- Add the following CSS to style the modal -->
<style>
    .modal {
        display: none; /* Hidden by default */
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.5); /* Black background with opacity */
    }

    .modal-content {
        background-color: white;
        margin: 10% auto; /* 10% from the top and centered */
        padding: 20px;
        border-radius: 10px;
        width: 50%; /* Adjust the width as needed */
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        animation: fadeIn 0.3s ease-in-out;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>

<!-- Add the following JavaScript to control the modal -->
<script>
    function openEditModal(id, name, capacity, teacherId) {
        // Populate the modal fields with the class data
        document.getElementById('edit_class_id').value = id;
        document.getElementById('edit_class_name').value = name;
        document.getElementById('edit_capacity').value = capacity;
        document.getElementById('edit_teacher_id').value = teacherId;

        // Show the modal
        document.getElementById('editModal').style.display = 'block';
    }

    function closeModal(modalId) {
        // Hide the modal
        document.getElementById(modalId).style.display = 'none';
    }

    // Close the modal when clicking outside of it
    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    };
</script>

<?php include 'footer.php'; ?>

