<?php
session_start();
require_once '../classes/UserLogic.php';

$err = [];

$token = filter_input(INPUT_POST, 'csrf_token');
//トークンがない、もしくは一致しない場合、処理を中止
if (!isset($_SESSION['csrf_token']) || $token !== $_SESSION['csrf_token']) {
  exit('不正なリクエスト');
}

unset($_SESSION['csrf_token']);

if(!$username = filter_input(INPUT_POST, 'username')){
    $err[] ='ユーザ名を記入してください';
}
if(!$nickname = filter_input(INPUT_POST, 'nickname')){
    $err[] ='ニックネームを記入してください';
}
$pass = filter_input(INPUT_POST, 'pass');
//正規表現
if (!preg_match("/\A[a-z\d]{0,30}+\z/i",$pass)){
    $err[] = 'パスワードは英数字30文字以下にしてください。';
}
$password_conf = filter_input(INPUT_POST, 'password_conf');
if ($pass !== $password_conf){
    $err[] = '確認用パスワードど異なってます';
}

if (count($err) === 0){
    //ユーザを登録する処理
    $hasCreated = UserLogic::createUser($_POST);

    if(!$hasCreated){
        $err[] = '登録に失敗しました';
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
</head>
<body>
<?php if (count($err) > 0) : ?>
    <?php foreach($err as $e) : ?>
        <p><?php echo $e ?></p>
    <?php endforeach ?>
    <?php else : ?>
    <p>ユーザ登録が完了しました</p>
    <?php endif ?>
    <a href="./login_form.php">ログイン</a>
</body>
</html>