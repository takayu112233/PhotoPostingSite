<?php
session_start();
require("libDB.php");

setcookie("back_url","./mypage.php",time()+60*60);

$nickname = "";

if (isset($_SESSION['login_user_nickname'])){
    $nickname = $_SESSION['login_user_nickname'];
}

if(isset($_GET["photoid"])){
    $photoid = $_GET["photoid"];
}

if(isset($_POST["action"])){
    $action = $_POST["action"];
    $action = htmlspecialchars($_POST["action"], ENT_QUOTES, "UTF-8");
    
    switch($action){
        case "favorite":
            $db = new libDB();
            $pdo = $db->getPDO();
            $sql = $pdo->prepare("insert into bookmark(user_id,photo_id) values(" . $_SESSION["login_user_id"] . "," . $photoid . ")");
            $sql->execute();
            break;
        case "un_favorite": 
            $db = new libDB();
            $pdo = $db->getPDO();
            $sql_q = "delete from bookmark where user_id = "  . $_SESSION["login_user_id"] ." and photo_id = "  . $photoid ;
            $sql = $pdo->prepare($sql_q);
            $sql->execute();
            break;
        default:
            break;
    }
}

//$photoid = 1;
//$_SESSION["login_user_id"] = "1";

//セッションを消す↓
//$_SESSION = array();

$db = new libDB();

$none_user = "display:none";
$f_add = "display:block;";
$f_del = "display:none;";

$f_add_url = "";

if (isset($_SESSION["login_user_id"])) {
    $pdo = $db->getPDO();
    $sql = $pdo->prepare("select * from bookmark where user_id = :user_id and photo_id = :photo_id");
    //SQL文への値の設定
    
    if (!isset($_SESSION["login_user_id"])) {
        $user_id = " ";
    }else{
        $user_id = $_SESSION["login_user_id"];
    }
    
    $sql->bindValue(':user_id', $user_id, PDO::PARAM_STR);
    $sql->bindValue(':photo_id', $photoid, PDO::PARAM_STR);
    //SQL文の実行
    $sql->execute();
    //結果の取得
    $bookmark_result = $sql->fetchAll();

    foreach($bookmark_result as $bookmark){
        $photo_id = $bookmark['photo_id'];
    
        if($_SESSION["login_user_id"] && $bookmark['photo_id']){
            $f_del = "display:block;";
            $f_add = "display:none;";

        }else if($_SESSION["login_user_id"] && !$bookmark['photo_id']){
            $f_del = "display:none;";
            $f_add = "display:block;";
            $f_add_url = "";
        }
    }
}else{
    $f_del = "display:none;";
    $f_add = "display:block;";
    $f_add_url = "login.php";
}


$db = new libDB();
$pdo = $db->getPDO();
$sql = $pdo->prepare("select * from view_photo_details where photo_id = :photo_id");
//SQL文への値の設定
$sql->bindValue(':photo_id', $photoid, PDO::PARAM_STR);
//SQL文の実行
$sql->execute();
//結果の取得
$result = $sql->fetchAll();

foreach($result as $loop){
    $gerne = $loop['gerne_name']. "</br>";
    $shop_id = $loop['shop_id']."</br>";
    $shop_name = $loop['shop_name']. "</br>";
    $prefecture = $loop['prefecture_name']."\t".$loop['address']."</br>";
    $photo_url = "./upload_img/" . $loop['photo_url'];
    $comment = $loop['comment']. "</br>";


    $a_gerne = $loop['gerne_name'];
    $a_shop_id = $loop['shop_id'];
    $a_shop_name = $loop['shop_name'];
    $a_prefecture = $loop['prefecture_name'].$loop['address'];
}

?>


<html>
    <head>
        <meta charset="UTF-8">
        <title>詳細</title>
        <link rel="stylesheet" href="detail.css">
    </head>
    <body>
        <script type="text/javascript">
            function favorite(){
                post("", {action:"favorite"})
            }
            function un_favorite(){
                post("", {action:"un_favorite"})
            }
            function post(path, params, method='post') {
                const form = document.createElement('form');
                form.method = method;
                form.action = path;

                for (const key in params) {
                    if (params.hasOwnProperty(key)) {
                        const hiddenField = document.createElement('input');
                        hiddenField.type = 'hidden';
                        hiddenField.name = key;
                        hiddenField.value = params[key];

                        form.appendChild(hiddenField);
                    }
                }

                document.body.appendChild(form);
                form.submit();
            }

        </script>


        <a href="javascript:history.back()">写真検索に戻る</a><br>

        <div class = "header">
            <header class="page_header wrapper">
                <h1>サービス名</h1>
                    <ul class="main_menu" style="<?php echo $logout_style ?>">
                        <li><a>こんにちは <?php echo $nickname ?> さん</a></li>
                        <li><a href="./search_img.php">写真検索</a></li>
                        <li><a href="./mypage.php">マイページ</a></li>
                        <li><a href="./shop_select.php">投稿</a></li>
                        <li><a href="./public/logout.php">ログアウト</a></li>
                    </ul>
            </header>
        </div>
        
        <div style="<?php echo $f_add ?>"> 
            <a href="javascript:void(0)" class="btn btn-tag btn-tag--favorite" onClick="favorite();return false;">
                <i class="fas fa-star"></i>お気に入り追加
            </a>
        </div>

        <div style="<?php echo $f_del ?>">
            <a href="javascript:void(0)" class="btn btn-tag btn-tag--favorite" onClick="un_favorite();return false;">
                <i class="fas fa-star"></i>お気に入り解除
            </a>
        </div>

        <p><img src="<?php echo $photo_url ?>"></p>
        <p>ジャンル： <?php echo $gerne?></p>
        <p>店舗名: <?php echo $shop_name?></p>
        <p>住所: <?php echo $prefecture?></p>
        <p>コメント: <?php echo $comment?></p>
        <p>地図:</p>
        <iframe src="https://maps.google.co.jp/maps?output=embed&q=<?php echo $a_prefecture?>+<?php echo $shop_name?>" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        </br>

        <a onclick="location.href='./shop_select.php'" class="btn btn-flat">
            <span>新しい店の写真追加</span>
        </a>
        </br>
        <a href="javascript:void(0)" class="btn btn-flat" onclick="add_photo();return false;">
            <span>同じ店の写真を追加</span>
        </a>


        <script type="text/javascript">
            function add_photo(){
                var w = ( screen.width-640 ) / 2;
                var h = ( screen.height-480 ) / 2;

        
                var shop_id = <?php echo $a_shop_id?>;
                var shop_name = "<?php echo $a_shop_name ?>";
                var genre = "<?php echo $a_gerne  ?>"; 

                post("./img_post.php", {shop_id:shop_id,shop_name:shop_name,genre:genre});
            }
        </script>

        <footer>
            <p><small>&copy;2021 プロジェクト実習G15</small></p>
        </footer>

    </body>
</html>