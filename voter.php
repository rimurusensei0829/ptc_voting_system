<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['voter_id'])) {
    header("Location: voter_login.php");
    exit();
}

// Group candidates by position
$candidates_raw = $pdo->query("SELECT * FROM candidates")->fetchAll(PDO::FETCH_ASSOC);
$candidates = [];

foreach ($candidates_raw as $candidate) {
    $candidates[$candidate['position']][] = $candidate;
}

// Define minimum required positions
$required_positions = ['President', 'Vice President', 'Secretary', 'Treasurer', 'Auditor'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vote Now</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .candidate-card {
            transition: transform 0.2s;
            cursor: pointer;
            border-radius: 10px;
        }
        .candidate-card:hover {
            transform: scale(1.02);
        }
        .candidate-photo {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
        }
        .radio-label {
            display: block;
            width: 100%;
        }
        input[type="radio"] {
            margin-right: 10px;
        }
        .position-section {
            margin-top: 40px;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="text-center mb-4">
            <h2 class="mb-3">Vote for Your Candidates</h2>
            <p class="text-muted">You must vote for: President, Vice President, Secretary, Treasurer, and Auditor.</p>
        </div>

        <form method="POST" action="submit_vote.php">
            <?php foreach ($required_positions as $position): ?>
                <?php if (isset($candidates[$position])): ?>
                    <div class="position-section">
                        <h4><?= htmlspecialchars($position) ?></h4>
                        <div class="row g-4">
                            <?php foreach ($candidates[$position] as $candidate): ?>
                                <div class="col-md-4">
                                    <label class="radio-label">
                                        <input type="radio" name="<?= strtolower(str_replace(' ', '_', $position)) ?>" value="<?= $candidate['id'] ?>" required>
                                        <div class="card candidate-card shadow-sm">
                                            <img src="uploads/<?= htmlspecialchars($candidate['photo'] ?: 'default.jpeg') ?>" class="candidate-photo" alt="<?= htmlspecialchars($candidate['name']) ?>">
                                            <div class="card-body text-center">
                                                <h5 class="card-title"><?= htmlspecialchars($candidate['name']) ?></h5>
                                                <p class="text-muted"><?= htmlspecialchars($candidate['position']) ?></p>
                                            </div>
                                        </div>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="alert alert-danger mt-4">
                        No candidates available for <?= htmlspecialchars($position) ?>.
                    </div>
                <?php endif; ?>
            <?php endforeach; ?>

            <div class="text-center mt-5">
                <button type="submit" class="btn btn-success btn-lg">Submit Vote</button>
            </div>
        </form>
    </div>
</body>
</html>
