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
</head>
<body>
    <h2>ユーザ登録フォーム</h2>
        <?php if (isset($login_err)) : ?>
            <p><?php echo $login_err; ?></p>
        <?php endif; ?>
    <form action="register.php" method="POST">
    <p>
        <label for="username">ユーザ名:</label>
        <input type="text" name="username">
    </p>
    <p>
        <label for="nickname">ニックネーム:</label>
        <input type="nickname" name="nickname">
    </p>
    <p>
        <label for="password">パスワード:</label>
        <input type="password" name="pass">
    </p>
    <p>
        <label for="password_conf">パスワード確認:</label>
        <input type="password" name="password_conf">
    </p>
    <input type="hidden" name="csrf_token" value="<?php echo h(setToken()); ?>">
    <!-- -->
    <p>
        <input type="submit" value="新規登録">
    </p>
    </form>
    <a href="./login_form.php">ログイン画面に戻る</a>       
    <a href="../search_img.php">写真検索画面に戻る</a>
</body>
</html>