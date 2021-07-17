<?php
session_start();

require_once '../classes/UserLogic.php';

$result = UserLogic::checkLogin();
if($result) {
    header('Location: ../search_img.php');
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
    <link rel="stylesheet" href="login_form.css">

</head>

  <div id="login">
    <div id="window"></div>   <!-- -->

    <form action="login.php" method="POST">

        <input type="button" onclick="history.back(); return false;" value="前に戻る" style="margin-bottom: 10px;">

        <span class="fontawesome-user"></span>
        <!--fontawesome-userはアイコン表示(人の)-->
        <input type="text" name="username"　required method="POST">
        <?php if (isset($err['username'])) : ?>
                <p><?php echo $err['username']; ?></p>
        <?php endif; ?>
      
        <span class="fontawesome-lock"></span>
        <!--fontawesome-userはアイコン表示(鍵の)-->
        <input type="password" name="pass" required >
        <?php if (isset($err['pass'])) : ?>
                <p><?php echo $err['pass']; ?></p>
        <?php endif; ?>
        
        <input type="submit" value="Login" style="margin-bottom: 10px;">

        <input type="button" onclick="location.href='./signup_form.php'" value="register">

    </form>
</html>