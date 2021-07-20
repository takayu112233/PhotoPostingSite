<!DOCTYPE html>
<?php 
session_start();

require("libDB.php");
$nickname = "";

$same_shop = false;

$default_css = "";

$msg = "";

$photoid = "9999";

if(isset($_GET["photoid"])){
    $photoid = $_GET["photoid"];
}

$nickname = "";
$logout_style = "display:none;";

if (isset($_SESSION['login_user_nickname'])){
    $nickname = $_SESSION['login_user_nickname'];
    $logout_style = "";
}

function show_bookmark($photoid){
    $db = new libDB();
    $pdo = $db->getPDO();
    $sql_q = "select * from view_bookmark_who where photo_id=" . $photoid;
    $sql = $pdo->prepare($sql_q);
    $sql->execute();
    $result = $sql->fetchAll();

    $cnt = 0;
    foreach($result as $loop){
        echo "<tr>";
            echo"<td><a>" . $loop["nickname"] . "</a></td>";
        echo"</tr>";
        $cnt = $cnt + 1;
    }
    if($cnt == 0){
        echo "<tr>";
            echo"<td><a>ブックマークに追加した人はいません</a></td>";
        echo"</tr>";
    }
}

?>

<html lang="ja">
    <head>
        <meta name="viewport" content="width=device-width">
        <meta charset="utf-8">
        <title>ブックマーク一覽 | ACE</title>
        <link href="css/t_style.css" rel="stylesheet">
        <link rel="icon" href="img/favi.ico">
    </head>

    <body>
        <div class = "header">
        <header class="page_header wrapper">
            <a><img src="img/logo_1.png" alt="logo" height="50" style="margin-top: 25px;"></a><p id="m_display_none" style="margin-top: 50px;">ブックマーク</p>
            <ul class="main_menu" style="<?php echo $logout_style ?>">
                <li><a>こんにちは <?php echo $nickname ?> さん</a></li>
            </ul>
        </header>

        <header class="title wrapper">
            <a class="button" href="" onclick="window.close()" class="button">閉じる</a>
        </header>
        </div>

        <div class = "body wrapper">  
            <h3>ブックマーク追加した人</h3>
            <div class="content">
                <table>
                    <tbody>
                        <?php show_bookmark($photoid); ?>
                    </tbody>
                </table>
            </div>
        </div> 

    </body>

    <footer>
        <p><small>&copy;2021 プロジェクト実習G15</small></p>
    </footer>
</html>