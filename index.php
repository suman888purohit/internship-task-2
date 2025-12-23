<?php
require 'db.php';

// Protect page: only logged-in users can see
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <title>Posts</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      margin: 0;
      padding: 0;
      min-height: 100vh;
      background: linear-gradient(to bottom right, #0d0d0d 0%, #ff4500 40%, #8b0000 100%);
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      align-items: center;
      justify-content: center;
    }
    .content-box {
      background-color: #fff;
      border-radius: 15px;
      box-shadow: 0 8px 20px rgba(0,0,0,0.5);
      width: 500px;
      padding: 30px;
      text-align: center;
    }
    h1 {
      color: #d32f2f;
      font-weight: 700;
      margin-bottom: 20px;
    }
    .welcome {
      font-size: 1.1rem;
      margin-bottom: 20px;
      color: #333;
    }
    .welcome a {
      color: #ff4500;
      font-weight: bold;
      text-decoration: none;
    }
    .welcome a:hover {
      color: #8b0000;
    }
    .btn-custom {
      background-color: #ff4500;
      border: none;
      color: #fff;
      font-weight: bold;
      transition: 0.3s;
    }
    .btn-custom:hover {
      background-color: #d32f2f;
    }
    .post-card {
      background-color: #f9f9f9;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
      padding: 15px;
      margin-bottom: 15px;
      text-align: left;
    }
    .post-card h2 {
      color: #ff4500;
      margin-bottom: 10px;
    }
    .post-card p {
      color: #444;
    }
    .post-card small {
      color: #666;
    }
    .post-card a {
      color: #d32f2f;
      font-weight: bold;
      text-decoration: none;
    }
    .post-card a:hover {
      color: #ff4500;
    }
  </style>
</head>
<body>
  <div class="content-box">
    <h1>All Posts</h1>
    <p class="welcome">
      Welcome, <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong> |
      <a href="logout.php">Logout</a>
    </p>
    <div class="mb-4">
      <a href="create.php" class="btn btn-custom">➕ Create New Post</a>
    </div>

    <?php foreach ($posts as $post): ?>
      <div class="post-card">
        <h2><?php echo htmlspecialchars($post['title']); ?></h2>
        <p><?php echo nl2br(htmlspecialchars($post['content'])); ?></p>
        <small>🕒 Created at: <?php echo $post['created_at']; ?></small><br>

        <!-- Show attachment if file_path exists -->
        <?php if (!empty($post['file_path'])): ?>
          <p>📎 <a href="<?php echo htmlspecialchars($post['file_path']); ?>" target="_blank">View Attachment</a></p>
        <?php endif; ?>

        <a href="edit.php?id=<?php echo $post['id']; ?>" class="me-2">✏️ Edit</a> |
        <a href="delete.php?id=<?php echo $post['id']; ?>" onclick="return confirm('Delete this post?');">🗑️ Delete</a>
      </div>
    <?php endforeach; ?>
  </div>
</body>
</html>