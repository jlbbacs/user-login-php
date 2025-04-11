<?php
require 'config.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = trim($_POST['name']);
    $age = intval($_POST['age']);
    $phone = trim($_POST['phone']);
    
    // Handle image upload
    $pictureName = ''; // Initialize pictureName variable

    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        // Generate a unique file name using uniqid() and the file's extension
        $ext = pathinfo($_FILES['picture']['name'], PATHINFO_EXTENSION);
        $pictureName = uniqid() . '.' . $ext;

        // Move the uploaded file to the uploads directory
        move_uploaded_file($_FILES['picture']['tmp_name'], "uploads/$pictureName");
    }

    // Insert user details into the database, including the image name
    $stmt = $pdo->prepare("INSERT INTO users (username, password, name, age, phone, picture) VALUES (?, ?, ?, ?, ?, ?)");
    
    try {
        $stmt->execute([$username, $password, $name, $age, $phone, $pictureName]);
        $message = "<div class='alert alert-success'>Registration successful. <a href='login.php'>Login here</a></div>";
    } catch (Exception $e) {
        $message = "<div class='alert alert-danger'>Username already taken.</div>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex align-items-center justify-content-center" style="height:100vh;">
    <div class="card shadow p-4" style="width: 100%; max-width: 500px;">
        <h2 class="mb-3 text-center">Register</h2>
        <?= $message ?>
        <form method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input name="password" type="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Full Name</label>
                <input name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Age</label>
                <input name="age" type="number" class="form-control" required min="1">
            </div>
            <div class="mb-3">
                <label class="form-label">Phone</label>
                <input name="phone" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Profile Picture</label>
                <input name="picture" type="file" accept="image/*" class="form-control">
            </div>
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Register</button>
            </div>
            <div class="text-center mt-3">
                <a href="login.php" class="btn btn-link">Already have an account?</a>
            </div>
        </form>
    </div>
</body>
</html>
