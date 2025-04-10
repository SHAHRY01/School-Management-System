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
$page_title = "Teaching Assistant Management";
include 'header.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_ta'])) {
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $salary = $_POST['salary'];
        $background_check = isset($_POST['background_check']) ? 1 : 0;
        $hire_date = $_POST['hire_date'];
        $class_id = $_POST['class_id'];
        
        $stmt = $conn->prepare("INSERT INTO teaching_assistants (first_name, last_name, address, phone, email, salary, background_check, hire_date, class_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssdisi", $first_name, $last_name, $address, $phone, $email, $salary, $background_check, $hire_date, $class_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Teaching Assistant added successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error adding teaching assistant: ' . $conn->error . '</div>';
        }
    } elseif (isset($_POST['update_ta'])) {
        $ta_id = $_POST['ta_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $salary = $_POST['salary'];
        $background_check = isset($_POST['background_check']) ? 1 : 0;
        $hire_date = $_POST['hire_date'];
        $class_id = $_POST['class_id'];
        
        $stmt = $conn->prepare("UPDATE teaching_assistants SET first_name=?, last_name=?, address=?, phone=?, email=?, salary=?, background_check=?, hire_date=?, class_id=? WHERE ta_id=?");
        $stmt->bind_param("sssssdisii", $first_name, $last_name, $address, $phone, $email, $salary, $background_check, $hire_date, $class_id, $ta_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Teaching Assistant updated successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error updating teaching assistant: ' . $conn->error . '</div>';
        }
    }
} elseif (isset($_GET['delete'])) {
    $ta_id = $_GET['delete'];
    
    $stmt = $conn->prepare("DELETE FROM teaching_assistants WHERE ta_id=?");
    $stmt->bind_param("i", $ta_id);
    
    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Teaching Assistant deleted successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Error deleting teaching assistant: ' . $conn->error . '</div>';
    }
}

// Get all teaching assistants with class information
$tas = $conn->query("
    SELECT ta.*, c.class_name 
    FROM teaching_assistants ta
    LEFT JOIN classes c ON ta.class_id = c.class_id
    ORDER BY ta.last_name, ta.first_name
");

// Get all classes for dropdown
$classes = $conn->query("SELECT class_id, class_name FROM classes ORDER BY class_name");
?>

<h1 class="section-title">Teaching Assistant Management</h1>

<div class="card">
    <div class="card-header">Add New Teaching Assistant</div>
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
            <label class="form-label" for="class_id">Assigned Class (Optional)</label>
            <div class="select-wrapper">
                <select class="form-control" id="class_id" name="class_id">
                    <option value="">Select Class (Optional)</option>
                    <?php while ($class = $classes->fetch_assoc()): ?>
                        <option value="<?php echo $class['class_id']; ?>"><?php echo htmlspecialchars($class['class_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">
                <input type="checkbox" id="background_check" name="background_check">
                Background Check Completed
            </label>
        </div>
        <button type="submit" name="add_ta" class="btn">Add Teaching Assistant</button>
    </form>
</div>

<div class="card">
    <div class="card-header">Teaching Assistant List</div>
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
            <?php while ($ta = $tas->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($ta['first_name'] . ' ' . $ta['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($ta['phone']); ?></td>
                    <td><?php echo htmlspecialchars($ta['email']); ?></td>
                    <td>£<?php echo number_format($ta['salary'], 2); ?></td>
                    <td><?php echo $ta['class_name'] ? htmlspecialchars($ta['class_name']) : 'Not assigned'; ?></td>
                    <td>
                        <a href="ta_details.php?id=<?php echo $ta['ta_id']; ?>" class="btn btn-sm">View</a>
                        <button onclick="openEditModal(
                            <?php echo $ta['ta_id']; ?>,
                            '<?php echo htmlspecialchars($ta['first_name'], ENT_QUOTES); ?>',
                            '<?php echo htmlspecialchars($ta['last_name'], ENT_QUOTES); ?>',
                            '<?php echo htmlspecialchars($ta['address'], ENT_QUOTES); ?>',
                            '<?php echo htmlspecialchars($ta['phone'], ENT_QUOTES); ?>',
                            '<?php echo htmlspecialchars($ta['email'], ENT_QUOTES); ?>',
                            <?php echo $ta['salary']; ?>,
                            '<?php echo $ta['hire_date']; ?>',
                            <?php echo $ta['background_check']; ?>,
                            <?php echo $ta['class_id'] ? $ta['class_id'] : 'null'; ?>
                        )" class="btn btn-sm">Edit</button>
                        <a href="teaching_assistants.php?delete=<?php echo $ta['ta_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a>
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
            <button type="submit" name="update_teacher" class="btn">Update Teaching Assistant</button>
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

