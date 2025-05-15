<?php
session_start();
require 'config/db.php';

// Restrict access to admins
if (!isset($_SESSION['admin_id'])) {
    exit("Access denied");
}

$message = "";

// Handle voter creation
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id']);
    $email = trim($_POST['email']);

    // Check for duplicate entries
    $check = $pdo->prepare("SELECT * FROM voters WHERE student_id = ? OR email = ?");
    $check->execute([$student_id, $email]);
    
    if ($check->fetch()) {
        $message = "Voter with this Student ID or Email already exists.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO voters (student_id, email) VALUES (?, ?)");
        if ($stmt->execute([$student_id, $email])) {
            $message = "Voter account created successfully.";
        } else {
            $message = "Failed to create voter.";
        }
    }
}

// Fetch all voters
$voters = $pdo->query("SELECT * FROM voters ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Voters</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm mb-4">
        <div class="card-body">
            <h4 class="card-title text-center mb-3">Add New Voter</h4>

            <?php if ($message): ?>
                <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="student_id" class="form-label">Student ID</label>
                    <input type="text" class="form-control" id="student_id" name="student_id" required value="2021-4093">
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Student Email</label>
                    <input type="email" class="form-control" id="email" name="email" required value="ccquema@paterostechnologicalcollege.edu.ph">
                </div>
                <button type="submit" class="btn btn-primary w-100">Create Voter</button>
            </form>
        </div>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <h4 class="card-title mb-3 text-center">Voters List</h4>
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Student ID</th>
                        <th>Email</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($voters as $voter): ?>
                    <tr>
                        <td><?= $voter['id'] ?></td>
                        <td><?= htmlspecialchars($voter['student_id']) ?></td>
                        <td><?= htmlspecialchars($voter['email']) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <div class="text-center mt-3">
                <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
