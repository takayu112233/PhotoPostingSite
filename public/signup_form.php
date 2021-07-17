<?php
session_start();
require_once '../function.php';
require_once '../classes/UserLogic.php';

$result = UserLogic::checkLogin();
if($result) {
    header('Location: mypage.php');
    return;
}
$login_err = isset($_SESSION['login_err']) ? $_SESSION['login_err'] : null;
unset($_SESSION['login_err']);
//isset(変数)で変数に値が入っているか確認し、3項演算子で比較
//$_SESSION['login_err']を評価し中身があるなら$login_errに代入、ないのならばnullを代入
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ登録画面</title>
    <link rel="stylesheet" href="signup_form.css">
    <link rel="icon" href="../img/favi.ico">
</head>
  <div id="login">
  <div id="window"></div>   <!-- -->
    <form action="register.php" method="POST">

        <p><label for="username">username:</label></p>
        <span class="fontawesome-user"><img src="../img/user.png"><br></span>
        <input type="text" name="username"　required> 

        <p><label for="nickname">nickname:</label></p>  
        <span class="fontawesome-user"><img src="../img/user.png"><br></span>
        <input type="text" name="nickname" required>

        <p><label for="password">password:</label></p> 
        <span class="fontawesome-user"><img src="../img/pass.png"><br></span>
        <input type="password" name="pass" required>

        <p><label for="password_conf">password confirmation:</label></p> 
        <span class="fontawesome-user"><img src="../img/pass.png"><br></span>
        <input type="password" name="password_conf" required>

        <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
             
        <input type="submit" value="register" style="margin-bottom: 10px;">
         
        <input type="button" onclick="location.href='./login_form.php'" value="login page" style="margin-bottom: 10px;">
        
        <input type="button" onclick="location.href='../search_img.php'" value="photo search page">
    </form>
</html>