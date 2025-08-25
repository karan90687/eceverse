<?php
session_start();
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

$message = "";
$alertType = "danger";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'partials/_dbconnect.php';

    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $sql = "SELECT * FROM users WHERE youremail = '$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) > 0) {
        $otp = rand(100000, 999999);
        $_SESSION['otp'] = $otp;
        $_SESSION['email'] = $email;

        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'srujanjaloji3557@gmail.com';
            $mail->Password = 'yaez djjc nuaz tvtw'; // Make sure to keep this secure!
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->SMTPOptions = [
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true
                ]
            ];

            $mail->setFrom('srujanjaloji3557@gmail.com', 'eceverse');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Your OTP Code from ECEverse';
            $mail->Body = "Your OTP code is <b>$otp</b>. Please enter this to reset your password.";

            $mail->send();
            header("Location: verify_otp.php");
            exit;

        } catch (Exception $e) {
            $message = "Could not send OTP. Mailer Error: {$mail->ErrorInfo}";
        }
    } else {
        $message = "Email not found!";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Forgot Password - ECEverse</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Roboto+Mono&display=swap');

        body {
            font-family: 'Roboto Mono', monospace;
            background: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            overflow: hidden;
        }
        .mask {
            position: absolute;
            inset: 0;
            background: rgba(236, 241, 244, 0.1);
            z-index: 0;
        }
        .forgot-card {
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
        .btn-primary {
            background-color: #0077cc;
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #005fa3;
        }
        .alert-success {
            background-color: #d1f2eb;
            color: #0077cc;
            border: 1px solid #0077cc;
            text-align: center;
            margin-bottom: 1rem;
        }
        .alert-danger {
            background-color: #fdecea;
            color: #cc0000;
            border: 1px solid #cc0000;
            text-align: center;
            margin-bottom: 1rem;
        }

        @keyframes fadeInUp {
            0% {
                opacity: 0;
                transform: translateY(40px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Floating label + visible input box styles */
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

        /* Link styling */
        p a {
            color: #0077cc;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        p a:hover {
            color: #005fa3;
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="mask"></div>
<div class="forgot-card">
    <?php if(!empty($message)): ?>
        <div class="alert alert-<?php echo $alertType; ?>">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <h3>Forgot Password</h3>

    <form method="post" action="forgot_password.php">
        <div class="form-outline">
            <input type="email" name="email" id="email" required placeholder=" " autocomplete="email" />
            <label for="email" class="form-label">Enter your registered email</label>
        </div>

        <button type="submit" class="btn btn-primary w-100">Send OTP</button>
    </form>

    <p class="mt-3 text-center">
        <a href="login.php">Back to Login</a>
    </p>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
