<?php
session_start();
require_once '../classes/UserLogic.php';


//if (!$logout = filter_input(INPUT_POST, 'logout')){
//    exit('不正なリクエストです');
//}

//ログインしているかを判定し、セッションが切れていたらログインしてくださいとメッセージを出す
//$result = UserLogic::checkLogin();

//if (!$result) {
//    header('Location: https://techacademy.jp/');
//}
//ログアウトする
UserLogic::logout();

if(isset($_COOKIE["search_parameter"])){
    header('Location: ../search_img.php?'.$_COOKIE["search_parameter"]);
}else{
    header('Location: ../search_img.php');
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログアウト</title>
</head>
<body>
<h2>ログアウト完了</h2>
<p>ログアウトしました</p>
<a href="login_form.php">ログイン画面へ</a>
<a href="../search_img.php">写真検索画面へ</a>
</body>
</html>