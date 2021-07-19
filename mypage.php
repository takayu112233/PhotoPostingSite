<!DOCTYPE html>
<?php 
session_start();

$search_img_url = "./search_img.php";

if(isset($_COOKIE["search_parameter"])){
 $search_img_url = "./search_img.php?" . $_COOKIE["search_parameter"];
}

if (isset($_SESSION['login_user_nickname'])){
    $nickname = $_SESSION['login_user_nickname'];
    $login_style = "display:none;";
    $logout_style = "";
}else{
    setcookie("back_url","./mypage.php",time()+60*60);
    header('Location: ./public/login_form.php');
}

require("libDB.php");

function show_img(){
    $db = new libDB();
    $pdo = $db->getPDO();
    $sql = $pdo->prepare("select * from view_all_photo order by photo_id desc");
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
        <title>マイページ | ACE</title>
        <link href="css/t_style.css" rel="stylesheet">
        <link rel="icon" href="img/favi.ico">
    </head>

    <body>
        <script>
        </script>
        <div class = "header">
            <header class="page_header wrapper">
                    <a href="homepage.php"><img src="img/logo_1.png" alt="logo" height="50" style="margin-top: 25px;"></a><p id="m_display_none" style="margin-top: 50px;">マイページ</p>
                    <ul class="main_menu" style="<?php echo $logout_style ?>">
                        <li id="m_display_none"><a>こんにちは <?php echo $nickname ?> さん</a></li>
                        <li id="m_display_none"><a href="javascript:void(0)" onclick="winCenter()">ニックネーム変更</a></li>
                        <li><a href="<?php echo $search_img_url ?>"><img src="./img/search.svg" width="32" height="32" title="検索"></a></li>
                        <li><a href="./mypage.php"><img src="./img/mypage.svg" width="32" height="32" title="マイページ" style="filter: drop-shadow(1px 1px 5px gold);"></a></li>
                        <li><a href="./shop_select.php"><img src="./img/post.svg" width="32" height="32" title="投稿"></a></li>
                        <li><a href="./public/logout.php"><img src="./img/logout.svg" width="32" height="32" title="ログアウト"></a></li>
                    </ul>
            </header>
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

        <script>
        function winCenter(){
  
        //別窓の左と上の余白を求める
        var w = ( screen.width-1000 ) / 2;
        var h = ( screen.height-700 ) / 2;


        //オプションパラメーターleftとtopに余白の値を指定
        window.open("./nickname_change.php","","width=1000,height=700,scrollbars=yes" + ",left=" + w + ",top=" + h);
        }
        </script>
    </body>

    <footer>
        <p><small>&copy;2021 プロジェクト実習G15</small></p>
    </footer>
</html>