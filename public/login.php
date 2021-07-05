<?php
session_start();

require_once '../classes/UserLogic.php';

$err = [];

if(!$username = filter_input(INPUT_POST, 'username')){
    $err['username'] ='ニックネームを記入してください';
}
if(!$pass = filter_input(INPUT_POST, 'pass')){
    $err['pass'] ='パスワードを記入してください';
}


if (count($err) > 0){
    //エラーがあった場合戻す
    $_SESSION = $err;
    header('Location: login_form.php');
    return;
}
//ログイン成功時の処理
$result = UserLogic::login($username, $pass);
//ログイン失敗時の処理
if (!$result){
    header('Location: login_form.php');
    return;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン完了</title>
</head>
<body>
<h2>ログイン完了</h2>
<p>ログインしました</p>
<a href="../mypage.php">マイページへ</a>
<a href="../search_img.php">写真検索へ</a>
</body>
</html>