<?php
require 'config.php';
require_login();
if (!is_admin()) die('Access denied: Admins only.');
$err = '';
$ok = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_editor'])) {
  $username = trim($_POST['username'] ?? '');
  $name = trim($_POST['name'] ?? '');
  $mobile = trim($_POST['mobile'] ?? '');
  $password = $_POST['password'] ?? '';
  try {
    if (strlen($username) < 3) throw new RuntimeException('Username too short.');
    if (!preg_match('/^\d{10}$/', $mobile)) throw new RuntimeException('Mobile must be 10 digits.');
    if (strlen($password) < 6) throw new RuntimeException('Password too short.');
    $q = $conn->prepare("SELECT 1 FROM users WHERE username=? OR mobile=? LIMIT 1");
    $q->bind_param("ss", $username, $mobile);
    $q->execute();
    if ($q->get_result()->fetch_row()) throw new RuntimeException('Username or mobile already exists.');
    $hash = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $conn->prepare("INSERT INTO users(username,name,mobile,password,role) VALUES(?,?,?,?, 'editor')");
    $stmt->bind_param("ssss", $username, $name, $mobile, $hash);
    $stmt->execute();
    $ok = 'Editor created.';
  } catch (Throwable $e) {
    $err = $e->getMessage();
  }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
  $id = intval($_POST['delete_id']);
  if ($id == current_user_id()) {
    $err = 'You cannot delete your own admin account.';
  } else {
    $stmt = $conn->prepare("DELETE FROM users WHERE id=?");
    $stmt->bind_param("i", $id);
    if ($stmt->execute()) $ok = 'User deleted.';
    else $err = 'Delete failed.';
  }
}
$res = $conn->query("SELECT id,username,name,mobile,role,created_at FROM users ORDER BY created_at DESC");
$users = $res->fetch_all(MYSQLI_ASSOC);
include 'header.php';
?>
<h2 class="mb-3">Manage Users</h2>
<?php if ($err): ?><div class="alert alert-danger"><?= e($err) ?></div><?php endif; ?>
<?php if ($ok):  ?><div class="alert alert-success"><?= e($ok)  ?></div><?php endif; ?>
<div class="card p-4 mb-4">
  <h5 class="mb-3">Create Editor</h5>
  <form method="post" class="row g-3">
    <input type="hidden" name="create_editor" value="1">
    <div class="col-md-3"><input class="form-control" name="username" placeholder="Username" required></div>
    <div class="col-md-3"><input class="form-control" name="name" placeholder="Full Name"></div>
    <div class="col-md-3"><input class="form-control" name="mobile" placeholder="Mobile (10 digits)" pattern="\d{10}" required></div>
    <div class="col-md-3"><input class="form-control" type="password" name="password" placeholder="Password (≥6)" required minlength="6"></div>
    <div class="col-12"><button class="btn btn-primary btn-rounded">Create Editor</button></div>
  </form>
</div>
<div class="table-responsive card p-0">
  <table class="table table-hover align-middle mb-0">
    <thead class="table-light">
      <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Name</th>
        <th>Mobile</th>
        <th>Role</th>
        <th>Joined</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($users as $u): ?>
        <tr>
          <td><?= $u['id'] ?></td>
          <td><?= e($u['username']) ?></td>
          <td><?= e($u['name']) ?></td>
          <td><?= e($u['mobile']) ?></td>
          <td>
            <span class="badge <?= $u['role'] === 'admin' ? 'bg-danger' : ($u['role'] === 'editor' ? 'bg-secondary' : 'bg-info') ?>">
              <?= ucfirst($u['role']) ?>
            </span>
          </td>
          <td><?= e($u['created_at']) ?></td>
          <td>
            <?php if ($u['id'] == current_user_id()): ?>
              <span class="text-muted">Current</span>
            <?php else: ?>
              <form method="post" class="d-inline" onsubmit="return confirm('Delete this user?');">
                <input type="hidden" name="delete_id" value="<?= $u['id'] ?>">
                <button class="btn btn-sm btn-danger">Delete</button>
              </form>
            <?php endif; ?>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</div>
<?php include 'footer.php'; ?>