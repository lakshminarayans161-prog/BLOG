<?php
require 'config.php';
require_login();
$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
header("Location: index.php");
exit;
}

$stmt = $conn->prepare("SELECT id, title, content FROM posts WHERE id=?");

$stmt->bind_param("i", $id);

$stmt->execute();

$post = $stmt->get_result()->fetch_assoc();

if (!$post) {

header("Location: index.php");

exit;
}
$error="";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

$title = trim($_POST['title'] ??'');

$content = trim($_POST['content'] ?? '');

if ($title==="" || $content === "") {

$error "Title and content are required.";

} else {

$up $conn->prepare("UPDATE posts SET title=?, content = ? WHERE id=?");

$up->bind_param("ssi", $title, $content, $id);

$up->execute();

header("Location: index.php");

exit;

}
}
?>
<!DOCTYPE html>

<html>

<head>

<meta charset="utf-8">

<title>Edit Post</title>

</head>

<body>

<header>

<h1>Edit Post</h1>
  <nav>
                <a class="btn" herf="index.php">Back</a>
                <a class="btn" herf="logout.php">Logout</a>
</nav>
</header>
<?php if($error): ?><div class="alert"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <from method="POST">
        <label>Title</label>
        <input type="text" name="title" value="<?= htmlspecialchars($post['title'])?>" required>
        <labal>Content</labal>
        <textarea name="content" rows="8" required><?= htmlspecialchars($post['content'])?></textarea>
        <button class = "btn btn-primary" type="submit">Update</button>
</form>
</body>
</htmL> 