<?php
require 'config.php';
session_start();

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$_POST['username']]);
    $user = $stmt->fetch();

    if ($user && password_verify($_POST['password'], $user['password'])) {
        $_SESSION['user'] = $user['username'];
        header("Location: dashboard.php");
        exit;
    } else {
        $message = "<div class='alert alert-danger'>Invalid username or password.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body, html {
            height: 100%;
        }
    </style>
</head>
<body class="bg-light d-flex align-items-center justify-content-center">

<div class="card shadow p-4" style="width: 100%; max-width: 400px;">
    <h2 class="mb-3 text-center">Login</h2>
    <?= $message ?>
    <form method="post">
        <div class="mb-3">
            <label class="form-label">Username</label>
            <input name="username" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input name="password" type="password" class="form-control" required>
        </div>
        <div class="d-grid">
            <button type="submit" class="btn btn-success">Login</button>
        </div>
        <div class="text-center mt-3">
            <a href="register.php" class="btn btn-link">Register</a>
        </div>
    </form>
</div>

</body>
</html>
