<!DOCTYPE html>
<?php 
session_start();

if (isset($_SESSION['login_user_nickname'])){
    $nickname = $_SESSION['login_user_nickname'];
    $login_style = "display:none;";
    $logout_style = "";
}else{
    header('Location: ./search_img.php');
}

require("libDB.php");

function show_img(){
    $db = new libDB();
    $pdo = $db->getPDO();
    $sql = $pdo->prepare("select * from view_all_photo");
    $sql->execute();
    $result = $sql->fetchAll();

    $cnt = 0;
    echo"<div class=\"container\">"."\n";

    foreach($result as $loop){
        if($_SESSION["login_user_id"] == $loop['user_id']){
            echo"<a href=\"" . "./detail.php?photoid=" . $loop["photo_id"] . "\" class=\"item\">"."\n";
            echo"<img src=\"./upload_img/resize/" . $loop["photo_url"] . "\">"."\n";
            echo"<div class=\"shop_name\">" . $loop["shop_name"] . "</div>"."\n";
            echo"</a>"."\n";
            $cnt++;
        }
    }

    if($cnt == 0){
        echo("<p>投稿はありません。</p>");
    }
    echo"</div>"."\n";
}

function favorite_img(){
    $db = new libDB();
    $pdo = $db->getPDO();
    $sql = $pdo->prepare("select * from view_favorite_photo");
    $sql->execute();
    $bookmark_result = $sql->fetchAll();

    $cnt = 0;

    echo"<div class=\"container\">"."\n";
    foreach($bookmark_result as $bookmark){
        if($_SESSION["login_user_id"] == $bookmark['user_id']){
            echo"<a href=\"" . "./detail.php?photoid=" . $bookmark["photo_id"] . "\" class=\"item\">"."\n";
            echo"<img src=\"./upload_img/resize/" . $bookmark["photo_url"] . "\">"."\n";
            echo"<div class=\"shop_name\">" . $bookmark["shop_name"] . "</div>"."\n";
            echo"</a>"."\n";
            $cnt++;
        }
    }

    if($cnt == 0){
        echo("<p>お気に入りはありません。</p>");
    }
    echo"</div>"."\n";
}
?>

<html lang="ja">
    <head>
        <meta name="viewport" content="width=device-width">
        <meta charset="utf-8">
        <title>マイページ</title>
        <link href="css/t_style.css" rel="stylesheet">
        <link rel="icon" href="img/favi.ico">
    </head>

    <body>
        <script>
        </script>
        <div class = "header">
            <header class="page_header wrapper">
                <h1>サービス名</h1>
                    <ul class="main_menu" style="<?php echo $logout_style ?>">
                        <li><a>こんにちは <?php echo $nickname ?> さん</a></li>
                        <li><a href="./search_img.php">写真検索</a></li>
                        <li><a>マイページ</a></li>
                        <li><a href="./shop_select.php">投稿</a></li>
                        <li><a href="./public/logout.php">ログアウト</a></li>
                    </ul>
            </header>
        </div>

        <div class="nickname_show wrapper" id="img_show">
            <p>ニックネーム： <?php echo $_SESSION["login_user_nickname"]?></p>
        </div>
        <div class="img_show wrapper" id="img_show">
            <h2>お気に入り</h2>
            <div class="post_images">
                <?php favorite_img(); ?>
            </div>

            <h2>投稿</h2>
            <div class="post_images">
                <?php show_img(); ?>
            </div>
        </div>
    </body>

    <footer>
        <p><small>&copy;2021 プロジェクト実習G15</small></p>
    </footer>
</html>