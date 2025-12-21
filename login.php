<?php
session_start();
require 'config.php';
// If already logged in, redirect
if (isset($_SESSION['user'])) {
  header("Location: index.php");
  exit;
}
$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id   = trim($_POST['user_or_mobile'] ?? '');
  $pass = $_POST['password'] ?? '';
  try {
    // Case-insensitive username OR mobile
    $stmt = $conn->prepare("SELECT * FROM users WHERE (LOWER(username)=LOWER(?) OR mobile=?) LIMIT 1");
    $stmt->bind_param("ss", $id, $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user   = $result->fetch_assoc();
    if ($user) {
      // Debug: check password length
      if (strlen($user['password']) < 60) {
        $err = "⚠️ Password hash truncated in DB (length=" . strlen($user['password']) . "). 
                        Fix with: ALTER TABLE users MODIFY password VARCHAR(255);";
      } elseif (password_verify($pass, $user['password'])) {
        $_SESSION['user'] = [
          'id'       => $user['id'],
          'username' => $user['username'],
          'name'     => $user['name'],
          'mobile'   => $user['mobile'],
          'role'     => $user['role'],
          'profile_pic' => $user['profile_pic'],
        ];
        header("Location: index.php");
        exit;
      } else {
        $err = "❌ Invalid credentials (hash mismatch).";
      }
    } else {
      $err = "❌ No user found with that username/mobile.";
    }
  } catch (Throwable $e) {
    $err = "Login error: " . $e->getMessage();
  }
}
include 'header.php';
?>
<div class="row justify-content-center">
  <div class="col-lg-5">
    <div class="card p-4 shadow-lg border-0 rounded-4">
      <h3 class="mb-3 text-center">🔐 Login</h3>
      <?php if ($err): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($err) ?></div>
      <?php endif; ?>
      <form method="post" novalidate>
        <div class="mb-3">
          <label class="form-label">Username or Mobile</label>
          <input class="form-control" name="user_or_mobile" required
            value="<?= htmlspecialchars($_POST['user_or_mobile'] ?? '') ?>">
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" class="form-control" name="password" required>
        </div>
        <div class="d-flex gap-2">
          <button class="btn btn-primary btn-rounded w-100">Login</button>
          <a class="btn btn-outline-secondary btn-rounded w-100" href="register.php">Register</a>
        </div>
      </form>
    </div>
  </div>
</div>
<?php include 'footer.php'; ?>