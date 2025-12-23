<?php
require 'db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $message = 'Username and password are required.';
    } else {
        // Check if username already exists
        $stmt = $pdo->prepare('SELECT id FROM users WHERE username = ?');
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $message = 'Username already exists.';
        } else {
            // Hash password before storing
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)');
            $stmt->execute([$username, $hash]);
            $message = 'Registration successful! You can now login.';
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      /* Softer red-pink gradient */
      background: linear-gradient(to bottom right, #ff6b6b 0%, #ff85a2 40%, #ffb3c6 80%, #d6336c 100%);
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .shape {
      position: absolute;
      border-radius: 50%;
      filter: blur(60px);
      opacity: 0.5;
      animation: float 10s infinite ease-in-out;
    }

    .shape1 {
      width: 280px;
      height: 280px;
      background: #ff6b6b; /* coral red */
      top: 15%;
      left: 8%;
    }

    .shape2 {
      width: 240px;
      height: 240px;
      background: #ffb3c6; /* pastel pink */
      bottom: 8%;
      right: 12%;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-20px); }
    }

    .card {
      backdrop-filter: blur(12px);
      background-color: rgba(255, 255, 255, 0.85);
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.3);
      z-index: 1;
    }

    .btn-primary {
      background-color: #ff6b6b; /* coral red button */
      border: none;
    }

    .btn-primary:hover {
      background-color: #d6336c; /* deeper pink on hover */
    }
  </style>
</head>
<body>
  <div class="shape shape1"></div>
  <div class="shape shape2"></div>

  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-4">
        <div class="card p-4">
          <h3 class="text-center text-danger mb-4">Register</h3>
          <p class="text-danger text-center"><?php echo htmlspecialchars($message); ?></p>
          <form method="post">
            <div class="mb-3">
              <label class="form-label">Username</label>
              <input name="username" class="form-control" required>
            </div>
            <div class="mb-3">
              <label class="form-label">Password</label>
              <input name="password" type="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Register</button>
          </form>
          <p class="mt-3 text-center"><a href="login.php" class="text-danger">Login</a></p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>