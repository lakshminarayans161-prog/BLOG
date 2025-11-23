<?php
require 'config.php';
require_login();
if ($_SERVER["REQUEST_METHOD"]=== "POST"){
    $id = intval($_POST['id']?? 0);
    if($id >0){
        $stmt = $conn->prepare("DELETE FROM posts WHERE id = ?");
        $stmp->bind_param("i",$id);
        $stmt->execute();
    }
}
header("Location: index.php");
exit;