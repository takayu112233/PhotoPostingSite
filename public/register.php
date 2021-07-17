<?php
session_start();
require_once '../classes/UserLogic.php';
require_once '../dbconnect.php';

$err = [];

$token = filter_input(INPUT_POST, 'csrf_token');
//トークンがない、もしくは一致しない場合、処理を中止
if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
  exit('不正なリクエスト');
}

unset($_SESSION['csrf_token']);
//ユーザーネーム
if(!$username = filter_input(INPUT_POST, 'username')){
    $err[] ='ユーザネームを記入してください';
}
$username = filter_input(INPUT_POST, 'username');
if(!preg_match("/\A[a-zA-Z_0-9]{0,30}+\z/i",$username)){
    $err[] ='ユーザネームは英数文字30文字以下にしてください。';
}
$pdo = connect();
$stmt = $pdo -> query("SELECT * FROM users");
$uaername = filter_input(INPUT_POST, 'username');
while($item = $stmt->fetch()) {
    if($item['user_name'] == $username){
        $err[] ='ユーザネームは使用されてます。';
    }
}
//ニックネーム
if(!$nickname = filter_input(INPUT_POST, 'nickname')){
    $err[] ='ニックネームを記入してください';
}
$nickname = filter_input(INPUT_POST, 'nickname');
if(!preg_match("/^.{0,20}$/",$nickname)){
    $err[] ='ニックネームは英数字と日本語20文字以下にしてください。';
}
//パスワード
$pass = filter_input(INPUT_POST, 'pass');
if(!$pass = filter_input(INPUT_POST, 'pass')){
    $err[] ='パスワードを記入してください';
}
if (!preg_match("/\A[a-z\d]{0,30}+\z/i",$pass)){
    $err[] = 'パスワードは英数字30文字以下にしてください。';
}
//if (!preg_match("/\A(?=.*?[a-z])(?=.*?[A-Z])(?=.*?\d)[a-zA-Z\d]{0,100}+\z/",$pass)){
//    $err[] = 'パスワードは半角英小文字大文字それぞれ1種類以上含めてください。';
//}
//確認用パスワード
$password_conf = filter_input(INPUT_POST, 'password_conf');
if ($pass !== $password_conf){
    $err[] = '確認用パスワードと異なってます。';
}

if (count($err) === 0){
    //ユーザを登録する処理
    $hasCreated = UserLogic::createUser($_POST);

    if(!$hasCreated){
        $err[] = '登録に失敗しました。';
    }
}



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ登録完了画面</title>
    <link rel="stylesheet" href="register.css">
</head>
<div id="login">
    <form>
        <?php if (count($err) > 0) : ?>
        <?php foreach($err as $e) : ?>
        <p><?php echo $e ?></p>
        <?php endforeach ?>
        <?php else : ?>
        <p>ユーザ登録が完了しました</p>
        <?php endif ?>

        <?php if (count($err)>0) :?>
            <input type="button" onclick="location.href='./signup_form.php'" value="Go to the signup page">
        <?php elseif (count($err)==0) :?>
            <input type="button" onclick="location.href='./login_form.php'" value="Go to the login page">

        <?php endif; ?>

    </form>
</html>