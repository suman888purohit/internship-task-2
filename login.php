<?php
require 'db.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $pdo->prepare('SELECT id, password FROM users WHERE username = ?');
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $username;
        header('Location: index.php'); // redirect to posts page
        exit;
    } else {
        $message = 'Invalid username or password.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      background: radial-gradient(circle at top left, #ff4d6d 0%, #ff99ac 40%, #fff0f5 100%);
      overflow: hidden;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .shape {
      position: absolute;
      border-radius: 50%;
      filter: blur(60px);
      opacity: 0.6;
      animation: float 10s infinite ease-in-out;
    }

    .shape1 {
      width: 300px;
      height: 300px;
      background: #ff4d6d;
      top: 10%;
      left: 5%;
    }

    .shape2 {
      width: 250px;
      height: 250px;
      background: #ff99ac;
      bottom: 10%;
      right: 10%;
    }

    @keyframes float {
      0%, 100% { transform: translateY(0); }
      50% { transform: translateY(-20px); }
    }

    .card {
      backdrop-filter: blur(10px);
      background-color: rgba(255, 255, 255, 0.8);
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.2);
      z-index: 1;
    }

    .btn-primary {
      background-color: #ff4d6d;
      border: none;
    }

    .btn-primary:hover {
      background-color: #e63950;
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
          <h3 class="text-center text-danger mb-4">Login</h3>
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
            <button type="submit" class="btn btn-primary w-100">Login</button>
          </form>
          <p class="mt-3 text-center"><a href="register.php" class="text-danger">Register</a></p>
        </div>
      </div>
    </div>
  </div>
</body>
</html>