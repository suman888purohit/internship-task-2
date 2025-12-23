<?php
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$id = (int)($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    die("Post not found.");
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    $filePath = $post['file_path']; // keep existing file by default

    if ($title !== '' && $content !== '') {
        // Handle file upload if new file provided
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

        $upd = $pdo->prepare("UPDATE posts SET title=?, content=?, file_path=? WHERE id=?");
        $upd->execute([$title, $content, $filePath, $id]);
        header('Location: index.php');
        exit;
    } else {
        $message = 'Title and content are required.';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Edit Post</title>
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
    }
    h1 {
      color: #d32f2f;
      font-weight: 700;
      margin-bottom: 20px;
      text-align: center;
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
    <h1>Edit Post</h1>
    <p class="text-danger"><?php echo htmlspecialchars($message); ?></p>
    <form method="post" enctype="multipart/form-data" class="text-start">
      <div class="mb-3">
        <label class="form-label">Title</label>
        <input name="title" value="<?php echo htmlspecialchars($post['title']); ?>" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Content</label>
        <textarea name="content" rows="6" class="form-control" required><?php echo htmlspecialchars($post['content']); ?></textarea>
      </div>
      <div class="mb-3">
        <label class="form-label">Upload New File (optional)</label>
        <input type="file" name="upload" class="form-control">
        <?php if (!empty($post['file_path'])): ?>
          <p class="mt-2">📎 Current File: 
            <a href="<?php echo htmlspecialchars($post['file_path']); ?>" target="_blank">View Attachment</a>
          </p>
        <?php endif; ?>
      </div>
      <button type="submit" class="btn btn-custom w-100">Update</button>
    </form>
    <p class="mt-3 text-center"><a href="index.php">⬅ Back to Posts</a></p>
  </div>
</body>
</html>