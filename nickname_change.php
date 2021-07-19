<!DOCTYPE html>
<?php 
session_start();

require("libDB.php");
$nickname = "";

$same_shop = false;

if (!isset($_SESSION['login_user_nickname'])){
    header('Location: ./search_img.php');
}

$default_css = "";


$msg = "";

function name(){
    $db = new libDB();
    $pdo = $db->getPDO();
    $sql_q = "select * from users";
    $sql = $pdo->prepare($sql_q);
    $sql->execute();
    $result = $sql->fetchAll();

    foreach($result as $loop){
        if($_SESSION["login_user_id"] == $loop['user_id']){
            echo $loop["nickname"];
        }
    }
}

if(!isset($_POST['nick_name'])){
    
}else{
    $nick_change = $_POST['nick_name'];
    $nick_change = htmlspecialchars($nick_change, ENT_QUOTES, "UTF-8");
    $user_id = $_SESSION['login_user_id'];

    $db = new libDB();
    $pdo = $db->getPDO();
    $sql_q = "update users set nickname=\"". $nick_change . "\"where user_id = " . $user_id;
    $sql = $pdo->prepare($sql_q);
    $sql->execute();

    $msg = "<p>ニックネームの変更が完了致しました！<p>";

    if($_SESSION['login_user_nickname'] != $nick_change){
        $_SESSION['login_user_nickname'] = $nick_change;
    }
}

$nickname = $_SESSION['login_user_nickname'];
?>

<html lang="ja">
    <head>
        <meta name="viewport" content="width=device-width">
        <meta charset="utf-8">
        <title>ニックネーム変更 | ACE</title>
        <link href="css/t_style.css" rel="stylesheet">
        <link rel="icon" href="img/favi.ico">
    </head>

    <body>
        <div class = "header">
        <header class="page_header wrapper">
            <img src="img/logo_1.png" alt="logo" height="50" style="margin-top: 25px;"><p style="margin-top: 50px;">ニックネーム変更</p>
            <ul class="main_menu" style="<?php echo $default_css ?>">
                <li><a>こんにちは <?php echo $nickname ?> さん</a></li>
            </ul>
        </header>

        <header class="title wrapper">
            <a class="button" href="" onclick="window.close(); return false;" class="button">閉じる</a>
        </header>
        </div>

        <div class = "body wrapper">  
            <div class="content">
                <p><?php echo $msg ?></p> 
                <table>
                    <tbody>
                    <tr>
                        <th><a>現在のニックネーム : </a></th>
                        <td><a><?php name(); ?></a></td>
                    </tr>
                    <tr>
                        <form action="" method="post" name="form">
                        <th><a>変更のニックネーム : </a></th>
                        <td><a><input type="txt" name="nick_name" maxlength='30'require></a></td>
                    </tr>
                    <tr>
                        <td><a href="javascript:form.submit()" class="button" style="margin-left: 10px;">変更</a></td>
                        </form>
                    </tr>
                </tbody>
                </table>
            </div>
        </div>
    </body>

    <footer>
        <p><small>&copy;2021 プロジェクト実習G15</small></p>
    </footer>
</html>