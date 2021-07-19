<!DOCTYPE html>
<?php 
session_start();

$redirect = 0;
$genre_name = "";
$shopname = "";
$ai_id = "-1";

$search_img_url = "./search_img.php";

if(isset($_COOKIE["search_parameter"])){
 $search_img_url = "./search_img.php?" . $_COOKIE["search_parameter"];
}

if (isset($_SESSION['login_user_nickname'])){
    $nickname = $_SESSION['login_user_nickname'];
}else{
    setcookie("back_url","./shop_select.php",time()+60*60);
    header('Location: ./public/login_form.php');
}

require("libDB.php");
$db = new libDB();
$pdo = $db->getPDO();

$msg = "";

function select_genre(){
    echo "\n" . "<!– php 処理 ここから –>" . "\n";
    $db = new libDB();
    $pdo = $db->getPDO();
    $sql = $pdo->prepare("select * from gerne");
    $sql->execute();
    $result = $sql->fetchAll();

    foreach($result as $loop){	 
        echo "<option value=\"" . $loop["gerne_id"] . "\">" . $loop["gerne_name"] .  "</option>\n";
    }
    echo "\n" . "<!– php 処理 ここまで –>" . "\n";
}

function select_prefecture(){
    echo "\n" . "<!– php 処理 ここから –>" . "\n";
    $db = new libDB();
    $pdo = $db->getPDO();
    $sql = $pdo->prepare("select * from prefectures");
    $sql->execute();
    $result = $sql->fetchAll();

    foreach($result as $loop){	 
        echo "<option value=\"" . $loop["prefecture_id"] . "\">" . $loop["prefecture_name"] .  "</option>\n";
    }
    echo "\n" . "<!– php 処理 ここまで –>" . "\n";
}

function show_shop($shop_result){
    echo "\n" . "<!– php 処理 ここから –>" . "\n";

    if(0 != count($shop_result)){
        foreach($shop_result as $loop){	 
            echo "<tr>";
            echo "<td><a>" . $loop["gerne_name"] . "</a></td>\n";
            echo "<td><a>" . $loop["prefecture_name"] . "</a></td>\n";
            echo "<td><a>" . $loop["address"] . "</a></td>\n";
            echo "<td><a>" . $loop["shop_name"] . "</a></td>\n";
            echo "<td><a href=\"javascript:void(0)\" class=\"button\" id=\"table_button\" onClick=\"shop_select(" . $loop["shop_id"] . ");return false;\">選択</a></td>\n";
            echo "</tr>";
        }
    }
    else
    {
        echo "<td colspan=\"1\"><a>データがありません</a></td>\n";
    }
    echo "\n" . "<!– php 処理 ここまで –>" . "\n";
}

if(isset($_POST["button"])){
    $btn_val = $_POST["button"];
    
    if($btn_val){
        $btn_val = htmlspecialchars($_POST["button"], ENT_QUOTES, "UTF-8");
        switch($btn_val){
            case "追加":

                $genre = htmlspecialchars($_POST["genre"], ENT_QUOTES, "UTF-8");
                $prefecture = htmlspecialchars($_POST["prefecture"], ENT_QUOTES, "UTF-8");
                $postcode = htmlspecialchars($_POST["postcode"], ENT_QUOTES, "UTF-8");
                $shopname = htmlspecialchars($_POST["shopname"], ENT_QUOTES, "UTF-8");
                $address = htmlspecialchars($_POST["address"], ENT_QUOTES, "UTF-8");

                $sql_q = "INSERT INTO shop(gerne_id,prefecture_id,shop_name,postcode,address) VALUES (" . $genre . "," . $prefecture . ",\"" . $shopname . "\"," . $postcode . ",\"" . $address . "\")";
                //echo $sql_q;

                $sql = $pdo->prepare($sql_q);
                $sql->execute();
                $msg = $shopname . " を追加しました";

                //$ai_id = $pdo->lastInsertId();
                //$redirect = 1;
                break;
            case "追加_g":
                $genre_name = htmlspecialchars($_POST["genre_name"], ENT_QUOTES, "UTF-8");

                $genre = htmlspecialchars($_POST["genre"], ENT_QUOTES, "UTF-8");
                $prefecture = htmlspecialchars($_POST["prefecture"], ENT_QUOTES, "UTF-8");
                $postcode = htmlspecialchars($_POST["postcode"], ENT_QUOTES, "UTF-8");
                $shopname = htmlspecialchars($_POST["shopname"], ENT_QUOTES, "UTF-8"); 
                $address = htmlspecialchars($_POST["address"], ENT_QUOTES, "UTF-8");
                $place_id = $_POST["place_id"];

                $sql_q = "INSERT INTO shop(gerne_id,prefecture_id,shop_name,postcode,address,place_id) VALUES (" . $genre . "," . $prefecture . ",\"" . $shopname . "\"," . $postcode . ",\"" . $address . "\",\"" . $place_id . "\")";
                //echo $sql_q;

                $sql = $pdo->prepare($sql_q);
                $sql->execute();
                $msg = $shopname . " を追加しました";

                $ai_id = $pdo->lastInsertId();
                $redirect = 1;
                break;
            default:
                break;
        }
    }
}

$sql = $pdo->prepare("select * from view_shop_select");
$sql->execute();
$shop_result = $sql->fetchAll();

$shop_table_json = json_encode($shop_result);
?>

<script src="js/ajaxzip3.js" charset="UTF-8"></script>
<script src="./js/jquery.min.js"></script>
<script src="js/shop_select.js" charset="UTF-8"></script>
<script type="text/javascript">
    var shop_data = <?php echo $shop_table_json; ?>;
</script>

<html lang="ja">
    <head>
        <meta name="viewport" content="width=device-width">
        <meta charset="utf-8">
        <title>投稿施設検索 | ACE</title>
        <link href="css/t_style.css" rel="stylesheet">
        <link rel="icon" href="img/favi.ico">
    </head>

    <body>
        <div class = "header">
        <header class="page_header wrapper">
            <a href="homepage.php"><img src="img/logo_1.png" alt="logo" height="50" style="margin-top: 25px;"></a><p id="m_display_none" style="margin-top: 50px;">写真追加</p>
            <ul class="main_menu" style="">
                <li id="m_display_none"><a>こんにちは <?php echo $nickname ?> さん</a></li>
                <li><a href="<?php echo $search_img_url ?>"><img src="./img/search.svg" width="32" height="32" title="検索"></a></li>
                <li><a href="./mypage.php"><img src="./img/mypage.svg" width="32" height="32" title="マイページ"></a></li>
                <li><a href="./shop_select.php"><img src="./img/post.svg" width="32" height="32" title="投稿" style="filter: drop-shadow(1px 1px 5px gold);"></a></li>
                <li><a href="./public/logout.php"><img src="./img/logout.svg" width="32" height="32" title="ログアウト"></a></li>
            </ul>
        </header>
        </div>

        <div class = "wrapper">
            <a href="<?php echo $search_img_url ?>" class="button">写真検索へ戻る</a>
            <a href="" class="button">店舗選択リセット</a>
        </div>
        
        <div class = "shop_add wrapper" id="shop_add">   
    	    <h2>施設検索</h2>
            <div class="content"> 
                <div id="make_shop_button">
                    <table>
                        <tbody>
                        <tr>
                            <td><a><input type="text" id="shopname_search" autocomplete="off" style="height: 30px;padding-top: 2px;" onkeypress="on_keyboard(event.keyCode);"></a></td>
                            <td><a href="javascript:void(0)" class="button" onClick="shop_search();return false;">検索</a></td>
               	        </tr>
                        </tbody>
                    </table>  
                </div>
                <a id = "system_msg"><?php echo $msg?></a>
            </div>
        </div>
        
        <div class="shop_select wrapper" id="shop_select">   
    	    <h2>施設選択（Google Map）</h2>

            <div class="content"> 
            <div id = search_result>撮影場所を検索してください</div>
            </div>
        </div>

        <div class = "select_genre wrapper" id="select_genre" style="display:none">   
    	    <h2>ジャンル選択</h2>
            <div class="content"> 
                <div id="select_genre_msg"></div>
                <select id="genre_google"><?php select_genre() ?></select>
                <td><a href="javascript:void(0)" class="button" onClick="shop_add_google();return false;">決定</a></td>
            </div>
        </div>

        <div class = "manual wrapper" style="display:none">   
    	    <h2>手動追加店舗</h2>

            <div class="content"> 
                <div id="shop_add_manual"> 
                    <table>
                    <tbody>
                    <tr>
                        <th><a>ジャンル</a></th>
                        <th><a>ショップ名</a></th>
                        <th><a>郵便番号</a></th>
                        <th><a>都道府県</a></th>
                        <th><a>住所</a></th>
               	    </tr>
                    <tr>
                        <td><select id="genre"><?php select_genre() ?></select></td>
                        <td><a><input type="text" id="shopname" autocomplete="off"></a></td>
                        <td><a><input type="text" id="postcode" size="10" maxlength="8" onKeyUp="AjaxZip3.zip2addr(this,'','prefecture','address');"></a></td>
                        <td><select id="prefecture" name="prefecture"><?php select_prefecture() ?></select></td>
                        <td><a><input type="text" name="address" id="address"></a></td>
                        <td><button onclick="shop_add();">追加</button></td>
               	    </tr>
                    </tbody>
                    </table>
                    <a id = "system_msg_manual"><?php echo $msg?></a>
                </div>
            </div>
        </div>

        <div class = "shop_select wrapper" style="display:none">   
    	    <h2>既存施設選択</h2>
            <div class="content"> 
                <div id = shop_db_show_button>
                    <td><a href="javascript:void(0)" class="button" onClick="shop_db_show();return false;">表示</a></td>
                </div>
                <div>
                <table class="shop_t" id="shop_t" style="display:none;">
                    <tbody>
                        <tr>
                            <th><a>ジャンル</a></th>
                            <th><a>都道府県</a></th>
                            <th><a>住所</a></th>
                            <th><a>名称</a></th>
                            <th><a></a></th>
               	        </tr>
                        <?php show_shop($shop_result) ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </body>

    <script type="text/javascript">
    var redirect = <?php echo $redirect; ?>;
    if(redirect == 1){
        var genre_name = "<?php echo $genre_name; ?>";
        var shop_name = "<?php echo $shopname; ?>";
        var ai_id = <?php echo $ai_id; ?>;
        
        post("img_post.php", {shop_id:ai_id,shop_name:shop_name,genre:genre_name});
    }
    document.getElementById("shopname_search").focus();
    </script>

    <footer>
        <p><small>&copy;2021 プロジェクト実習G15</small></p>
    </footer>
</html>