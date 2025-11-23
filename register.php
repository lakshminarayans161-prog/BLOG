<?php
require 'config.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$username = trim($_POST['username'] ??'');

$password = $_POST['password'] ??'';

if ($username ===''|| $password==='') {

Serror "All fields are required.";

} else {

$hash = password_hash ($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?,?)");
$stmt->bind_param("ss", $username,$hash);
if ($stmt->execute()) {

header("Location: login.php?registered=1");
exit;
} else {

$error = "Username may already exist.";
}
}
}
?>
<!DOCTYPE html>

<html>

<head>

<meta charset="utf-8">

<title>Register</title>

</head>

<body>

<header>

<h1>Register</h1>

<nav><a href="login.php">Login</a></nav>

</header>

<?php if ($error): ?><div class="alert"><?= htmlspecialchars(Serror) ?></div><?php endif; ?>

<form method="POST">

<label>Username</label>

<input type="text" name="username" required>

<label>Password</label>

<input type="password" name="password" required>

<button class="btn btn-primary" type="submit">Create account</button>

</form>
</body>
</html>