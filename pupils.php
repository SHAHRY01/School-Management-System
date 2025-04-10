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
$page_title = "Pupil Management";
include 'header.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_pupil'])) {
        // Retrieve form data for adding a new pupil.
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $date_of_birth = $_POST['date_of_birth'];
        $address = $_POST['address'];
        $medical_info = $_POST['medical_info'];
        $class_id = $_POST['class_id'];
        $parent1_id = $_POST['parent1_id'];
        $parent2_id = $_POST['parent2_id'];
        
        // Prepare and execute an SQL query to insert the new pupil into the database.
        $stmt = $conn->prepare("INSERT INTO pupils (first_name, last_name, date_of_birth, address, medical_info, class_id, parent1_id, parent2_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("sssssiii", $first_name, $last_name, $date_of_birth, $address, $medical_info, $class_id, $parent1_id, $parent2_id);
        
        // Check if the query was successful and display a success or error message.
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Pupil added successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error adding pupil: ' . $conn->error . '</div>';
        }
    } elseif (isset($_POST['update_pupil'])) {
        // Retrieve form data for updating an existing pupil.
        $pupil_id = $_POST['pupil_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $date_of_birth = $_POST['date_of_birth'];
        $address = $_POST['address'];
        $medical_info = $_POST['medical_info'];
        $class_id = $_POST['class_id'];
        $parent1_id = $_POST['parent1_id'];
        $parent2_id = $_POST['parent2_id'];
        
        // Prepare and execute an SQL query to update the pupil's details in the database.
        $stmt = $conn->prepare("UPDATE pupils SET first_name=?, last_name=?, date_of_birth=?, address=?, medical_info=?, class_id=?, parent1_id=?, parent2_id=? WHERE pupil_id=?");
        $stmt->bind_param("sssssiiii", $first_name, $last_name, $date_of_birth, $address, $medical_info, $class_id, $parent1_id, $parent2_id, $pupil_id);
        
        // Check if the query was successful and display a success or error message.
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Pupil updated successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error updating pupil: ' . $conn->error . '</div>';
        }
    }
} elseif (isset($_GET['delete'])) {
    // Handle deletion of a pupil.
    $pupil_id = $_GET['delete'];
    
    // Prepare and execute an SQL query to delete the pupil from the database.
    $stmt = $conn->prepare("DELETE FROM pupils WHERE pupil_id=?");
    $stmt->bind_param("i", $pupil_id);
    
    // Check if the query was successful and display a success or error message.
    if ($stmt->execute()) {
        echo '<div class="alert alert-success">Pupil deleted successfully!</div>';
    } else {
        echo '<div class="alert alert-danger">Error deleting pupil: ' . $conn->error . '</div>';
    }
}

// Get all pupils with class and parent information
$pupils = $conn->query("
    SELECT p.*, 
           c.class_name,
           CONCAT(pa1.first_name, ' ', pa1.last_name) AS parent1_name,
           CONCAT(pa2.first_name, ' ', pa2.last_name) AS parent2_name
    FROM pupils p
    LEFT JOIN classes c ON p.class_id = c.class_id
    LEFT JOIN parents pa1 ON p.parent1_id = pa1.parent_id
    LEFT JOIN parents pa2 ON p.parent2_id = pa2.parent_id
    ORDER BY p.last_name, p.first_name
");

// Get all classes for dropdown
$classes = $conn->query("SELECT class_id, class_name FROM classes ORDER BY class_name");

// Get all parents for dropdown
$parents = $conn->query("SELECT parent_id, CONCAT(first_name, ' ', last_name) AS name FROM parents ORDER BY last_name");
?>

<h1 class="section-title">Pupil Management</h1>

<div class="card">
    <div class="card-header">Add New Pupil</div>
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
            <label class="form-label" for="date_of_birth">Date of Birth</label>
            <input type="date" class="form-control" id="date_of_birth" name="date_of_birth" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="address">Address</label>
            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
        </div>
        <div class="form-group">
            <label class="form-label" for="medical_info">Medical Information</label>
            <textarea class="form-control" id="medical_info" name="medical_info" rows="3"></textarea>
        </div>
        <div class="form-group">
            <label class="form-label" for="class_id">Class</label>
            <div class="select-wrapper">
                <select class="form-control" id="class_id" name="class_id" required>
                    <option value="">Select Class</option>
                    <?php while ($class = $classes->fetch_assoc()): ?>
                        <option value="<?php echo $class['class_id']; ?>"><?php echo htmlspecialchars($class['class_name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label" for="parent1_id">Primary Parent/Guardian</label>
            <div class="select-wrapper">
                <select class="form-control" id="parent1_id" name="parent1_id" required>
                    <option value="">Select Parent/Guardian</option>
                    <?php while ($parent = $parents->fetch_assoc()): ?>
                        <option value="<?php echo $parent['parent_id']; ?>"><?php echo htmlspecialchars($parent['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label class="form-label" for="parent2_id">Secondary Parent/Guardian (Optional)</label>
            <div class="select-wrapper">
                <select class="form-control" id="parent2_id" name="parent2_id">
                    <option value="">Select Parent/Guardian (Optional)</option>
                    <?php 
                    $parents->data_seek(0); // Reset pointer
                    while ($parent = $parents->fetch_assoc()): ?>
                        <option value="<?php echo $parent['parent_id']; ?>"><?php echo htmlspecialchars($parent['name']); ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
        </div>
        <button type="submit" name="add_pupil" class="btn">Add Pupil</button>
    </form>
</div>


<div class="card">
    <div class="card-header">Pupil List</div>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Date of Birth</th>
                <th>Class</th>
                <th>Parents</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($pupil = $pupils->fetch_assoc()): ?>
                <tr>
                    <!-- Output pupil full name safely -->
                    <td><?php echo htmlspecialchars($pupil['first_name'] . ' ' . $pupil['last_name']); ?></td>
                    
                    <!-- Format date of birth in dd/mm/yyyy -->
                    <td><?php echo date('d/m/Y', strtotime($pupil['date_of_birth'])); ?></td>
                    
                    <!-- Output class name or fallback text if not assigned -->
                    <td><?php echo $pupil['class_name'] ? htmlspecialchars($pupil['class_name']) : 'Not assigned'; ?></td>
                    
                    <!-- Output parent(s) if available -->
                    <td>
                        <?php echo $pupil['parent1_name'] ? htmlspecialchars($pupil['parent1_name']) : 'None'; ?>
                        <?php if ($pupil['parent2_name']): ?>
                            <br><?php echo htmlspecialchars($pupil['parent2_name']); ?>
                        <?php endif; ?>
                    </td>

                    <!-- Action buttons: view, edit modal, delete -->
                    <td>
                        <!-- View button links to detailed profile -->
                        <a href="pupil_details.php?id=<?php echo $pupil['pupil_id']; ?>" class="btn btn-sm">View</a>
                        
                        <!-- Edit button opens modal and passes all pupil data -->
                        <button onclick="openEditModal(
                            <?php echo $pupil['pupil_id']; ?>,
                            '<?php echo htmlspecialchars($pupil['first_name'], ENT_QUOTES); ?>',
                            '<?php echo htmlspecialchars($pupil['last_name'], ENT_QUOTES); ?>',
                            '<?php echo $pupil['date_of_birth']; ?>',
                            '<?php echo htmlspecialchars($pupil['address'], ENT_QUOTES); ?>',
                            '<?php echo htmlspecialchars($pupil['medical_info'], ENT_QUOTES); ?>',
                            <?php echo $pupil['class_id'] ? $pupil['class_id'] : 'null'; ?>,
                            <?php echo $pupil['parent1_id'] ? $pupil['parent1_id'] : 'null'; ?>,
                            <?php echo $pupil['parent2_id'] ? $pupil['parent2_id'] : 'null'; ?>
                        )" class="btn btn-sm">Edit</button>
                        
                        <!-- Delete button with confirmation -->
                        <a href="pupils.php?delete=<?php echo $pupil['pupil_id']; ?>" 
                           class="btn btn-sm btn-danger" 
                           onclick="return confirmDelete()">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>


<!-- Edit Pupil Modal -->
<div id="editModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editModal')">&times;</span>
        <h2>Edit Pupil</h2>
        <form method="POST">
            <input type="hidden" id="edit_pupil_id" name="pupil_id">
            <div class="form-group">
                <label class="form-label" for="edit_first_name">First Name</label>
                <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_last_name">Last Name</label>
                <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_date_of_birth">Date of Birth</label>
                <input type="date" class="form-control" id="edit_date_of_birth" name="date_of_birth" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_address">Address</label>
                <textarea class="form-control" id="edit_address" name="address" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_medical_info">Medical Information</label>
                <textarea class="form-control" id="edit_medical_info" name="medical_info" rows="3"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_class_id">Class</label>
                <div class="select-wrapper">
                    <select class="form-control" id="edit_class_id" name="class_id" required>
                        <option value="">Select Class</option>
                        <?php 
                        $classes->data_seek(0); // Reset pointer
                        while ($class = $classes->fetch_assoc()): ?>
                            <option value="<?php echo $class['class_id']; ?>"><?php echo htmlspecialchars($class['class_name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_parent1_id">Primary Parent/Guardian</label>
                <div class="select-wrapper">
                    <select class="form-control" id="edit_parent1_id" name="parent1_id" required>
                        <option value="">Select Parent/Guardian</option>
                        <?php 
                        $parents->data_seek(0); // Reset pointer
                        while ($parent = $parents->fetch_assoc()): ?>
                            <option value="<?php echo $parent['parent_id']; ?>"><?php echo htmlspecialchars($parent['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_parent2_id">Secondary Parent/Guardian (Optional)</label>
                <div class="select-wrapper">
                    <select class="form-control" id="edit_parent2_id" name="parent2_id">
                        <option value="">Select Parent/Guardian (Optional)</option>
                        <?php 
                        $parents->data_seek(0); // Reset pointer
                        while ($parent = $parents->fetch_assoc()): ?>
                            <option value="<?php echo $parent['parent_id']; ?>"><?php echo htmlspecialchars($parent['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <button type="submit" name="update_pupil" class="btn">Update Pupil</button>
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

<!-- JavaScript to control the modal -->
<script>
    function openEditModal(id, firstName, lastName, dob, address, medicalInfo, classId, parent1Id, parent2Id) {
        // Populate the modal fields with the pupil's data
        document.getElementById('edit_pupil_id').value = id;
        document.getElementById('edit_first_name').value = firstName;
        document.getElementById('edit_last_name').value = lastName;
        document.getElementById('edit_date_of_birth').value = dob;
        document.getElementById('edit_address').value = address;
        document.getElementById('edit_medical_info').value = medicalInfo;
        document.getElementById('edit_class_id').value = classId;
        document.getElementById('edit_parent1_id').value = parent1Id;
        document.getElementById('edit_parent2_id').value = parent2Id;

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

