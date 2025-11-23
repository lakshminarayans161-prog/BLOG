<?php
require 'config.php';

$error="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$username = trim($_POST['username'] ??'');
$password = $_POST['password'] ?? '';
$stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username?");

$stmt->bind_param("s", $username);

$stmt->execute();

$result = $stmt->get_result();

$user = $result->fetch_assoc();

if ($user && password_verify ($password, $user['password'])) {

$_SESSION ['username']= $user['username'];

$_SESSION ['user_id'] Suser['id'];

header("Location: index.php");

exit;
} else{
$error "Invalid username or password.";
}
}
$registered isset($_GET['registered']);
?>
<!DOCTYPE html>

<html>
<meta charset="utf-8">

<title>Login</title>

</head>

<body>

<header>

<h1> LOgin </h1>

<nav><a href="register.php">Register</a></nav>

</header>

<?php if ($registered): ?><div class="alert"> Registration successful.Please login.</div><?php endif; ?>
<form method="POST">

<label> Username</label>
<input type="text" name="username" required>
<label> Password</label>

<input type="password" name="password" required>
<button class="btn-primary" type="submit">LOgin</button>
</form>
</body>
</htmL>