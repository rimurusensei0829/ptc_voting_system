<?php
session_start();
require 'config/db.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $student_id = trim($_POST['student_id']);
    $email = trim($_POST['email']);

    $stmt = $pdo->prepare("SELECT * FROM voters WHERE student_id = ? AND email = ?");
    $stmt->execute([$student_id, $email]);

    if ($voter = $stmt->fetch()) {
        $_SESSION['voter_id'] = $voter['id'];
        header("Location: voter.php");
        exit;
    } else {
        $error = "Invalid student ID or email.";
    }
}
?>

<!-- Simple Bootstrap Login Form -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Voter Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm mx-auto" style="max-width: 400px;">
        <div class="card-body">
            <h4 class="card-title text-center mb-3">Voter Login</h4>

            <?php if ($error): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="mb-3">
                    <label for="student_id" class="form-label">Student ID</label>
                    <input type="text" class="form-control" id="student_id" name="student_id" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Student Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>
        </div>
    </div>
</div>
</body>
</html>
