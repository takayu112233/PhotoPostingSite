<!DOCTYPE html>
<?php 
session_start();

$nickname = "";

$same_shop = false;

$search_img_url = "./search_img.php";

if(isset($_COOKIE["search_parameter"])){
 $search_img_url = "./search_img.php?" . $_COOKIE["search_parameter"];
}

if (isset($_SESSION['login_user_nickname'])){
    $nickname = $_SESSION['login_user_nickname'];
    $shop_id = $_POST["shop_id"];
    $shop_name = $_POST["shop_name"];
    $shop_genre = $_POST["genre"];

    if(isset($_POST['type'])){
        if($_POST['type'] == "same_shop"){
            $same_shop = true;
        }
    }
}else{
    header('Location: ./search_img.php');
}

$simple_css = "display:none";
$default_css = "";
$type = "";

if($same_shop){
    $default_css = "display:none";
    $simple_css = "";
    $type = "simple";
}
?>

<html lang="ja">
    <head>
        <meta name="viewport" content="width=device-width">
        <meta charset="utf-8">
        <title>投稿内容編集 | ACE</title>
        <link href="css/t_style.css" rel="stylesheet">
        <link rel="icon" href="img/favi.ico">
    </head>

    <body>
        <div class = "header">
        <header class="page_header wrapper">
            <a style="<?php echo $default_css ?>" href="homepage.php"><img src="img/logo_1.png" alt="logo" height="50" style="margin-top: 25px;"></a>
            <a style="<?php echo $simple_css ?>"><img src="img/logo_1.png" alt="logo" height="50" style="margin-top: 25px;"></a><p id="m_display_none" style="margin-top: 50px;">写真追加</p>
            <ul class="main_menu" style="<?php echo $default_css ?>">
                <li id="m_display_none"><a>こんにちは <?php echo $nickname ?> さん</a></li>
                <li><a href="<?php echo $search_img_url ?>"><img src="./img/search.svg" width="32" height="32" title="検索"></a></li>
                <li><a href="./mypage.php"><img src="./img/mypage.svg" width="32" height="32" title="マイページ"></a></li>
                <li><a href="./shop_select.php"><img src="./img/post.svg" width="32" height="32" title="投稿" style="filter: drop-shadow(1px 1px 5px gold);"></a></li>
                <li><a href="./public/logout.php"><img src="./img/logout.svg" width="32" height="32" title="ログアウト"></a></li>
            </ul>
            <ul class="main_menu" style="<?php echo $simple_css ?>">
                <li><a>こんにちは <?php echo $nickname ?> さん</a></li>
            </ul>
        </header>

        <header class="title wrapper">
            <div style="<?php echo $default_css ?>">
                <a href="<?php echo $search_img_url ?>" class="button">店舗選択へ戻る</a>
                <a href="./search_img.php" class="button">写真検索へ戻る</a> 
            </div>  
            <div style="<?php echo $simple_css ?>">
                <a href="#" onClick="window.close(); return false;" class="button">閉じる</a>
            </div>
        </header>
        </div>

        <div class = "body wrapper">  
            
    	    <h2>店情報</h2>
            <div class="content"> 
                <table>
                    <tbody>
                    <tr>
                        <th><a>ジャンル</a></th>
                        <th><a>店舗名</a></th>
                    </tr>
                    <tr>
                        <td><a><?php echo $shop_genre; ?><a></td>
                        <td><a><?php echo $shop_name; ?><a></td>
                    </tr>
                </tbody>
                </table>
            </div>

            <form action="img_save.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="shop_id" value="<?php echo $shop_id; ?>">
                <input type="hidden" name="shop_name" value="<?php echo $shop_name; ?>">
                <input type="hidden" name="genre" value="<?php echo $shop_genre; ?>">   
                 
    	        <h2>アップロード画像</h2>
                <div class="content"> 
                    <p>(JPGかPNGを指定してください)</p>
                    <input type="file" name="image" id="image" accept="image/jpeg, image/png"></p>
                    <canvas style="display: none" id="img_canvas" width="400" height="300"></canvas>
                    <div id="preview_img"></div>
                    <p id="system_msg"><p>
                </div>
                <h2>コメント</h2>

                <div class="content"> 
                    <textarea id="comment" name="comment" wrap="hard"></textarea><p>
                    <a href="javascript:void(0)" class="button" onClick="commit();return false;">送信する</a>
                </div>
            </form>
        </div>
           
        <script src="./js/jquery.min.js"></script>
        <script src="./js/img_post.js"></script>  
        <script>
            shop_name = "<?php echo $shop_name ?>";
            shop_genre = "<?php echo $shop_genre ?>";
            shop_id = <?php echo $shop_id ?>;
            type = "<?php echo $type ?>";

            document.getElementById("comment").focus();
    </script>
        </script> 

    </body>

    <footer>
        <p><small>&copy;2021 プロジェクト実習G15</small></p>
    </footer>
</html>