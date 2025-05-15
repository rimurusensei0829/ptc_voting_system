<?php
// Start session to check if the admin is logged in
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f0f2f5;
        }

        .navbar {
            background-color: #007bff;
        }

        .navbar .navbar-brand,
        .navbar .nav-link {
            color: white !important;
            font-weight: bold;
        }

        .navbar .nav-link:hover {
            color: #e2e6ea !important;
        }

        .dashboard-container {
            min-height: 90vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .dashboard-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 700px;
            width: 100%;
        }

        .dashboard-card h1 {
            font-size: 2.2rem;
            margin-bottom: 30px;
        }

        .links a {
            display: block;
            margin: 10px auto;
            padding: 12px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-size: 16px;
            width: 70%;
            transition: background-color 0.2s ease-in-out;
        }

        .links a:hover {
            background-color: #218838;
        }

        .btn-reset {
            margin-top: 30px;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Admin Panel</a>
        <div class="ms-auto">
            <a class="nav-link" href="logout.php">Logout</a>
        </div>
    </div>
</nav>

<!-- Dashboard Content -->
<div class="dashboard-container">
    <div class="dashboard-card">
        <h1>Welcome to the Admin Dashboard!</h1>

        <div class="links">
            <a href="manage_candidates.php">Manage Candidates</a>
            <a href="manage_voters.php">Manage Voters</a>
            <a href="view_result.php">View Results</a>
            <a href="toggle_voting.php">Toggle Voting</a>
            <a href="admin_profile.php">Profile Settings</a>
        </div>

        <!-- Reset votes form -->
        <form method="POST" action="reset_votes.php" onsubmit="return confirm('Are you sure you want to reset all votes? This cannot be undone.');">
            <button type="submit" class="btn btn-danger btn-reset">🔄 Reset All Votes</button>
        </form>
    </div>
</div>

</body>
</html>
