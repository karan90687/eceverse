<?php
session_start();
$message = "";
$alertType = "danger";

// Include DB connection early
include 'partials/_dbconnect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['id_token'])) {
        require_once 'vendor/autoload.php';

        $client = new Google_Client(['client_id' => '816487949923-7k96vbp7r23cequahp035smoie5agfb3.apps.googleusercontent.com']);
        $payload = $client->verifyIdToken($_POST['id_token']);

        if ($payload) {
            $username = explode('@', $payload['email'])[0];
            $email = $payload['email'];

            $username = mysqli_real_escape_string($conn, $username);
            $email = mysqli_real_escape_string($conn, $email);

            $existsSql = "SELECT * FROM users WHERE email = '$email'";
            $result = mysqli_query($conn, $existsSql);

            if ($result && mysqli_num_rows($result) > 0) {
                $userData = mysqli_fetch_assoc($result);
                $_SESSION['user_id'] = $userData['id'];
                $_SESSION['username'] = $userData['username'];
                $_SESSION['email'] = $userData['email'];
                $_SESSION['loggedin'] = true;
                header("Location: problems.php");
                exit;
            } else {
                $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '')";
                if (mysqli_query($conn, $sql)) {
                    $_SESSION['user_id'] = mysqli_insert_id($conn);
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                    $_SESSION['loggedin'] = true;
                    header("Location: problems.php");
                    exit;
                } else {
                    $message = "Google Sign-In error. Try again.";
                }
            }
        } else {
            $message = "Invalid Google ID token.";
        }
    } else {
        $username = mysqli_real_escape_string($conn, $_POST["yourname"]);
        $email = mysqli_real_escape_string($conn, $_POST["youremail"]);
        $password = $_POST["password"];
        $cpassword = $_POST["cpassword"];

        if ($password === $cpassword) {
            $existsSql = "SELECT * FROM users WHERE username = '$username' OR email = '$email'";
            $result = mysqli_query($conn, $existsSql);

            if ($result && mysqli_num_rows($result) > 0) {
                $message = "Username or email already exists!";
            } else {
                // $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $sql = "INSERT INTO users (username, email, password) VALUES ('$username', '$email', '$password')";
                if (mysqli_query($conn, $sql)) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = mysqli_insert_id($conn);
                    $_SESSION['username'] = $username;
                    $_SESSION['email'] = $email;
                    $_SESSION['loggedin'] = true;
                    header("Location: problems.php");
                    exit;
                } else {
                    $message = "Error creating account: " . mysqli_error($conn);
                }
            }
        } else {
            $message = "Passwords do not match!";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>ECEverse - Create Account</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://accounts.google.com/gsi/client" async defer></script>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

  <style>
    :root {
      --primary-color: #3a7bd5;
      --secondary-color: #00d2ff;
      --accent-color: #5c6bc0;
      --light-gray: #f5f7fa;
      --medium-gray: #e1e5ee;
      --dark-gray: #6b7280;
    }

    body {
      font-family: 'Inter', sans-serif;
      background-color: var(--light-gray);
      height: 100vh;
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
      position: relative;
    }

    .circuit-lines {
      position: absolute;
      width: 100%;
      height: 100%;
      opacity: 0.05;
      z-index: 0;
    }

    .circuit-line {
      position: absolute;
      background-color: var(--primary-color);
      animation: circuitFlow 8s linear infinite;
    }

    @keyframes circuitFlow {
      0% { opacity: 0.3; }
      50% { opacity: 0.7; }
      100% { opacity: 0.3; }
    }

    .chip-animation {
      position: absolute;
      width: 80px;
      height: 80px;
      background: linear-gradient(135deg, rgba(58,123,213,0.1) 0%, rgba(92,107,192,0.1) 100%);
      border-radius: 12px;
      z-index: 0;
      animation: float 6s ease-in-out infinite;
    }

    .chip-animation:after {
      content: "";
      position: absolute;
      width: 100%;
      height: 100%;
      background-image: 
        linear-gradient(to right, var(--primary-color) 2px, transparent 2px),
        linear-gradient(to bottom, var(--primary-color) 2px, transparent 2px);
      background-size: 10px 10px;
      border-radius: 12px;
      opacity: 0.2;
    }

    @keyframes float {
      0% { transform: translateY(0) rotate(0deg); }
      50% { transform: translateY(-20px) rotate(2deg); }
      100% { transform: translateY(0) rotate(0deg); }
    }

    .create-card {
      position: relative;
      z-index: 10;
      background: white;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      width: 100%;
      max-width: 450px;
      padding: 2.5rem;
      animation: fadeInUp 0.8s ease forwards;
      border: 1px solid var(--medium-gray);
      overflow: hidden;
    }

    .create-card:before {
      content: "";
      position: absolute;
      top: 0;
      left: 0;
      width: 4px;
      height: 100%;
      background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
    }

    h2 {
      color: var(--primary-color);
      font-weight: 600;
      text-align: center;
      margin-bottom: 2rem;
      letter-spacing: 0.5px;
      position: relative;
    }

    h2:after {
      content: "";
      position: absolute;
      bottom: -10px;
      left: 50%;
      transform: translateX(-50%);
      width: 50px;
      height: 3px;
      background: linear-gradient(to right, var(--primary-color), var(--secondary-color));
      border-radius: 3px;
    }

    .btn-primary {
      background-color: var(--primary-color);
      border: none;
      transition: all 0.3s ease;
      font-weight: 500;
      letter-spacing: 0.5px;
      padding: 12px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(58, 123, 213, 0.1);
    }

    .btn-primary:hover {
      background-color: #2a6bc7;
      transform: translateY(-2px);
      box-shadow: 0 6px 12px rgba(58, 123, 213, 0.15);
    }

    .form-outline {
      position: relative;
      margin-bottom: 1.5rem;
    }

    .form-outline input {
      border: 1px solid var(--medium-gray);
      border-radius: 8px;
      padding: 1rem 0.75rem 0.25rem 0.75rem;
      font-size: 1rem;
      width: 100%;
      outline: none;
      transition: all 0.3s ease;
      background-color: #f9fafb;
    }

    .form-outline input:focus {
      border-color: var(--primary-color);
      box-shadow: 0 0 0 3px rgba(58, 123, 213, 0.1);
      background-color: white;
    }

    .form-label {
      position: absolute;
      top: 1rem;
      left: 0.75rem;
      color: var(--dark-gray);
      font-size: 0.95rem;
      pointer-events: none;
      transition: all 0.3s ease;
    }

    .form-outline input:focus + .form-label,
    .form-outline input:not(:placeholder-shown) + .form-label {
      top: 0.3rem;
      font-size: 0.8rem;
      color: var(--primary-color);
      font-weight: 500;
    }

    .divider {
      display: flex;
      align-items: center;
      margin: 1.5rem 0;
      color: var(--dark-gray);
      font-size: 0.9rem;
    }

    .divider:before, .divider:after {
      content: "";
      flex: 1;
      border-bottom: 1px solid var(--medium-gray);
    }

    .divider:before { margin-right: 1rem; }
    .divider:after { margin-left: 1rem; }

    .g_id_signin {
      width: 100% !important;
      margin-top: 1rem;
    }
  </style>
</head>
<body>
  <div class="circuit-lines" id="circuitLines"></div>
  <div class="chip-animation" style="top:20%; left:10%;"></div>
  <div class="chip-animation" style="top:70%; left:80%; animation-delay: 1s;"></div>
  <div class="chip-animation" style="top:30%; left:75%; animation-delay: 2s;"></div>
  <div class="chip-animation" style="top:80%; left:15%; animation-delay: 3s;"></div>

  <div class="create-card">
    <?php if (!empty($message)): ?>
      <div class="alert alert-<?php echo $alertType; ?> mb-4">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <h2>Join ECEverse</h2>

    <form method="post" action="">
      <div class="form-outline">
        <input type="text" id="yourname" name="yourname" class="form-control" required placeholder=" " />
        <label class="form-label" for="yourname">Username</label>
      </div>

      <div class="form-outline">
        <input type="email" id="youremail" name="youremail" class="form-control" required placeholder=" " />
        <label class="form-label" for="youremail">Email address</label>
      </div>

      <div class="form-outline">
        <input type="password" id="password" name="password" class="form-control" required placeholder=" " />
        <label class="form-label" for="password">Password</label>
      </div>

      <div class="form-outline">
        <input type="password" id="cpassword" name="cpassword" class="form-control" required placeholder=" " />
        <label class="form-label" for="cpassword">Confirm Password</label>
      </div>

      <button type="submit" class="btn btn-primary w-100 mb-3">Create Account</button>
    </form>

    <div class="divider">OR</div>

    <div class="text-center mb-3">
      <div id="g_id_onload"
           data-client_id="816487949923-7k96vbp7r23cequahp035smoie5agfb3.apps.googleusercontent.com"
           data-callback="handleCredentialResponse"
           data-auto_prompt="false">
      </div>
      <div class="g_id_signin"
           data-type="standard"
           data-shape="rectangular"
           data-theme="outline"
           data-text="signin_with"
           data-size="large"
           data-logo_alignment="left">
      </div>
    </div>

    <p class="text-center mt-3">
      Already have an account? <a href="login.php" class="login-link">Sign in</a>
    </p>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const circuitContainer = document.getElementById('circuitLines');
      for (let i = 0; i < 15; i++) {
        const line = document.createElement('div');
        line.className = 'circuit-line';
        const isHorizontal = Math.random() > 0.5;
        const posX = Math.random() * 100;
        const posY = Math.random() * 100;
        const length = 50 + Math.random() * 150;
        const thickness = 1 + Math.random() * 2;
        const delay = Math.random() * 5;
        line.style.width = isHorizontal ? `${length}px` : `${thickness}px`;
        line.style.height = isHorizontal ? `${thickness}px` : `${length}px`;
        line.style.left = `${posX}%`;
        line.style.top = `${posY}%`;
        line.style.animationDelay = `${delay}s`;
        circuitContainer.appendChild(line);
      }
    });

    function handleCredentialResponse(response) {
      const form = document.createElement('form');
      form.method = 'POST';
      form.action = '';
      const input = document.createElement('input');
      input.type = 'hidden';
      input.name = 'id_token';
      input.value = response.credential;
      form.appendChild(input);
      document.body.appendChild(form);
      form.submit();
    }
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
