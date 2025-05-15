<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['admin_id'])) exit("Not logged in");

$message = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = !empty($_POST['password']) ? $_POST['password'] : null;

    if (empty($username)) {
        $message = "Username cannot be empty.";
    } else {
        if ($password) {
            // Using plain text password as requested (not recommended for production)
            $query = "UPDATE admins SET username = ?, password = ? WHERE id = ?";
            $params = [$username, $password, $_SESSION['admin_id']];
        } else {
            $query = "UPDATE admins SET username = ? WHERE id = ?";
            $params = [$username, $_SESSION['admin_id']];
        }

        $stmt = $pdo->prepare($query);
        if ($stmt->execute($params)) {
            $message = "Profile updated successfully.";
        } else {
            $message = "Error updating profile.";
        }
    }
}

// Fetch admin data
$stmt = $pdo->prepare("SELECT * FROM admins WHERE id = ?");
$stmt->execute([$_SESSION['admin_id']]);
$admin = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card mx-auto shadow-sm" style="max-width: 500px;">
            <div class="card-body">
                <h4 class="card-title mb-4 text-center">Admin Profile Settings</h4>

                <?php if (!empty($message)): ?>
                    <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
                <?php endif; ?>

                <form method="POST">
                    <div class="mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($admin['username']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">New Password <small class="text-muted">(Leave blank to keep current password)</small></label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Profile</button>
                </form>

                <div class="mt-3 text-center">
                    <a href="admin_dashboard.php" class="btn btn-link">Back to Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
