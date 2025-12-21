
<?php
if (session_status() === PHP_SESSION_NONE) session_start();
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli('localhost', 'root', '', 'blog');
$conn->set_charset('utf8mb4');
define('UPLOADS_DIR', __DIR__ . '/uploads');
define('UPLOADS_URL', 'uploads');
if (!is_dir(UPLOADS_DIR)) {
  mkdir(UPLOADS_DIR, 0775, true);
}
function e($s)
{
  return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8');
}
function require_login()
{
  if (empty($_SESSION['user'])) {
    header('Location: login.php');
    exit;
  }
}
function current_user()
{
  return $_SESSION['user'] ?? null;
}
function current_user_id()
{
  return $_SESSION['user']['id'] ?? 0;
}
function is_admin()
{
  return (current_user()['role'] ?? '') === 'admin';
}
function is_editor()
{
  return (current_user()['role'] ?? '') === 'editor';
}
function is_user()
{
  return (current_user()['role'] ?? '') === 'user';
}
function can_modify_post($post_user_id)
{
  if (is_admin()) return true;
  if (is_editor() && current_user_id() == $post_user_id) return true;
  return false;
}
function save_profile_upload($field = 'profile_pic')
{
  if (!isset($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) return null;
  $f = $_FILES[$field];
  if ($f['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('Upload failed.');
  $finfo = finfo_open(FILEINFO_MIME_TYPE);
  $mime = finfo_file($finfo, $f['tmp_name']);
  finfo_close($finfo);
  $allowed = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
  if (!isset($allowed[$mime])) throw new RuntimeException('Invalid image type.');
  if ($f['size'] > 2 * 1024 * 1024) throw new RuntimeException('Image too large (max 2MB).');
  $ext = $allowed[$mime];
  $name = 'avatar_' . current_user_id() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
  $dest = UPLOADS_DIR . '/' . $name;
  if (!move_uploaded_file($f['tmp_name'], $dest)) throw new RuntimeException('Save failed.');
  return UPLOADS_URL . '/' . $name; // relative url
}
