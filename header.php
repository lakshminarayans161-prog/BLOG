<?php require_once 'config.php'; ?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>RentLet Blog</title>
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background: #f6f8fb;
    }

    .navbar-brand {
      font-weight: 700;
      letter-spacing: .3px;
    }

    .card {
      border: 0;
      border-radius: 16px;
      box-shadow: 0 6px 18px rgba(0, 0, 0, .06);
    }

    .btn-rounded {
      border-radius: 999px;
    }

    .avatar {
      width: 36px;
      height: 36px;
      border-radius: 50%;
      object-fit: cover;
    }
  </style>
</head>

<body>
  <nav class="navbar navbar-expand-lg bg-white shadow-sm sticky-top">
    <div class="container">
      <a class="navbar-brand text-primary" href="index.php">RentLet Blog</a>
      <button class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button>
      <div class="collapse navbar-collapse" id="nav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <?php if (current_user()): ?>
            <li class="nav-item"><a class="nav-link" href="index.php">Posts</a></li>
          <?php endif; ?>
          <?php if (is_admin() || is_editor()): ?>
            <li class="nav-item"><a class="nav-link" href="add.php">Add Post</a></li>
          <?php endif; ?>
          <?php if (is_admin()): ?>
            <li class="nav-item"><a class="nav-link" href="users.php">Manage Users</a></li>
          <?php endif; ?>
        </ul>
        <ul class="navbar-nav">
          <?php if (!current_user()): ?>
            <li class="nav-item"><a class="btn btn-primary btn-rounded" href="login.php">Login</a></li>
          <?php else: ?>
            <li class="nav-item d-flex align-items-center me-3">
              <?php $u = current_user();
              $pic = $u['profile_pic'] ? e($u['profile_pic']) : 'https://ui-avatars.com/api/?name=' . urlencode($u['username']) . '&size=64'; ?>
              <img class="avatar me-2" src="<?= $pic ?>" alt="">
              <span class="small text-muted"><?= e(ucfirst($u['role'])) ?></span>
            </li>
            <li class="nav-item"><a class="nav-link" href="account.php">Manage Account</a></li>
            <li class="nav-item"><a class="btn btn-outline-secondary btn-rounded ms-lg-2" href="logout.php">Logout</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>
  <div class="container py-4">