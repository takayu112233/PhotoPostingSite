<!DOCTYPE html>
<?php 
session_start();

$_SESSION["user_id"] = 1;
$_SESSION["nickname"] = "のすけ";

if (!$_SESSION["user_id"]) {
    header('Location: ./login.php');
}

require("libDB.php");

function show_img()
{
    echo("\n<!– php 処理 開始 –>\n");

    $db = new libDB();
    $pdo = $db->getPDO();
    $sql = $pdo->prepare("select * from view_all_photo");
    $sql->execute();
    $result = $sql->fetchAll();

    echo"<div class=\"container\">"."\n";
    foreach($result as $loop){	 
        echo"<a href=\"" . "./detail.php?photoid=" . $loop["photo_id"] . "\" class=\"item\">"."\n";
        echo"<img src=\"./upload_img/resize/" . $loop["photo_url"] . "\">"."\n";
        echo"<div class=\"shop_name\">" . $loop["shop_name"] . "</div>"."\n";
        echo"</a>"."\n";
    }
    echo"</div>"."\n";
    echo("\n<!– php 処理 終了 –>\n");
}
?>

<html lang="ja">
    <head>
        <meta name="viewport" content="width=device-width">
        <meta charset="utf-8">
        <title>サービス名</title>
        <link href="css/t_style.css" rel="stylesheet">
        <link rel="icon" href="img/favi.ico">
    </head>

    <body>
        <div class = "header">
            <header class="page_header wrapper">
                <h1>サービス名</h1>
                <ul class="main_menu">
                    <li><a>ようこそ <?php echo $_SESSION["nickname"] ?> さん</a></li>
                    <li><a href="./shop_select.php">投稿する</a></li>
                    <li><a href="./logout.php">ログアウト</a></li>
                </ul>
            </header>

            <header class="title wrapper">
                <p>ここで写真の絞り込みを行います</p>
            </header>
        </div>

        <div class="img_show wrapper">
            <?php show_img(); ?>
        </div>
    </body>

    <footer>
        <p><small>&copy;2021 プロジェクト実習G15</small></p>
    </footer>
</html>