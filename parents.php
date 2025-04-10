<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="styles.css"> <!-- Link to the stylesheet -->
</head>

<?php
require_once 'config.php'; // Include the database configuration file.
$page_title = "Parent/Guardian Management";
include 'header.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_parent'])) {
        // Add a new parent/guardian to the database.
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $relationship = $_POST['relationship'];
        
        $stmt = $conn->prepare("INSERT INTO parents (first_name, last_name, address, phone, email, relationship) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $first_name, $last_name, $address, $phone, $email, $relationship);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Parent/Guardian added successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error adding parent/guardian: ' . $conn->error . '</div>';
        }
    } elseif (isset($_POST['update_parent'])) {
        // Update an existing parent's/guardian's details.
        $parent_id = $_POST['parent_id'];
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $address = $_POST['address'];
        $phone = $_POST['phone'];
        $email = $_POST['email'];
        $relationship = $_POST['relationship'];
        
        $stmt = $conn->prepare("UPDATE parents SET first_name=?, last_name=?, address=?, phone=?, email=?, relationship=? WHERE parent_id=?");
        $stmt->bind_param("ssssssi", $first_name, $last_name, $address, $phone, $email, $relationship, $parent_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Parent/Guardian updated successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error updating parent/guardian: ' . $conn->error . '</div>';
        }
    }
} elseif (isset($_GET['delete'])) {
    // Handle deletion of a parent/guardian.
    $parent_id = $_GET['delete'];
    
    // First check if parent is assigned to any pupils
    $check = $conn->query("SELECT COUNT(*) FROM pupils WHERE parent1_id=$parent_id OR parent2_id=$parent_id")->fetch_row()[0];
    
    if ($check > 0) {
        echo '<div class="alert alert-danger">Cannot delete parent/guardian assigned to pupils!</div>';
    } else {
        $stmt = $conn->prepare("DELETE FROM parents WHERE parent_id=?");
        $stmt->bind_param("i", $parent_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Parent/Guardian deleted successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error deleting parent/guardian: ' . $conn->error . '</div>';
        }
    }
}

// Get all parents
$parents = $conn->query("SELECT * FROM parents ORDER BY last_name, first_name");
?>

<h1 class="section-title">Parent/Guardian Management</h1>

<div class="card">
    <div class="card-header">Add New Parent/Guardian</div>
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
            <label class="form-label" for="relationship">Relationship</label>
            <input type="text" class="form-control" id="relationship" name="relationship" required>
        </div>
        <button type="submit" name="add_parent" class="btn">Add Parent/Guardian</button>
    </form>
</div>
<!-- Display the list of parents/guardians -->
<div class="card">
    <div class="card-header">Parent/Guardian List</div>
    <table class="table">
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Relationship</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($parent = $parents->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($parent['first_name'] . ' ' . $parent['last_name']); ?></td>
                    <td><?php echo htmlspecialchars($parent['phone']); ?></td>
                    <td><?php echo htmlspecialchars($parent['email']); ?></td>
                    <td><?php echo htmlspecialchars($parent['relationship']); ?></td>
                    <td>
                        <!-- Action buttons for viewing, editing, and deleting -->
                        <a href="parent_details.php?id=<?php echo $parent['parent_id']; ?>" class="btn btn-sm">View</a>
                        <button onclick="openEditModal(
                            <?php echo $parent['parent_id']; ?>,
                            '<?php echo htmlspecialchars($parent['first_name'], ENT_QUOTES); ?>',
                            '<?php echo htmlspecialchars($parent['last_name'], ENT_QUOTES); ?>',
                            '<?php echo htmlspecialchars($parent['address'], ENT_QUOTES); ?>',
                            '<?php echo htmlspecialchars($parent['phone'], ENT_QUOTES); ?>',
                            '<?php echo htmlspecialchars($parent['email'], ENT_QUOTES); ?>',
                            '<?php echo htmlspecialchars($parent['relationship'], ENT_QUOTES); ?>'
                        )" class="btn btn-sm">Edit</button>
                        <a href="parents.php?delete=<?php echo $parent['parent_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Edit Parent Modal -->
<div id="editModal" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editModal')">&times;</span>
        <h2>Edit Parent/Guardian</h2>
        <form method="POST">
            <input type="hidden" id="edit_parent_id" name="parent_id">
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
                <label class="form-label" for="edit_relationship">Relationship</label>
                <input type="text" class="form-control" id="edit_relationship" name="relationship" required>
            </div>
            <button type="submit" name="update_parent" class="btn">Update Parent/Guardian</button>
        </form>
    </div>
</div>

<script>
function openEditModal(id, firstName, lastName, address, phone, email, relationship) {
    document.getElementById('edit_parent_id').value = id;
    document.getElementById('edit_first_name').value = firstName;
    document.getElementById('edit_last_name').value = lastName;
    document.getElementById('edit_address').value = address;
    document.getElementById('edit_phone').value = phone;
    document.getElementById('edit_email').value = email;
    document.getElementById('edit_relationship').value = relationship;
    document.getElementById('editModal').style.display = 'block';
}
</script>

<?php include 'footer.php'; ?>

