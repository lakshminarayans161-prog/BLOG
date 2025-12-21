
<?php
require 'config.php';
require_login();
if (!(is_admin() || is_editor())) die('Unauthorized.');
$err = '';
$ok = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $title = trim($_POST['title'] ?? '');
  $content = trim($_POST['content'] ?? '');
  if (!$title || !$content) $err = 'Title & content required.';
  else {
    $stmt = $conn->prepare("INSERT INTO posts(user_id,title,content) VALUES(?,?,?)");
    $uid = current_user_id();
    $stmt->bind_param("iss", $uid, $title, $content);
    $stmt->execute();
    $ok = 'Post created.';
    $title = $content = '';
  }
}
include 'header.php';
?>
<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="card p-4">
      <h3 class="mb-3">Add Post</h3>
      <?php if ($err): ?><div class="alert alert-danger"><?= e($err) ?></div><?php endif; ?>
      <?php if ($ok):  ?><div class="alert alert-success"><?= e($ok)  ?></div><?php endif; ?>
      <form method="post">
        <div class="mb-3"><label class="form-label">Title</label>
          <input name="title" class="form-control" value="<?= e($title ?? '') ?>" required>
        </div>
        <div class="mb-3"><label class="form-label">Content</label>
          <textarea name="content" rows="6" class="form-control" required><?= e($content ?? '') ?></textarea>
        </div>
        <button class="btn btn-primary btn-rounded">Publish</button>
        <a href="index.php" class="btn btn-outline-secondary btn-rounded">Cancel</a>
      </form>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>
