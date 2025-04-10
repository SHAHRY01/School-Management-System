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
$page_title = "Library Management";
include 'header.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_book'])) {
        $title = $_POST['title'];
        $author = $_POST['author'];
        $isbn = $_POST['isbn'];
        $published_year = $_POST['published_year'];
        
        $stmt = $conn->prepare("INSERT INTO library_books (title, author, isbn, published_year) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sssi", $title, $author, $isbn, $published_year);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Book added successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error adding book: ' . $conn->error . '</div>';
        }
    } elseif (isset($_POST['update_book'])) {
        $book_id = $_POST['book_id'];
        $title = $_POST['title'];
        $author = $_POST['author'];
        $isbn = $_POST['isbn'];
        $published_year = $_POST['published_year'];
        
        $stmt = $conn->prepare("UPDATE library_books SET title=?, author=?, isbn=?, published_year=? WHERE book_id=?");
        $stmt->bind_param("sssii", $title, $author, $isbn, $published_year, $book_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Book updated successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error updating book: ' . $conn->error . '</div>';
        }
    } elseif (isset($_POST['loan_book'])) {
        $book_id = $_POST['book_id'];
        $pupil_id = $_POST['pupil_id'];
        $loan_date = date('Y-m-d');
        $due_date = date('Y-m-d', strtotime('+14 days'));
        
        // Check if book is available
        $available = $conn->query("SELECT available FROM library_books WHERE book_id=$book_id")->fetch_row()[0];
        
        if (!$available) {
            echo '<div class="alert alert-danger">Book is not available for loan!</div>';
        } else {
            $stmt = $conn->prepare("INSERT INTO book_loans (book_id, pupil_id, loan_date, due_date) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiss", $book_id, $pupil_id, $loan_date, $due_date);
            
            if ($stmt->execute()) {
                // Update book availability
                $conn->query("UPDATE library_books SET available=FALSE WHERE book_id=$book_id");
                echo '<div class="alert alert-success">Book loaned successfully!</div>';
            } else {
                echo '<div class="alert alert-danger">Error loaning book: ' . $conn->error . '</div>';
            }
        }
    } elseif (isset($_POST['return_book'])) {
        $loan_id = $_POST['loan_id'];
        $book_id = $_POST['book_id'];
        $return_date = date('Y-m-d');
        
        $stmt = $conn->prepare("UPDATE book_loans SET return_date=? WHERE loan_id=?");
        $stmt->bind_param("si", $return_date, $loan_id);
        
        if ($stmt->execute()) {
            // Update book availability
            $conn->query("UPDATE library_books SET available=TRUE WHERE book_id=$book_id");
            echo '<div class="alert alert-success">Book returned successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error returning book: ' . $conn->error . '</div>';
        }
    }
} elseif (isset($_GET['delete'])) {
    $book_id = $_GET['delete'];
    
    // First check if book is on loan
    $check = $conn->query("SELECT COUNT(*) FROM book_loans WHERE book_id=$book_id AND return_date IS NULL")->fetch_row()[0];
    
    if ($check > 0) {
        echo '<div class="alert alert-danger">Cannot delete book that is currently on loan!</div>';
    } else {
        $stmt = $conn->prepare("DELETE FROM library_books WHERE book_id=?");
        $stmt->bind_param("i", $book_id);
        
        if ($stmt->execute()) {
            echo '<div class="alert alert-success">Book deleted successfully!</div>';
        } else {
            echo '<div class="alert alert-danger">Error deleting book: ' . $conn->error . '</div>';
        }
    }
}

// Get all books //
$books = $conn->query("SELECT * FROM library_books ORDER BY title");

// Get all pupils for loan dropdown // 
$pupils = $conn->query("SELECT pupil_id, CONCAT(first_name, ' ', last_name) AS name FROM pupils ORDER BY last_name");

// Get current loans // 
$loans = $conn->query("
    SELECT bl.*, b.title, b.author, 
           CONCAT(p.first_name, ' ', p.last_name) AS pupil_name,
           p.pupil_id,
           DATEDIFF(bl.due_date, CURDATE()) AS days_remaining
    FROM book_loans bl
    JOIN library_books b ON bl.book_id = b.book_id
    JOIN pupils p ON bl.pupil_id = p.pupil_id
    WHERE bl.return_date IS NULL
    ORDER BY bl.due_date
");
?>

<h1 class="section-title">Library Management</h1>

<div class="card">
    <div class="card-header">Add New Book</div>
    <form method="POST">
        <div class="form-group">
            <label class="form-label" for="title">Title</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="author">Author</label>
            <input type="text" class="form-control" id="author" name="author" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="isbn">ISBN</label>
            <input type="text" class="form-control" id="isbn" name="isbn" required>
        </div>
        <div class="form-group">
            <label class="form-label" for="published_year">Published Year</label>
            <input type="number" class="form-control" id="published_year" name="published_year" min="1800" max="<?php echo date('Y'); ?>" required>
        </div>
        <button type="submit" name="add_book" class="btn">Add Book</button>
    </form>
</div>

<div class="card">
    <div class="card-header">Book List</div>
    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Year</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($book = $books->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($book['title']); ?></td>
                    <td><?php echo htmlspecialchars($book['author']); ?></td>
                    <td><?php echo htmlspecialchars($book['isbn']); ?></td>
                    <td><?php echo $book['published_year']; ?></td>
                    <td>
                        <?php if ($book['available']): ?>
                            <span class="badge badge-success">Available</span>
                        <?php else: ?>
                            <span class="badge badge-danger">On Loan</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button onclick="openEditModal(
                            <?php echo $book['book_id']; ?>,
                            '<?php echo htmlspecialchars($book['title'], ENT_QUOTES); ?>',
                            '<?php echo htmlspecialchars($book['author'], ENT_QUOTES); ?>',
                            '<?php echo htmlspecialchars($book['isbn'], ENT_QUOTES); ?>',
                            <?php echo $book['published_year']; ?>
                        )" class="btn btn-sm">Edit</button>
                        <button onclick="openLoanModal(<?php echo $book['book_id']; ?>)" class="btn btn-sm btn-success" <?php echo !$book['available'] ? 'disabled' : ''; ?>>Loan</button>
                        <a href="library.php?delete=<?php echo $book['book_id']; ?>" class="btn btn-sm btn-danger" onclick="return confirmDelete()">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<div class="card">
    <div class="card-header">Current Loans</div>
    <table class="table">
        <thead>
            <tr>
                <th>Book</th>
                <th>Pupil</th>
                <th>Loan Date</th>
                <th>Due Date</th>
                <th>Days Remaining</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($loan = $loans->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($loan['title']); ?></td>
                    <td><?php echo htmlspecialchars($loan['pupil_name']); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($loan['loan_date'])); ?></td>
                    <td><?php echo date('d/m/Y', strtotime($loan['due_date'])); ?></td>
                    <td>
                        <?php if ($loan['days_remaining'] > 0): ?>
                            <span class="badge badge-success"><?php echo $loan['days_remaining']; ?></span>
                        <?php else: ?>
                            <span class="badge badge-danger">Overdue by <?php echo abs($loan['days_remaining']); ?> days</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="POST" style="display: inline;">
                            <input type="hidden" name="loan_id" value="<?php echo $loan['loan_id']; ?>">
                            <input type="hidden" name="book_id" value="<?php echo $loan['book_id']; ?>">
                            <button type="submit" name="return_book" class="btn btn-sm btn-success">Return</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<!-- Edit Book Modal -->
<div id="editModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal('editModal')">&times;</span>
        <h2>Edit Book</h2>
        <form method="POST">
            <input type="hidden" id="edit_book_id" name="book_id">
            <div class="form-group">
                <label class="form-label" for="edit_title">Title</label>
                <input type="text" class="form-control" id="edit_title" name="title" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_author">Author</label>
                <input type="text" class="form-control" id="edit_author" name="author" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_isbn">ISBN</label>
                <input type="text" class="form-control" id="edit_isbn" name="isbn" required>
            </div>
            <div class="form-group">
                <label class="form-label" for="edit_published_year">Published Year</label>
                <input type="number" class="form-control" id="edit_published_year" name="published_year" min="1800" max="<?php echo date('Y'); ?>" required>
            </div>
            <button type="submit" name="update_book" class="btn">Update Book</button>
        </form>
    </div>
</div>

<!-- Loan Book Modal -->
<div id="loanModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close" onclick="closeModal('loanModal')">&times;</span>
        <h2>Loan Book</h2>
        <form method="POST">
            <input type="hidden" id="loan_book_id" name="book_id">
            <div class="form-group">
                <label class="form-label" for="pupil_id">Pupil</label>
                <div class="select-wrapper">
                    <select class="form-control" id="pupil_id" name="pupil_id" required>
                        <option value="">Select Pupil</option>
                        <?php 
                        $pupils->data_seek(0); // Reset pointer
                        while ($pupil = $pupils->fetch_assoc()): ?>
                            <option value="<?php echo $pupil['pupil_id']; ?>"><?php echo htmlspecialchars($pupil['name']); ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
            </div>
            <button type="submit" name="loan_book" class="btn">Loan Book</button>
        </form>
    </div>
</div>

<!-- Add the following CSS to style the modals -->
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

<!-- Add the following JavaScript to control the modals -->
<script>
    function openEditModal(id, title, author, isbn, publishedYear) {
        // Populate the modal fields with the book's data
        document.getElementById('edit_book_id').value = id;
        document.getElementById('edit_title').value = title;
        document.getElementById('edit_author').value = author;
        document.getElementById('edit_isbn').value = isbn;
        document.getElementById('edit_published_year').value = publishedYear;

        // Show the modal
        document.getElementById('editModal').style.display = 'block';
    }

    function openLoanModal(bookId) {
        // Populate the modal fields with the book's ID
        document.getElementById('loan_book_id').value = bookId;

        // Show the modal
        document.getElementById('loanModal').style.display = 'block';
    }

    function closeModal(modalId) {
        // Hide the modal
        document.getElementById(modalId).style.display = 'none';
    }

    // Close the modal when clicking outside of it
    window.onclick = function(event) {
        const editModal = document.getElementById('editModal');
        const loanModal = document.getElementById('loanModal');
        if (event.target == editModal) {
            editModal.style.display = 'none';
        }
        if (event.target == loanModal) {
            loanModal.style.display = 'none';
        }
    };
</script>

<?php include 'footer.php'; ?>
