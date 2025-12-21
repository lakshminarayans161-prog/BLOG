<?php
require 'config.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $id = intval($_POST['id'] ?? 0);
  $s = $conn->prepare("SELECT user_id FROM posts WHERE id=?");
  $s->bind_param("i", $id);
  $s->execute();
  $p = $s->get_result()->fetch_assoc();
  if (!$p) die('Post not found.');
  if (!can_modify_post($p['user_id'])) die('Unauthorized.');
  $d = $conn->prepare("DELETE FROM posts WHERE id=?");
  $d->bind_param("i", $id);
  $d->execute();
}
header('Location:index.php');