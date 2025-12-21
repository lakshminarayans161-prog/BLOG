require_login();
$search = trim($_GET['search'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 5;
$limit = 8;
$offset = ($page - 1) * $limit;
if ($search) {
  $stmt = $conn->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM posts 
        WHERE title LIKE ? OR content LIKE ? 
        ORDER BY created_at DESC LIMIT ?, ?");
  $stmt = $conn->prepare("SELECT SQL_CALC_FOUND_ROWS p.*, u.username FROM posts p
    LEFT JOIN users u ON u.id=p.user_id
    WHERE p.title LIKE ? OR p.content LIKE ?
    ORDER BY p.created_at DESC LIMIT ?,?");
  $like = "%$search%";
  $stmt->bind_param("ssii", $like, $like, $offset, $limit);
} else {
  $stmt = $conn->prepare("SELECT SQL_CALC_FOUND_ROWS * FROM posts 
        ORDER BY created_at DESC LIMIT ?, ?");
  $stmt = $conn->prepare("SELECT SQL_CALC_FOUND_ROWS p.*, u.username FROM posts p
    LEFT JOIN users u ON u.id=p.user_id
    ORDER BY p.created_at DESC LIMIT ?,?");
  $stmt->bind_param("ii", $offset, $limit);
}
$stmt->execute();
$res = $stmt->get_result();
$posts = $res->fetch_all(MYSQLI_ASSOC);
$totalRes = $conn->query("SELECT FOUND_ROWS() as total");
$totalRows = $totalRes->fetch_assoc()['total'];
$totalPages = ceil($totalRows / $limit);
$posts = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$totalRows = $conn->query("SELECT FOUND_ROWS() t")->fetch_assoc()['t'] ?? 0;
$totalPages = max(1, ceil($totalRows / $limit));
include 'header.php';
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>Posts</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="style.css">
</head>

<body class="container mt-4">
  <header class="d-flex justify-content-between align-items-center mb-4">
    <h1>Posts</h1>
    <div>
      <a class="btn btn-success" href="create.php">New Post</a>
      <a class="btn btn-outline-danger" href="logout.php">Logout</a>
    </div>
  </header>
  <form class="mb-3" method="get">
    <div class="input-group">
      <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Search posts...">
      <button class="btn btn-primary">Search</button>
    </div>
<div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-2 mb-3">
  <h2 class="m-0">Posts</h2>
  <form class="d-flex" method="get">
    <input class="form-control me-2" name="search" placeholder="Search posts..." value="<?= e($search) ?>">
    <button class="btn btn-primary btn-rounded">Search</button>
  </form>
  <?php if (empty($posts)): ?>
    <div class="alert alert-warning">No posts found.</div>
  <?php else: ?>
    <?php foreach ($posts as $p): ?>
      <div class="card mb-3">
</div>
<?php if (!$posts): ?><div class="alert alert-warning">No posts found.</div><?php endif; ?>
<div class="row g-3">
  <?php foreach ($posts as $p): ?>
    <div class="col-12 col-md-6">
      <div class="card h-100">
        <div class="card-body">
          <h3><?= htmlspecialchars($p['title']) ?></h3>
          <p><?= nl2br(htmlspecialchars($p['content'])) ?></p>
          <small class="text-muted"><?= $p['created_at'] ?></small><br>
          <a href="edit.php?id=<?= $p['id'] ?>" class="btn btn-sm btn-outline-secondary">Edit</a>
          <form action="delete.php" method="POST" style="display:inline;">
            <input type="hidden" name="id" value="<?= $p['id'] ?>">
            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Delete this post?')">Delete</button>
          </form>
          <div class="d-flex justify-content-between">
            <h5 class="card-title mb-1"><?= e($p['title']) ?></h5>
            <?php if ($p['user_id'] == current_user_id()): ?>
              <span class="badge bg-info">Yours</span>
            <?php elseif (is_admin()): ?>
              <span class="badge bg-danger">Admin</span>
            <?php elseif (is_editor()): ?>
              <span class="badge bg-secondary">Editor</span>
            <?php endif; ?>
          </div>
          <p class="card-text mt-2"><?= nl2br(e($p['content'])) ?></p>
        </div>
        <div class="card-footer bg-white d-flex justify-content-between align-items-center">
          <small class="text-muted">By <?= e($p['username'] ?? 'Unknown') ?> • <?= e($p['created_at']) ?></small>
          <?php if (can_modify_post($p['user_id'])): ?>
            <div class="d-flex gap-2">
              <a class="btn btn-sm btn-outline-secondary" href="edit.php?id=<?= $p['id'] ?>">Edit</a>
              <form method="post" action="delete.php" onsubmit="return confirm('Delete this post?')" class="m-0">
                <input type="hidden" name="id" value="<?= $p['id'] ?>">
                <button class="btn btn-sm btn-danger">Delete</button>
              </form>
            </div>
          <?php endif; ?>
        </div>
      </div>
    <?php endforeach; ?>
    <nav>
      <ul class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <li class="page-item <?= ($i == $page) ? 'active' : '' ?>">
            <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
          </li>
        <?php endfor; ?>
      </ul>
    </nav>
  <?php endif; ?>
</body>

</html>
    </div>
  <?php endforeach; ?>
</div>
<?php if ($totalPages > 1): ?>
  <nav class="mt-3">
    <ul class="pagination justify-content-center">
      <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <li class="page-item <?= $i == $page ? 'active' : '' ?>">
          <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
        </li>
      <?php endfor; ?>
    </ul>
  </nav>
<?php endif; ?>
<?php include 'footer.php'; ?>