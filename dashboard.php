<?php
require 'config.php';
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Fetch user data from database
$stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
$stmt->execute([$_SESSION['user']]);
$user = $stmt->fetch();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $age = intval($_POST['age']);
    $phone = trim($_POST['phone']);

    // Handle picture update
    $pictureName = $user['picture'];
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $ext = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
        $pictureName = uniqid() . '.' . $ext;
        move_uploaded_file($_FILES['picture']['tmp_name'], "uploads/$pictureName");
    }

    $updateStmt = $pdo->prepare("UPDATE users SET name = ?, age = ?, phone = ?, picture = ? WHERE username = ?");
    $updateStmt->execute([$name, $age, $phone, $pictureName, $_SESSION['user']]);

    // Refresh user data
    $stmt->execute([$_SESSION['user']]);
    $user = $stmt->fetch();
    $message = "<div class='alert alert-success'>Profile updated successfully.</div>";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height: 100vh;">

    <div class="card shadow p-4 text-center" style="max-width: 500px; width: 100%;">
        <h2 class="mb-4">Welcome, <?= htmlspecialchars($user['name']) ?>!</h2>

        <?php if (isset($message)) echo $message; ?>

        <!-- Profile Picture -->
        <?php 
        $profilePic = !empty($user['picture']) && file_exists(__DIR__ . '/uploads/' . $user['picture']) 
            ? 'uploads/' . htmlspecialchars($user['picture']) 
            : 'uploads/placeholder.jpg';
        ?>

        <div class="d-flex justify-content-center mb-3">
            <img src="<?= $profilePic ?>" 
                 alt="Profile Picture"
                 class="rounded-circle shadow"
                 style="width: 150px; height: 150px; object-fit: cover;">
        </div>

        <!-- Edit Form -->
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3 text-start">
                <label class="form-label"><strong>Name</strong></label>
                <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user['name']) ?>" required>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label"><strong>Age</strong></label>
                <input type="number" name="age" class="form-control" value="<?= htmlspecialchars($user['age']) ?>" required>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label"><strong>Phone</strong></label>
                <input type="text" name="phone" class="form-control" value="<?= htmlspecialchars($user['phone']) ?>" required>
            </div>
            <div class="mb-3 text-start">
                <label class="form-label"><strong>Profile Picture</strong></label>
                <input type="file" name="picture" class="form-control" accept="image/*">
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </form>

        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
    </div>

</body>
</html>
