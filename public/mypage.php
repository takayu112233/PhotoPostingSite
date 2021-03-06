<?php
session_start();
require_once '../classes/UserLogic.php';
require_once '../function.php';

//ログインしているかを判定
$result = UserLogic::checkLogin();

if (!$result) {
    $_SESSION['login_err'] = 'ユーザを登録してログインしてください';
    header('Location: signup_form.php');
    return;
}

$login_user_name = $_SESSION['login_user_name'];
$login_user_nickname = $_SESSION['login_user_nickname'];

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>マイページ</title>
</head>
<body>
<h2>マイページ</h2>
<p>ログインユーザ:<?php echo h($login_user['user_name']) ?></p>
<p>ニックネーム:<?php echo h($login_user['nickname']) ?></p>
<form action="logout.php" method="POST">
<input type="submit" name="logout" value="ログアウト">
</form>
</body>
</html>