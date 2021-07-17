<!DOCTYPE html>
<?php 

session_start();

setcookie("back_url","../." . $_SERVER['REQUEST_URI'],time()+60*60);

$search_img_url = "./search_img.php";

if(isset($_COOKIE["search_parameter"])){
 $search_img_url = "./search_img.php?" . $_COOKIE["search_parameter"];
}

require("libDB.php");

$nickname = "";
$login_style = "";
$logout_style = "display:none;";

$f_func = "favorite_login";

$b_count = "--"; //ブックマーク数の表示　まだ、作ってないよ！

if (isset($_SESSION['login_user_nickname'])){
    $nickname = $_SESSION['login_user_nickname'];
    $login_style = "display:none;";
    $logout_style = "";
    $f_func = "favorite";
}

if(isset($_GET["photoid"])){
    $photoid = $_GET["photoid"];
}

$confirm = "display:none;";

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
        case "deleteFlag":
            $db = new libDB();
            $pdo = $db->getPDO();
            $sql_q = "update photo_data set delete_flag=\"". 1 . "\"where photo_id = " . $photoid;
            $sql = $pdo->prepare($sql_q);
            $sql->execute();
            //header('Location: ./public/mypage.php');
            header('Location: ./mypage.php');
            break;
        default:
            break;
    }
}

$db = new libDB();
$none_user = "display:none";
$f_add = "";
$f_del = "display:none;";

$f_add_url = "";

if (isset($_SESSION["login_user_id"])) {
    $pdo = $db->getPDO();
    $sql = $pdo->prepare("select * from bookmark where user_id = :user_id and photo_id = :photo_id");
    //SQL文への値の設定
    
    if (!isset($_SESSION["login_user_id"])) {
        $user_id = "";
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
            $f_del = " ";
            $f_add = "display:none;";

        }else if($_SESSION["login_user_id"] && !$bookmark['photo_id']){
            $f_del = "display:none;";
            $f_add = " ";
            $f_add_url = "";
        }
    }
}else{
    $f_del = "display:none;";
    $f_add = " ";
    $f_add_url = "login.php";
}


if(!isset($_SESSION["login_user_id"])){
    $s_add = "display:none";
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
    $photo_send_nickname = $loop['nickname']. "</br>";

    $r_comment = str_replace('&lt;br&gt;', '<br>', $comment);

    $bookmark_count = $loop['bookmark_count'];

    $a_gerne = $loop['gerne_name'];
    $a_shop_id = $loop['shop_id'];
    $a_shop_name = $loop['shop_name'];
    $a_prefecture = $loop['prefecture_name'].$loop['address'];

}

$pdo = $db->getPDO();
$sql = $pdo->prepare("select * from photo_data where photo_id = :photo_id");
$sql->bindValue(':photo_id', $photoid, PDO::PARAM_STR);
$sql->execute();
$photo_result = $sql->fetchAll();

$del = "display:none;";

if(isset($_SESSION["login_user_id"])){
    foreach($photo_result as $photo_loop){
        if($photo_loop["user_id"] == $_SESSION["login_user_id"]){
            $confirm = "display:none;";
            $del = "";
        }else if($photo_loop["user_id"] == $_SESSION["login_user_id"]){
            $confirm = "display:none;";
        }
    }
}

?>

<script type="text/javascript">
    function favorite(){
        post("", {action:"favorite"})
    }
    function favorite_login(){
        post("./public/login_form.php", {action:"favorite"})
    }
    function un_favorite(){
        post("", {action:"un_favorite"})
    }
    function deleteFlag(){
        post("", {action:"deleteFlag"})
    }
    function deleteButton(){
        //document.getElementById('delete').style.display="block";
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

function hidePopup(){
    document.getElementById('delete').style.display="none";
}

function deleteButton(){
    document.getElementById('delete').style.display="block";
}

function window_open(){
    window.open("about:blank","img_post.php","width=1000,height=700,scrollbars=yes");
    document.fdata.target = "img_post.php";
    document.fdata.method = "post";
    document.fdata.action = "img_post.php";
    document.fdata.submit();
}
</script>

<html lang="ja">
    <head>
        <meta name="viewport" content="width=device-width">
        <meta charset="utf-8">
        <title> <?php echo $a_shop_name?> | ACE</title>
        <link href="css/t_style.css" rel="stylesheet">
        <link href="css/detail.css" rel="stylesheet">
        <link rel="icon" href="img/favi.ico">
    </head>

    <body>
        <div class="confirm" id="delete" style="<?php echo $confirm ?>">
            <h1><?php echo $shop_name?></h1></br>
            <p>ジャンル： <?php echo $gerne?>住所: <?php echo $prefecture?>削除しますか？</p>
            <button onclick="hidePopup();" autofocus>閉じる</button>
            <button onclick="deleteFlag();return false;">削除</button>
        </div>

        <div class = "header">
            <header class="page_header wrapper">
                    <img src="img/logo_1.png" alt="logo" height="50" style="margin-top: 25px;"><p id="m_display_none" style="margin-top: 50px;">写真詳細表示</p>
                    <ul class="main_menu" style="<?php echo $logout_style ?>">
                        <li id="m_display_none"><a>こんにちは <?php echo $nickname ?> さん</a></li>
                        <li><a href="<?php echo $search_img_url ?>"><img src="./img/search.svg" width="32" height="32" title="検索"></a></li>
                        <li><a href="./mypage.php"><img src="./img/mypage.svg" width="32" height="32" title="マイページ"></a></li>
                        <li><a href="./shop_select.php"><img src="./img/post.svg" width="32" height="32" title="投稿"></a></li>
                        <li><a href="./public/logout.php"><img src="./img/logout.svg" width="32" height="32" title="ログアウト"></a></li>
                    </ul>
                    <ul class="main_menu" style="<?php echo $login_style ?>">
                        <li id="m_display_none"><a>未ログイン状態です</a></li>
                        <li><a href="<?php echo $search_img_url ?>"><img src="./img/search.svg" width="32" height="32" title="検索"></a></li>
                        <li><a href="./mypage.php"><img src="./img/mypage.svg" width="32" height="32" title="マイページ"></a></li>
                        <li><a href="./shop_select.php"><img src="./img/post.svg" width="32" height="32" title="投稿"></a></li>
                        <li><a href="./public/login_form.php"><img src="./img/login.svg" width="32" height="32" title="ログイン"></a></li>
                    </ul>
            </header>

            <header class="title wrapper">
                <a href="<?php echo $search_img_url ?>" class="button">写真検索に戻る</a>
                <a style="<?php echo $s_add ?>" input type="submit" class="button" onclick="window_open();">同じ場所の写真を追加</a>
                <a class="button" href="./search_img.php?s=<?php echo $a_shop_id?>" input type="submit" class="btn btn-flat">同じ場所の他の投稿を見る</a>
                <a style="<?php echo $f_add ?>" class="button" href="javascript:void(0)" onClick="<?php echo $f_func ?>();return false;">☆</a>
                <a style="<?php echo $f_del ?>" class="button" href="javascript:void(0)" onClick="un_favorite();return false;">⭐️</a>
                <a>📝⭐️x<?php echo $bookmark_count ?></a>
                <a style="<?php echo $del ?>" class="button" href="javascript:void(0)" onclick="deleteButton();">🗑</a>
            </header>
        </div>

        <form action="#" method="post" name="fdata" id="fdata">
            <input type="hidden" name="shop_id" value="<?php echo $a_shop_id ?>">
            <input type="hidden" name="shop_name" value="<?php echo $a_shop_name ?>">
            <input type="hidden" name="genre" value="<?php echo $a_gerne ?>">
            <input type="hidden" name="type" value="same_shop">
        </form>

        <div class = "wrapper">
        <h2>写真詳細</h2>
            <div class="content"> 
            <p><img class="detail_pic" src="<?php echo $photo_url ?>"></p>
            <p>ジャンル： <?php echo $gerne?></p>
            <p>店舗名: <?php echo $shop_name?></p>
            <p>住所: <?php echo $prefecture?></p>
            <p>コメント: <?php echo $r_comment?></p>
            <p>地図:</p>
            <iframe src="https://maps.google.co.jp/maps?output=embed&q=<?php echo $a_prefecture ?>+<?php echo $a_shop_name ?>" width="700" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
            <p>投稿者： <?php echo $photo_send_nickname ?></p>
        </br>
            </div>
        </div>
    </body>

    <footer>
        <p><small>&copy;2021 プロジェクト実習G15</small></p>
    </footer>
</html>