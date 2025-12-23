<?php
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $filePath = null;

    if ($title === '' || $content === '') {
        $message = 'Title and content are required.';
    } else {
        // Handle file upload if provided
        if (!empty($_FILES['upload']['name'])) {
            $targetDir = "uploads/";
            if (!is_dir($targetDir)) {
                mkdir($targetDir, 0777, true);
            }
            $fileName = time() . "_" . basename($_FILES['upload']['name']);
            $targetFile = $targetDir . $fileName;
            if (move_uploaded_file($_FILES['upload']['tmp_name'], $targetFile)) {
                $filePath = $targetFile;
            }
        }

        // Save post with optional file path
        $stmt = $pdo->prepare("INSERT INTO posts (title, content, file_path) VALUES (?, ?, ?)");
        $stmt->execute([$title, $content, $filePath]);
        header('Location: index.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Create Post</title>
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
      width: 450px;
      padding: 30px;
      text-align: center;
    }
    h1 {
      color: #d32f2f;
      font-weight: 700;
      margin-bottom: 20px;
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
    a {
      color: #ff4500;
      text-decoration: none;
      font-weight: bold;
    }
    a:hover {
      color: #8b0000;
    }
  </style>
</head>
<body>
  <div class="content-box">
    <h1>Create Post</h1>
    <p class="text-danger"><?php echo htmlspecialchars($message); ?></p>
    <!-- enctype added for file upload -->
    <form method="post" enctype="multipart/form-data" class="text-start">
      <div class="mb-3">
        <label class="form-label">Title</label>
        <input name="title" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Content</label>
        <textarea name="content" rows="6" class="form-control" required></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Upload File</label>
        <input type="file" name="upload" class="form-control">
      </div>
      <button type="submit" class="btn btn-custom w-100">Save</button>
    </form>
    <p class="mt-3"><a href="index.php">⬅ Back to Posts</a></p>
  </div>
</body>
</html>