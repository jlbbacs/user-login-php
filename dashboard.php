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

        <!-- Debugging: Check Image Path -->
        <p>Image Path: <?= __DIR__ . '/uploads/' . htmlspecialchars($user['picture']) ?></p>

        <!-- Profile Picture -->
        <?php 
        $profilePic = !empty($user['picture']) && file_exists(__DIR__ . '/uploads/' . $user['picture']) 
            ? 'uploads/' . htmlspecialchars($user['picture']) 
            : 'uploads/placeholder.jpg'; // Default placeholder image
        ?>
       
        <div class="d-flex justify-content-center mb-3">
            <img src="<?= $profilePic ?>" 
                 alt="Profile Picture"
                 class="rounded-circle shadow"
                 style="width: 150px; height: 150px; object-fit: cover;">
        </div>

        <!-- User Info -->
        <ul class="list-group text-start mb-4">
            <li class="list-group-item"><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></li>
            <li class="list-group-item"><strong>Age:</strong> <?= htmlspecialchars($user['age']) ?></li>
            <li class="list-group-item"><strong>Phone:</strong> <?= htmlspecialchars($user['phone']) ?></li>
        </ul>

        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>

</body>
</html>
