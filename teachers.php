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
$page_title = "Teacher Management";
include 'header.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_teacher'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $salary = $_POST['salary'];
        $background_check = isset($_POST['background_check']) ? 1 : 0;
        $hire_date = $_POST['hire_date'];
        
        $stmt = $conn->prepare("INSERT INTO teachers (first_name, last_name, address, phone, email, salary, background_check, hire_date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssdis", $first_name, $last_name, $address, $phone, $email, $salary, $background_check, $hire_date);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Teacher added successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error adding teacher: ' . $conn->error . '</div>';
        }
    } elseif (isset($_POST['update_teacher'])) {
        $teacher_id = $_POST['teacher_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $salary = $_POST['salary'];
        $background_check = isset($_POST['background_check']) ? 1 : 0;
        $hire_date = $_POST['hire_date'];
        
        $stmt = $conn->prepare("UPDATE teachers SET first_name=?, last_name=?, address=?, phone=?, email=?, salary=?, background_check=?, hire_date=? WHERE teacher_id=?");
        $stmt->bind_param("sssssdisi", $first_name, $last_name, $address, $phone, $email, $salary, $background_check, $hire_date, $teacher_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Teacher updated successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error updating teacher: ' . $conn->error . '</div>';
        }
    }
} elseif (isset($_GET['delete'])) {
    $teacher_id = $_GET['delete'];
    
    // First check if teacher is assigned to any class
    $check = $conn->query("SELECT COUNT(*) FROM classes WHERE teacher_id=$teacher_id")->fetch_row()[0];
    
    if ($check > 0) {
        echo '<div class="alert alert-danger">Cannot delete teacher assigned to a class!</div>';
    } else {
        $stmt = $conn->prepare("DELETE FROM teachers WHERE teacher_id=?");
        $stmt->bind_param("i", $teacher_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Teacher deleted successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error deleting teacher: ' . $conn->error . '</div>';
        }
    }
}

// Get all teachers with class information
$teachers = $conn->query("
    SELECT t.*, c.class_name 
    FROM teachers t
    LEFT JOIN classes c ON t.teacher_id = c.teacher_id
    ORDER BY t.last_name, t.first_name
");

?>

<h1 class="section-title">Teacher Management</h1>

<div class="card">
    <div class="card-header">Add New Teacher</div>
    <form method="POST">
        <div class="form-group">
            <label class="form-label" for="first_name">First Name</label>
            <input type="text" class="form-control" id="first_name" name="first_name" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="last_name">Last Name</label>
            <input type="text" class="form-control" id="last_name" name="last_name" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="address">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label class="form-label" for="phone">Phone</label>
            <input type="tel" class="form-control" id="phone" name="phone" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="salary">Salary (£)</label>
            <input type="number" class="form-control" id="salary" name="salary" step="0.01" min="0" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="hire_date">Hire Date</label>
            <input type="date" class="form-control" id="hire_date" name="hire_date" required>
        </div>
        <div class="form-group">
            <label class="form-label">
                <input type="checkbox" id="background_check" name="background_check">
                Background Check Completed
            </label>
        </div>
        <button type="submit" name="add_teacher" class="btn">Add Teacher</button>
    </form>
</div>

<div class="card">
    <div class="card-header">Teacher List</div>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Salary</th>
                <th>Class</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($teacher = $teachers->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($teacher['phone']); ?></td>
                    <td><?php echo htmlspecialchars($teacher['email']); ?></td>
                    <td>£<?php echo number_format($teacher['salary'], 2); ?></td>
                    <td><?php echo $teacher['class_name'] ? htmlspecialchars($teacher['class_name']) : 'Not assigned'; ?></td>
                    <td>
                        <a href="teacher_details.php?id=<?php echo $teacher['teacher_id']; ?>" class="btn btn-sm">View</a>
                        <button onclick="openEditModal(
                            <?php echo $teacher['teacher_id']; ?>,
                            '<?php echo htmlspecialchars($teacher['first_name'], ENT_QUOTES); ?>',
                            '<?php echo htmlspecialchars($teacher['last_name'], ENT_QUOTES); ?>',
                            '<?php echo htmlspecialchars($teacher['address'], ENT_QUOTES); ?>',
                            '<?php echo htmlspecialchars($teacher['phone'], ENT_QUOTES); ?>',
                            '<?php echo htmlspecialchars($teacher['email'], ENT_QUOTES); ?>',
                            <?php echo $teacher['salary']; ?>,
                            '<?php echo $teacher['hire_date']; ?>',
                            <?php echo $teacher['background_check']; ?>
                        )" class="btn btn-sm">Edit</button>
                        <a href="teachers.php?delete=<?php echo $teacher['teacher_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Edit Teacher Modal -->
<div id="editModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editModal')">&times;</span>
        <h2>Edit Teacher</h2>
        <form method="POST">
            <input type="hidden" id="edit_teacher_id" name="teacher_id">
            <div class="form-group">
                <label class="form-label" for="edit_first_name">First Name</label>
                <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_last_name">Last Name</label>
                <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_address">Address</label>
                <textarea class="form-control" id="edit_address" name="address" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_phone">Phone</label>
                <input type="tel" class="form-control" id="edit_phone" name="phone" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_email">Email</label>
                <input type="email" class="form-control" id="edit_email" name="email" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_salary">Salary (£)</label>
                <input type="number" class="form-control" id="edit_salary" name="salary" step="0.01" min="0" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_hire_date">Hire Date</label>
                <input type="date" class="form-control" id="edit_hire_date" name="hire_date" required>
            </div>
            <div class="form-group">
                <label class="form-label">
                    <input type="checkbox" id="edit_background_check" name="background_check">
                    Background Check Completed
                </label>
            </div>
            <button type="submit" name="update_teacher" class="btn">Update Teacher</button>
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


<script>
    function openEditModal(id, firstName, lastName, address, phone, email, salary, hireDate, backgroundCheck) {
        // Populate the modal fields with the teacher's data
        document.getElementById('edit_teacher_id').value = id;
        document.getElementById('edit_first_name').value = firstName;
        document.getElementById('edit_last_name').value = lastName;
        document.getElementById('edit_address').value = address;
        document.getElementById('edit_phone').value = phone;
        document.getElementById('edit_email').value = email;
        document.getElementById('edit_salary').value = salary;
        document.getElementById('edit_hire_date').value = hireDate;
        document.getElementById('edit_background_check').checked = backgroundCheck == 1;

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



