<?php
session_start();

require_once '../classes/UserLogic.php';

$result = UserLogic::checkLogin();
if($result) {
    header('Location: mypage.php');
    return;
}

$err = $_SESSION;

//セッションを消す
$_SESSION = array();
session_destroy();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ログイン画面</title>
</head>
<body>
        <h2>ログインフォーム</h2>
            <?php if (isset($err['msg'])) : ?>
                <p><?php echo $err['msg']; ?></p>
            <?php endif; ?>
    <form action="login.php" method="POST">

    <p>
        <label for="nickname">ニックネーム:</label>
        <input type="nickname" name="nickname">
            <?php if (isset($err['nickname'])) : ?>
                <p><?php echo $err['nickname']; ?></p>
            <?php endif; ?>
    </p>
    <p>
        <label for="pass">パスワード:</label>
        <input type="pass" name="pass">
            <?php if (isset($err['pass'])) : ?>
                <p><?php echo $err['pass']; ?></p>
            <?php endif; ?>
    </p>

    <p>
        <input type="submit" name="login" value="ログイン">
        <!--6/25追加　name="login"　-->
    </p>
    </form>
    <a href="signup_form.php">新規登録はこちら</a>
</body>
</html>