<?php
session_start();
$message = "";
$alertType = "danger";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $entered_otp = $_POST['otp'];

    if (isset($_SESSION['otp']) && $entered_otp == $_SESSION['otp']) {
        $_SESSION['verified'] = true;
        header("Location: reset_password.php");
        exit;
    } else {
        $message = "Invalid OTP. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Verify OTP - ECEverse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto Mono', monospace;
            background: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .mask {
            position: absolute;
            inset: 0;
            background: rgba(246, 246, 247, 0.1);
            z-index: 0;
        }
        .otp-card {
            position: relative;
            z-index: 10;
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(0, 119, 204, 0.5);
            width: 100%;
            max-width: 400px;
            padding: 2.5rem 2rem;
            animation: fadeInUp 0.8s ease forwards;
        }
        h3 {
            color: #0077cc;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }
        .btn-success {
            background-color: #28a745;
            border: none;
        }
        .btn-success:hover {
            background-color: #218838;
        }
        .form-outline {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .form-outline input {
            border: 2px solid #0077cc;
            border-radius: 8px;
            padding: 1rem 0.75rem 0.25rem 0.75rem;
            font-size: 1.1rem;
            width: 100%;
            outline: none;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            background-color: #f9faff;
        }
        .form-outline input:focus {
            border-color: #005fa3;
            box-shadow: 0 0 10px rgba(0, 95, 163, 0.6);
            background-color: #fff;
        }
        .form-label {
            position: absolute;
            top: 1rem;
            left: 0.75rem;
            color: #0077cc;
            font-size: 1rem;
            pointer-events: none;
            transition: all 0.3s ease;
            background-color: #f9faff;
            padding: 0 5px;
            border-radius: 3px;
        }
        .form-outline input:focus + .form-label,
        .form-outline input:not(:placeholder-shown) + .form-label {
            top: -0.5rem;
            font-size: 0.85rem;
            color: #005fa3;
            font-weight: 600;
        }
        .alert {
            text-align: center;
            margin-bottom: 1rem;
        }
        .alert-danger {
            background-color: #fdecea;
            color: #cc0000;
            border: 1px solid #cc0000;
        }
    </style>
</head>
<body>
<div class="mask"></div>
<div class="otp-card">
    <?php if (!empty($message)): ?>
        <div class="alert alert-<?php echo $alertType; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <h3>Verify OTP</h3>

    <form method="post">
        <div class="form-outline">
            <input type="text" name="otp" id="otp" required placeholder=" ">
            <label for="otp" class="form-label">Enter OTP</label>
        </div>
        <button type="submit" class="btn btn-success w-100">Verify</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
