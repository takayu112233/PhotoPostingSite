<?php
session_start();

require_once '../classes/UserLogic.php';

$result = UserLogic::checkLogin();
if($result) {
    header('Location: ../search_img.php');
    return;
}

$err = "";
if(isset($_SESSION['msg'])){
    $err = $_SESSION['msg'];
}

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
    <link rel="icon" href="../img/favi.ico">
</head>

  <div id="login">
    <div id="window"></div>   <!-- -->

    <form action="login.php" method="POST">

        <input type="button" onclick="history.back(); return false;" value="back" style="margin-bottom: 10px;">

        <span class="fontawesome-user"><img src="../img/user.png"><br></span>
        <!--fontawesome-userはアイコン表示(人の)-->
        <input type="text" name="username"　required method="POST">
      
        <span class="fontawesome-user"><img src="../img/pass.png"><br></span>
        <!--fontawesome-userはアイコン表示(鍵の)-->
        <input type="password" name="pass" required >
        <p style="text-align:center;"><?php echo $err; ?></p>
        
        <input type="submit" value="Login" style="margin-bottom: 10px;">

        <input type="button" onclick="location.href='./signup_form.php'" value="register">

    </form>
</html>