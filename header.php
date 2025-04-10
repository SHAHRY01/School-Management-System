<!-- filepath: c:\xampp 1\htdocs\student_managment_system\header.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UA92 School - <?php echo $page_title; ?></title>
    <style>
        /* Header Styles */
        header {
            background-color: #2c3e50; /* Dark background color */
            color: white;
            padding: 1rem 2rem;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-container {
            display: flex;
            justify-content: space-between; /* Align logo to the left and nav menu to the right */
            align-items: center;
            max-width: 1200px;
            margin: 0 auto;
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem; /* Add spacing between buttons */
            margin: 0;
            padding: 0;
        }

        .nav-menu li {
            margin: 0;
        }

        .nav-menu a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
            font-weight: 500;
            padding: 0.5rem 1rem; /* Add padding inside buttons */
            border-radius: 4px; /* Rounded corners */
        }

        .nav-menu a:hover {
            color: #ecf0f1; /* Light color on hover */
            background-color: rgba(255, 255, 255, 0.1); /* Subtle hover background */
        }
    </style>
</head>
<body>
    <header>
        <div class="container header-container">
            <a href="index.php" class="logo">UA92 School</a>
            <nav>
                <ul class="nav-menu">
                    <li><a href="index.php">Dashboard</a></li>
                    <li><a href="classes.php">Classes</a></li>
                    <li><a href="pupils.php">Pupils</a></li>
                    <li><a href="parents.php">Parents</a></li>
                    <li><a href="teachers.php">Teachers</a></li>
                    <li><a href="teaching_assistant.php">Teaching Assistants</a></li>
                    <li><a href="library.php">Library</a></li>
                </ul>
            </nav>
        </div>
    </header>
</body>
</html>