
<?php
require 'config.php';
require login();
$errar = "";
if ($_SERVER["REQUEST_METHOD"] == "POST"){
    $title = trim($_POST ['title'] ?? '');
    $content = trim($_POST ['content'] ?? '');
    if ($title === "" || $content === ""){
        $error = "Title and content are required.";
    } else {
        $stmt = $conn->prepare("INSERT INTO POSTS (title,content)values(?,?)");
        $stmt->bind_param("ss",$title $content );
        $stmt->execute();
        header( "location : index.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Create Post</title>
</head>
<body>
    <header>
        <h1>Create Post<h1>
            <nav>
                <a class="btn" herf="index.php">Back</a>
                <a class="btn" herf="logout.php">Logout</a>
</nav>
</header>
<?php if($error): ?><div class="alert"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <from method="POST">
        <label>Title</label>
        <input type="text" name="title" required>
        <labal>Content</labal>
        <textarea name="content" rows="8" required></textarea>
        <button class = "btn btn-primary" type="submit">Save</button>
</form>
</body>
</htmL> 