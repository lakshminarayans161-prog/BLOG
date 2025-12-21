<?php
require 'config.php';
require_login();
$u = $conn->prepare("SELECT * FROM users WHERE id=?");
$uid = current_user_id();
$u->bind_param("i", $uid);
$u->execute();
$user = $u->get_result()->fetch_assoc();
$err = '';
$ok = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $name     = trim($_POST['name'] ?? '');
  $mobile   = trim($_POST['mobile'] ?? '');
  $password = $_POST['password'] ?? '';
  $pic_path = null;
  try {
    if (strlen($username) < 3) throw new RuntimeException('Username must be ≥ 3 chars.');
    if ($mobile && !preg_match('/^\d{10}$/', $mobile)) throw new RuntimeException('Mobile must be 10 digits.');
    $q = $conn->prepare("SELECT 1 FROM users WHERE (username=? AND id<>?) OR (mobile=? AND mobile IS NOT NULL AND id<>?) LIMIT 1");
    $q->bind_param("sisi", $username, $uid, $mobile, $uid);
    $q->execute();
    if ($q->get_result()->fetch_row()) throw new RuntimeException('Username or mobile already registered.');
    try {
      $pic_path = save_profile_upload('profile_pic');
    } catch (Throwable $e) {
    }
    $sql = "UPDATE users SET username=?, name=?, mobile=?";
    $params = [$username, $name, $mobile];
    $types = 'sss';
    if (!empty($password)) {
      $sql .= ", password=?";
      $params[] = password_hash($password, PASSWORD_DEFAULT);
      $types .= 's';
    }
    if ($pic_path) {
      $sql .= ", profile_pic=?";
      $params[] = $pic_path;
      $types .= 's';
    }
    $sql .= " WHERE id=?";
    $params[] = $uid;
    $types .= 'i';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();
    $_SESSION['user']['username'] = $username;
    $_SESSION['user']['name'] = $name;
    $_SESSION['user']['mobile'] = $mobile;
    if ($pic_path) $_SESSION['user']['profile_pic'] = $pic_path;
    $ok = 'Account updated.';
    $u = $conn->prepare("SELECT * FROM users WHERE id=?");
    $u->bind_param("i", $uid);
    $u->execute();
    $user = $u->get_result()->fetch_assoc();
  } catch (Throwable $e) {
    $err = $e->getMessage();
  }
}
include 'header.php';
?>
<div class="row justify-content-center">
  <div class="col-lg-7">
    <div class="card p-4">
      <h3 class="mb-3">Manage Account</h3>
      <?php if ($err): ?><div class="alert alert-danger"><?= e($err) ?></div><?php endif; ?>
      <?php if ($ok):  ?><div class="alert alert-success"><?= e($ok)  ?></div><?php endif; ?>
      <form method="post" enctype="multipart/form-data" class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Username</label>
          <input class="form-control" name="username" value="<?= e($user['username']) ?>" required minlength="3">
        </div>
        <div class="col-md-6">
          <label class="form-label">Full Name</label>
          <input class="form-control" name="name" value="<?= e($user['name']) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">Mobile (10 digits)</label>
          <input class="form-control" name="mobile" pattern="\d{10}" value="<?= e($user['mobile']) ?>">
        </div>
        <div class="col-md-6">
          <label class="form-label">New Password (optional)</label>
          <input type="password" class="form-control" name="password" minlength="6" placeholder="Leave blank to keep same">
        </div>
        <div class="col-md-12 d-flex align-items-center gap-3">
          <?php $pic = $user['profile_pic'] ? e($user['profile_pic']) : 'https://ui-avatars.com/api/?name=' . urlencode($user['username']) . '&size=96'; ?>
          <img src="<?= $pic ?>" class="avatar" style="width:64px;height:64px">
          <div class="flex-grow-1">
            <label class="form-label">Profile Picture (max 2MB)</label>
            <input type="file" class="form-control" name="profile_pic" accept="image/*">
          </div>
        </div>
        <div class="col-12">
          <button class="btn btn-primary btn-rounded">Save Changes</button>
          <a href="index.php" class="btn btn-outline-secondary btn-rounded">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>s