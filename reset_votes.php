<?php
session_start();
require 'config/db.php';

// Ensure only admin can access this
if (!isset($_SESSION['admin_id'])) {
    die("Access denied. Admins only.");
}

try {
    // Start transaction
    $pdo->beginTransaction();

    // Reset voter statuses
    $pdo->exec("UPDATE voters SET has_voted = 0");

    // Reset all vote counts
    $pdo->exec("UPDATE candidates SET votes = 0");

    // Optional: clear vote logs
    $pdo->exec("DELETE FROM votes");

    $pdo->commit();

    echo "<script>
        alert('✅ All votes have been reset successfully.');
        window.location.href = 'admin_dashboard.php';
    </script>";
} catch (Exception $e) {
    $pdo->rollBack();
    echo "❌ Failed to reset votes: " . $e->getMessage();
}
?>
