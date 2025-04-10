<?php
require_once 'config.php';

// Check if user is logged in
// If the session variable 'user' is not set, redirect to the login page
if (!isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}

// Set the page title for the dashboard
$page_title = "Dashboard";

// Fetch counts for various entities to display on the dashboard
$classes_count = $conn->query("SELECT COUNT(*) FROM classes")->fetch_row()[0];
$pupils_count = $conn->query("SELECT COUNT(*) FROM pupils")->fetch_row()[0];
$teachers_count = $conn->query("SELECT COUNT(*) FROM teachers")->fetch_row()[0];
$parents_count = $conn->query("SELECT COUNT(*) FROM parents")->fetch_row()[0];
$tas_count = $conn->query("SELECT COUNT(*) FROM teaching_assistants")->fetch_row()[0];
$books_count = $conn->query("SELECT COUNT(*) FROM library_books")->fetch_row()[0];

// Fetch recent activities for pupils and teachers to display in the dashboard
$recent_pupils = $conn->query("SELECT pupil_id, first_name, last_name FROM pupils ORDER BY pupil_id DESC LIMIT 5");
$recent_teachers = $conn->query("SELECT teacher_id, first_name, last_name FROM teachers ORDER BY teacher_id DESC LIMIT 5");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - UA92 School</title>
    <!-- Include Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        /* Define CSS variables for consistent colors throughout the page */
        :root {
            --primary: #3498db;
            --secondary: #2ecc71;
            --danger: #e74c3c;
            --warning: #f39c12;
            --dark: #2c3e50;
            --light: #ecf0f1;
        }
        
        /* General body styling */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f5f7fa;
            color: #333;
        }
        
        /* Header styling */
        .header {
            background-color: var(--dark);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        /* Container for the main content */
        .container {
            max-width: 1400px;
            margin: 2rem auto;
            padding: 0 2rem;
        }
        
        /* Section title styling */
        .section-title {
            color: var(--dark);
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid var(--primary);
        }
        
        /* Grid layout for the stats section */
        .stats-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        /* Individual stat card styling */
        .stat-card {
            background: white;
            border-radius: 8px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        /* Add hover effect to stat cards */
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Label and number styling for stats */
        .stat-label {
            font-size: 1rem;
            color: #7f8c8d;
            margin-bottom: 0.5rem;
        }
        
        .stat-number {
            font-size: 2rem;
            font-weight: bold;
            color: var(--dark);
            margin-bottom: 1rem;
        }
        
        /* Button styling */
        .btn {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.3s;
        }
        
        .btn-sm {
            font-size: 0.9rem;
            padding: 0.4rem 0.8rem;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
        }
        
        /* Grid layout for the dashboard cards */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        /* Card styling */
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }
        
        /* Add hover effect to cards */
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1);
        }
        
        /* Card header styling */
        .card-header {
            background-color: var(--primary);
            color: white;
            padding: 0.75rem 1rem;
            font-weight: bold;
        }
        
        /* Card body styling */
        .card-body {
            padding: 1rem;
        }
        
        /* List styling inside cards */
        .card-body ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }
        
        .card-body ul li {
            padding: 0.75rem;
            border-bottom: 1px solid #eee;
            display: flex;
            align-items: center;
        }
        
        .card-body ul li:last-child {
            border-bottom: none;
        }
        
        .card-body ul li i {
            margin-right: 0.75rem;
            color: var(--primary);
        }
        
        /* Chart container styling */
        .chart-container {
            width: 100%;
            height: 500px;
            background: white;
            border-radius: 8px;
            padding: 1rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 2rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>UA92 School</h1>
    </div>
    
    <div class="container">
        <h1 class="section-title">Dashboard Overview</h1>
        
        <!-- Display stats for various entities -->
        <div class="stats-container">
            <div class="stat-card">
                <div class="stat-label">Classes</div>
                <div class="stat-number"><?php echo $classes_count; ?></div>
                <a href="classes.php" class="btn btn-primary btn-sm">View Classes</a>
            </div>
            <div class="stat-card">
                <div class="stat-label">Pupils</div>
                <div class="stat-number"><?php echo $pupils_count; ?></div>
                <a href="pupils.php" class="btn btn-primary btn-sm">View Pupils</a>
            </div>
            <div class="stat-card">
                <div class="stat-label">Teachers</div>
                <div class="stat-number"><?php echo $teachers_count; ?></div>
                <a href="teachers.php" class="btn btn-primary btn-sm">View Teachers</a>
            </div>
            <div class="stat-card">
                <div class="stat-label">Parents</div>
                <div class="stat-number"><?php echo $parents_count; ?></div>
                <a href="parents.php" class="btn btn-primary btn-sm">View Parents</a>
            </div>
            <div class="stat-card">
                <div class="stat-label">Teaching Assistants</div>
                <div class="stat-number"><?php echo $tas_count; ?></div>
                <a href="teaching_assistant.php" class="btn btn-primary btn-sm">View TAs</a>
            </div>
            <div class="stat-card">
                <div class="stat-label">Library Books</div>
                <div class="stat-number"><?php echo $books_count; ?></div>
                <a href="library.php" class="btn btn-primary btn-sm">View Books</a>
            </div>
        </div>
        
        <!-- Display recent activities -->
        <div class="dashboard-grid">
            <!-- Recent Pupils Card -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-users"></i> Recent Pupils
                </div>
                <div class="card-body">
                    <ul>
                        <?php while($pupil = $recent_pupils->fetch_assoc()): ?>
                        <li>
                            <i class="fas fa-user-graduate"></i>
                            <?php echo htmlspecialchars($pupil['first_name'] . ' ' . $pupil['last_name']); ?>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
            
            <!-- Recent Teachers Card -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-chalkboard-teacher"></i> Recent Teachers
                </div>
                <div class="card-body">
                    <ul>
                        <?php while($teacher = $recent_teachers->fetch_assoc()): ?>
                        <li>
                            <i class="fas fa-user-tie"></i>
                            <?php echo htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']); ?>
                        </li>
                        <?php endwhile; ?>
                    </ul>
                </div>
            </div>
            
            <!-- Upcoming Events Card -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-calendar-alt"></i> Upcoming Events
                </div>
                <div class="card-body">
                    <ul>
                        <li><i class="fas fa-running"></i> Sports Day - 15/04/2025</li>
                        <li><i class="fas fa-flask"></i> Science Fair - 20/04/2025</li>
                        <li><i class="fas fa-handshake"></i> Parent-Teacher Meeting - 25/04/2025</li>
                        <li><i class="fas fa-palette"></i> Art Exhibition - 30/04/2025</li>
                        <li><i class="fas fa-graduation-cap"></i> Annual Day - 05/05/2025</li>
                    </ul>
                </div>
            </div>
            
            <!-- Notices Card -->
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-bullhorn"></i> Notices
                </div>
                <div class="card-body">
                    <ul>
                        <li><i class="fas fa-info-circle"></i> School closed on 10/04/2025 for Good Friday</li>
                        <li><i class="fas fa-book"></i> New library books added</li>
                        <li><i class="fas fa-clipboard-list"></i> Exam schedules released on 18/04/2025</li>
                        <li><i class="fas fa-tshirt"></i> Uniform sale ongoing until 20/04/2025</li>
                        <li><i class="fas fa-project-diagram"></i> Submit science fair projects by 18/04/2025</li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Chart Section -->
        <div class="chart-container">
            <canvas id="performanceChart"></canvas>
        </div>

        <!-- Include Chart.js library -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Chart configuration
            const chartConfig = {
                type: 'bar', // Bar chart type
                data: {
                    labels: ['Classes', 'Pupils', 'Teachers', 'Parents', 'TAs', 'Books'], // Labels for the x-axis
                    datasets: [{
                        label: 'Counts', // Dataset label
                        data: [
                            <?php echo $classes_count; ?>,
                            <?php echo $pupils_count; ?>,
                            <?php echo $teachers_count; ?>,
                            <?php echo $parents_count; ?>,
                            <?php echo $tas_count; ?>,
                            <?php echo $books_count; ?>
                        ],
                        backgroundColor: [ // Colors for each bar
                            'rgba(52, 152, 219, 0.7)',
                            'rgba(46, 204, 113, 0.7)',
                            'rgba(231, 76, 60, 0.7)',
                            'rgba(241, 196, 15, 0.7)',
                            'rgba(155, 89, 182, 0.7)',
                            'rgba(52, 73, 94, 0.7)'
                        ],
                        borderColor: [ // Border colors for each bar
                            'rgba(52, 152, 219, 1)',
                            'rgba(46, 204, 113, 1)',
                            'rgba(231, 76, 60, 1)',
                            'rgba(241, 196, 15, 1)',
                            'rgba(155, 89, 182, 1)',
                            'rgba(52, 73, 94, 1)'
                        ],
                        borderWidth: 1 // Border width for each bar
                    }]
                },
                options: {
                    responsive: true, // Make the chart responsive
                    maintainAspectRatio: false, // Allow the chart to resize dynamically
                    plugins: {
                        legend: {
                            display: false // Hide the legend
                        },
                        title: {
                            display: true, // Display the chart title
                            text: 'System Statistics', // Chart title text
                            font: {
                                size: 18 // Font size for the title
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true, // Start the y-axis at zero
                            ticks: {
                                precision: 0 // Ensure whole numbers on the y-axis
                            }
                        }
                    },
                    animation: {
                        duration: 1500, // Animation duration in milliseconds
                        easing: 'easeOutBounce' // Easing effect for the animation
                    }
                }
            };

            // Initialize the chart but don't render it yet
            let performanceChart;

            // Intersection Observer to trigger the animation when the chart is in view
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        // Render the chart when it comes into view
                        if (!performanceChart) {
                            const ctx = document.getElementById('performanceChart').getContext('2d');
                            performanceChart = new Chart(ctx, chartConfig);
                        }
                    }
                });
            }, { threshold: 0.5 }); // Trigger when 50% of the chart is visible

            // Observe the chart container
            observer.observe(document.querySelector('.chart-container'));
        </script>
    </div>
</body>
</html>