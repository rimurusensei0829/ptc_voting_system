<?php
session_start();
require 'config/db.php';

if (!isset($_SESSION['admin_id'])) {
    exit("Access denied");
}

$message = "";

// Handle add candidate
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_candidate'])) {
    $name = trim($_POST['name']);
    $position = trim($_POST['position']);

    $photoName = null;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoTmp = $_FILES['photo']['tmp_name'];
        $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
        move_uploaded_file($photoTmp, 'uploads/' . $photoName);
    }

    if (!empty($name) && !empty($position)) {
        $stmt = $pdo->prepare("INSERT INTO candidates (name, position, photo) VALUES (?, ?, ?)");
        if ($stmt->execute([$name, $position, $photoName])) {
            $message = "Candidate added.";
        } else {
            $message = "Failed to add candidate.";
        }
    } else {
        $message = "Name and position are required.";
    }
}

// Handle delete
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $pdo->prepare("DELETE FROM candidates WHERE id = ?")->execute([$id]);
    $message = "Candidate deleted.";
}

// Handle update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_candidate'])) {
    $id = $_POST['id'];
    $name = trim($_POST['name']);
    $position = trim($_POST['position']);

    $query = "UPDATE candidates SET name = ?, position = ?";
    $params = [$name, $position];

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $photoName = uniqid() . '_' . basename($_FILES['photo']['name']);
        move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/' . $photoName);
        $query .= ", photo = ?";
        $params[] = $photoName;
    }

    $query .= " WHERE id = ?";
    $params[] = $id;

    $stmt = $pdo->prepare($query);
    if ($stmt->execute($params)) {
        $message = "Candidate updated.";
    } else {
        $message = "Failed to update candidate.";
    }
}

$candidates = $pdo->query("SELECT * FROM candidates ORDER BY position ASC, name ASC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Candidates</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <h3 class="text-center mb-4">Manage Candidates</h3>

    <?php if ($message): ?>
        <div class="alert alert-info"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <!-- Add Candidate Form -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="card-title">Add Candidate</h5>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-3">
                    <label class="form-label">Candidate Name</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Position</label>
                    <select name="position" class="form-select" required>
                        <option value="">Select Position</option>
                        <option value="President">President</option>
                        <option value="Vice President">Vice President</option>
                        <option value="Secretary">Secretary</option>
                        <option value="Treasurer">Treasurer</option>
                        <option value="Auditor">Auditor</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Photo</label>
                    <input type="file" name="photo" accept="image/*" class="form-control">
                </div>
                <button type="submit" name="add_candidate" class="btn btn-primary">Add Candidate</button>
            </form>
        </div>
    </div>

    <!-- Candidates Table -->
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Candidate List</h5>
            <?php if ($candidates): ?>
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Votes</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($candidates as $c): ?>
                        <tr>
                            <td>
                                <?php if ($c['photo']): ?>
                                    <img src="uploads/<?= $c['photo'] ?>" width="60" height="60" class="rounded-circle">
                                <?php else: ?>
                                    <span class="text-muted">No photo</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($c['name']) ?></td>
                            <td><?= htmlspecialchars($c['position']) ?></td>
                            <td><?= $c['votes'] ?></td>
                            <td>
                                <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#editModal<?= $c['id'] ?>">Edit</button>
                                <a href="?delete=<?= $c['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this candidate?')">Delete</a>
                            </td>
                        </tr>

                        <!-- Edit Modal -->
                        <div class="modal fade" id="editModal<?= $c['id'] ?>" tabindex="-1">
                            <div class="modal-dialog">
                                <form method="POST" enctype="multipart/form-data" class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Candidate</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="id" value="<?= $c['id'] ?>">
                                        <div class="mb-3">
                                            <label class="form-label">Name</label>
                                            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($c['name']) ?>" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Position</label>
                                            <select name="position" class="form-select" required>
                                                <option value="">Select Position</option>
                                                <option value="President" <?= $c['position'] == 'President' ? 'selected' : '' ?>>President</option>
                                                <option value="Vice President" <?= $c['position'] == 'Vice President' ? 'selected' : '' ?>>Vice President</option>
                                                <option value="Secretary" <?= $c['position'] == 'Secretary' ? 'selected' : '' ?>>Secretary</option>
                                                <option value="Treasurer" <?= $c['position'] == 'Treasurer' ? 'selected' : '' ?>>Treasurer</option>
                                                <option value="Auditor" <?= $c['position'] == 'Auditor' ? 'selected' : '' ?>>Auditor</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Photo (leave blank to keep current)</label>
                                            <input type="file" name="photo" accept="image/*" class="form-control">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" name="update_candidate" class="btn btn-primary">Save Changes</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="text-muted">No candidates added yet.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="text-center mt-3">
        <a href="admin_dashboard.php" class="btn btn-outline-secondary">Back to Dashboard</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
