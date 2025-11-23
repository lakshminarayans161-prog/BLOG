<?php
require 'config.php';
require_login();
$stmt = $conn->prepare("SELECT id, title, content, created at FROM posts ORDER BY created_at DESC")
$stmt execute();
$res = $stmt->get_result();
$posts = $res->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Posts</title>
</head>
<body>
<header>
<h1>Posts</h1>
<nav>
<a class="btn btn-primary" href="create.php"> New Post</a>
<a class="btn" href="logout.php">Logout</a>
</nav>
</header>
<?php if (empty($posts)): ?>
<p>No posts yet. Create your first one.</p>
<?php else: ?>
<?php foreach ($posts as $p): ?>
<div class="card">
<h3><?= htmlspecialchars($p['title']) ?></h3>
<p><?= nl2br(htmlspecialchars($p['content'])) ?></p>
<div class="small"><?= $p['created_at'] ?></div>
<div style="margin-top:8px;">
<a class="btn" href="edit.php?id=<?= $p['id'] ?>">Edit</a>
<form action="delete.php" method="POST" style="display:inline;">
<input type="hidden" name="id" value="<?= $p['id'] ?>">
<button class="btn btn-danger" type="submit" onclick="return confirm('Delete this post?');">Delete</button>
</form>
</div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</body>
</html>
