<?php
session_start();
include 'partials/_dbconnect.php';

if (!isset($_SESSION['verified']) || $_SESSION['verified'] !== true) {
    header("Location: forgot_password.php");
    exit;
}

$message = "";
$alertType = "danger";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    if ($new_password === $confirm_password) {
        $email = $_SESSION['email'];
        $sql = "UPDATE users SET password = '$new_password' WHERE youremail = '$email'";
        if (mysqli_query($conn, $sql)) {
            $alertType = "success";
            $message = "Password updated successfully!";
            session_destroy();
        } else {
            $message = "Error updating password: " . mysqli_error($conn);
        }
    } else {
        $message = "Passwords do not match!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - ECEverse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', sans-serif;
            background: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .mask {
            position: absolute;
            inset: 0;
            background-color: rgba(250, 244, 244, 0.3);
            z-index: 0;
        }
        .card {
            z-index: 1;
            background-color: #f8f9fa;
            border-radius: 1rem;
            box-shadow: 0 0 40px rgba(0, 0, 0, 0.15);
            padding: 2rem;
            width: 100%;
            max-width: 400px;
            animation: slideUp 0.6s ease-out;
        }
        @keyframes slideUp {
            from { transform: translateY(40px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .form-outline {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .form-outline input {
            border: none;
            border-bottom: 2px solid #555;
            background: transparent;
            width: 100%;
            padding: 10px 0;
            font-size: 1rem;
            outline: none;
        }
        .form-outline label {
            position: absolute;
            left: 0;
            top: 10px;
            font-size: 1rem;
            color: #444;
            transition: 0.3s ease;
        }
        .form-outline input:focus + label,
        .form-outline input:not(:placeholder-shown) + label {
            top: -10px;
            font-size: 0.8rem;
            font-weight: bold;
            color: #222;
        }
        .btn-primary {
            background-color: #343a40;
            border: none;
            font-weight: bold;
            padding: 0.6rem;
        }
        .btn-primary:hover {
            background-color: #1d2124;
        }
    </style>
</head>
<body>
<div class="mask"></div>
<div class="card">
    <h3 class="text-center mb-4">Reset Password</h3>

    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $alertType; ?> text-center">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <form method="post">
        <div class="form-outline">
            <input type="password" id="new_password" name="new_password" placeholder=" " required />
            <label for="new_password">New Password</label>
        </div>
        <div class="form-outline">
            <input type="password" id="confirm_password" name="confirm_password" placeholder=" " required />
            <label for="confirm_password">Confirm Password</label>
        </div>
        <button type="submit" class="btn btn-primary w-100">Change Password</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
