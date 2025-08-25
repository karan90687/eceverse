<?php
session_start();
$message = "";
$alertType = "danger";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'partials/_dbconnect.php';

    $username = mysqli_real_escape_string($conn, $_POST["username"]);
    $password = $_POST["password"];

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);
        
        if ($password === $user['password']) {
            session_regenerate_id(true);
            $_SESSION['loggedin'] = true;
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            $alertType = "success";
            $message = "Login successful! Redirecting...";
            header("Location: problems.php");
            exit();
        } else {
            $message = "Invalid password!";
        }
    } else {
        $message = "Username not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Login - ECEverse</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet" />
  <style>
    body, button, input, a {
        cursor: url('https://cur.cursors-4u.net/cursors/cur-13/cur1167.cur'), auto;
    }

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

    .login-card {
      position: relative;
      z-index: 9999;
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

    .login-card:before {
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

    .alert-success {
      background-color: #f0f9ff;
      color: var(--primary-color);
      border: 1px solid #b3e0ff;
      text-align: center;
      border-radius: 8px;
    }

    .alert-danger {
      background-color: #fff0f0;
      color: #e74c3c;
      border: 1px solid #ffb3b3;
      text-align: center;
      border-radius: 8px;
    }

    @keyframes fadeInUp {
      0% { opacity: 0; transform: translateY(40px); }
      100% { opacity: 1; transform: translateY(0); }
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

    .login-link {
      color: var(--primary-color);
      font-weight: 500;
      text-decoration: none;
      transition: all 0.3s ease;
    }

    .login-link:hover {
      color: #2a6bc7;
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="circuit-lines" id="circuitLines"></div>
  <div class="chip-animation" style="top:20%; left:10%;"></div>
  <div class="chip-animation" style="top:70%; left:80%; animation-delay: 1s;"></div>
  <div class="chip-animation" style="top:30%; left:75%; animation-delay: 2s;"></div>
  <div class="chip-animation" style="top:80%; left:15%; animation-delay: 3s;"></div>

  <div class="login-card">
    <?php if (!empty($message)): ?>
      <div class="alert alert-<?php echo $alertType; ?>">
        <?php echo htmlspecialchars($message); ?>
      </div>
    <?php endif; ?>

    <h2>Login to ECEverse</h2>

    <form method="post" action="">
      <div class="form-outline">
        <input type="text" id="username" name="username" class="form-control" required placeholder=" " />
        <label class="form-label" for="username">Username</label>
      </div>

      <div class="form-outline">
        <input type="password" id="password" name="password" class="form-control" required placeholder=" " />
        <label class="form-label" for="password">Password</label>
      </div>

      <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
    </form>

    <p class="text-center">
      <a href="forgot_password.php" class="login-link">Forgot Password?</a><br />
      Don't have an account? <a href="create_account.php" class="login-link">Register here</a>
    </p>
  </div>

  <script>
    // Generate animated circuit lines (optional)
    document.addEventListener('DOMContentLoaded', function() {
      const container = document.getElementById('circuitLines');
      for (let i = 0; i < 15; i++) {
        const line = document.createElement('div');
        line.className = 'circuit-line';
        const isHorizontal = Math.random() > 0.5;
        line.style.position = 'absolute';
        line.style.backgroundColor = 'var(--primary-color)';
        line.style.opacity = '0.1';
        line.style.width = isHorizontal ? `${Math.random() * 200 + 50}px` : '2px';
        line.style.height = isHorizontal ? '2px' : `${Math.random() * 200 + 50}px`;
        line.style.left = `${Math.random() * 100}%`;
        line.style.top = `${Math.random() * 100}%`;
        container.appendChild(line);
      }
    });
  </script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
