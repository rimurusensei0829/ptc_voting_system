<?php
session_start();
require 'config/db.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vote Submission</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-body">

<?php
if (!isset($_SESSION['voter_id'])) {
    echo '<div class="alert alert-danger">Invalid access - voter not logged in.</div>';
    echo '<a href="voter_login.php" class="btn btn-secondary">Login</a>';
    exit("</div></div></div></body></html>");
}

$voter_id = $_SESSION['voter_id'];
$required_positions = ['president', 'vice_president', 'secretary', 'treasurer', 'auditor'];

foreach ($required_positions as $pos) 
    if (empty($_POST[$pos])) {
        echo '<div class="alert alert-danger">⚠️ You must vote for all required positions.</div>';
        echo '<a href="vote_form.php" class="btn btn-warning mt-2">Back to Voting Form</a>';
        exit("</div></div></div></body></html>");
}

try {
    $pdo->beginTransaction();

    foreach ($required_positions as $pos) {
        $candidate_id = $_POST[$pos];

        // Update candidate's vote count
        $stmt = $pdo->prepare("UPDATE candidates SET votes = votes + 1 WHERE id = ?");
        $stmt->execute([$candidate_id]);

        // Log vote
        $log = $pdo->prepare("INSERT INTO votes (voter_id, candidate_id, position) VALUES (?, ?, ?)");
        $log->execute([$voter_id, $candidate_id, ucfirst(str_replace('_', ' ', $pos))]);
    }

    // Mark voter as having voted
    $pdo->prepare("UPDATE voters SET has_voted = 1 WHERE id = ?")->execute([$voter_id]);

    $pdo->commit();

    // End session after vote
    unset($_SESSION['voter_id']);

    echo '<div class="alert alert-success">✅ Vote submitted successfully!</div>';
    echo '<a href="index.php" class="btn btn-success mt-3">Return to Home</a>';

} catch (Exception $e) {
    $pdo->rollBack();
    echo '<div class="alert alert-danger">❌ Error submitting vote: ' . htmlspecialchars($e->getMessage()) . '</div>';
    echo '<a href="vote_form.php" class="btn btn-danger mt-3">Try Again</a>';
}
?>

        </div>
    </div>
</div>
</body>
</html>
