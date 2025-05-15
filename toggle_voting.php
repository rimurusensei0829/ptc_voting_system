<?php
require 'config/db.php';

$message = "";

// Toggle voting if requested
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->query("UPDATE settings SET voting_enabled = NOT voting_enabled");
    $message = "Voting status toggled.";
}

// Fetch current voting status
$stmt = $pdo->query("SELECT voting_enabled FROM settings LIMIT 1");
$votingStatus = $stmt->fetchColumn();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Toggle Voting</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card mx-auto shadow" style="max-width: 500px;">
            <div class="card-body">
                <h4 class="card-title text-center mb-4">Toggle Voting Status</h4>

                <?php if (!empty($message)): ?>
                    <div class="alert alert-success text-center"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <p class="text-center fw-bold">
                    Current Voting Status:
                    <span class="text-<?= $votingStatus ? 'success' : 'danger' ?>">
                        <?= $votingStatus ? 'Enabled' : 'Disabled' ?>
                    </span>
                </p>

                <form method="POST" class="d-flex justify-content-center">
                    <button type="submit" class="btn btn-primary">
                        Toggle Voting
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="admin_dashboard.php" class="btn btn-link">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
