<?php
require 'config/db.php'; // Make sure this connects correctly

$stmt = $pdo->query("SELECT name, position, votes FROM candidates ORDER BY position, votes DESC");
$candidates = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Group by position
$grouped = [];

foreach ($candidates as $candidate) {
    $position = $candidate['position'];
    if (!isset($grouped[$position])) {
        $grouped[$position] = [];
    }
    $grouped[$position][] = $candidate;
}

echo json_encode($grouped);
?>
