<!DOCTYPE html>
<?php 
setcookie("back_url","." . $_SERVER['REQUEST_URI'],time()+60*60);

session_start();

$nickname = "";
$login_style = "";
$logout_style = "display:none;";

if (isset($_SESSION['login_user_nickname'])){
    $nickname = $_SESSION['login_user_nickname'];
    $login_style = "display:none;";
    $logout_style = "";
}

$filter_genre_id = "-1";
$filter_prefecture_id = "-1";
$filter_keyword = "-1";
$filter_shop_id = "-1";

$sort_date = "-1";

if (isset($_GET['g'])){
    $filter_genre_id = $_GET['g'];
}

if (isset($_GET['p'])){
    $filter_prefecture_id = $_GET['p'];
}

if (isset($_GET['k'])){
    $filter_keyword = $_GET['k'];
}

if (isset($_GET['s'])){
    $filter_shop_id = $_GET['s'];
}

if (isset($_GET['sd'])){
    $sort_date = $_GET['sd'];
}

require("libDB.php");


function select_genre(){
    echo "\n" . "<!– php 処理 ここから –>" . "\n";
    $db = new libDB();
    $pdo = $db->getPDO();
    $sql = $pdo->prepare("select * from gerne");
    $sql->execute();
    $result = $sql->fetchAll();

    echo "<option value=\"0\">  ---  </option>";

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

    echo "<option value=\"0\">  ---  </option>";

    foreach($result as $loop){	 
        echo "<option value=\"" . $loop["prefecture_id"] . "\">" . $loop["prefecture_name"] .  "</option>\n";
    }
    echo "\n" . "<!– php 処理 ここまで –>" . "\n";
}

function add_where($sql_q,$filter_f,$column,$value){
    if(!$filter_f){
        $sql_q = $sql_q . " where ";
    }else{
        $sql_q = $sql_q . " and ";
    }
    $sql_q = $sql_q . $column . "=" . $value;
    return $sql_q;
}

function add_where_keyword($sql_q,$filter_f,$keyword){
    if(!$filter_f){
        $sql_q = $sql_q . " where ";
    }else{
        $sql_q = $sql_q . " and ";
    }
    $sql_q = $sql_q . " comment LIKE '%" . $keyword . "%'";
    $sql_q = $sql_q . " OR address LIKE '%" . $keyword . "%'";
    $sql_q = $sql_q . " OR shop_name LIKE '%" . $keyword . "%'";
    $sql_q = $sql_q . " OR prefecture_name LIKE '%" . $keyword . "%'";

    return $sql_q;
}

function order_by($sql_q,$column,$a_or_d){
    $sql_q = $sql_q . " order by " . $column . " ";
    if($a_or_d == "a") $sql_q = $sql_q . " asc ";
    if($a_or_d == "d") $sql_q = $sql_q . " desc ";
    return $sql_q;
}

function show_img()
{
    echo("\n<!– php 処理 開始 –>\n");

    $db = new libDB();
    $pdo = $db->getPDO();
    $sql_q = "select shop_name,photo_id,photo_url,prefecture_id,gerne_id from view_all_photo";

    $filter_f = false;
    global $filter_genre_id, $filter_prefecture_id, $filter_keyword, $sort_date, $filter_shop_id;

    if($filter_genre_id != "-1"){
        $sql_q = add_where($sql_q,$filter_f,"gerne_id",$filter_genre_id);
        $filter_f = true;
    }
    if($filter_prefecture_id != "-1"){
        $sql_q = add_where($sql_q,$filter_f,"prefecture_id",$filter_prefecture_id);
        $filter_f = true;
    }
    if($filter_keyword != "-1"){
        $keyword_arr = explode(" ", $filter_keyword);
        foreach($keyword_arr as $keyword){
            $sql_q = add_where_keyword($sql_q,$filter_f,$keyword);
            $filter_f = true;
          }
    }
    if($filter_shop_id != "-1"){
        $sql_q = add_where($sql_q,$filter_f,"shop_id",$filter_shop_id);
        $filter_f = true;
    }
    if($sort_date == "-1"){
        $sql_q = order_by($sql_q,"photo_id","d");
    }else{
        $sql_q = order_by($sql_q,"photo_id",$sort_date);
    }
    
    //echo "<span style=\"background-color:#00f;color:#fff;\">実行SQL文(デバック用):<br/> " . $sql_q . ";</span>";

    $sql = $pdo->prepare($sql_q);
    $sql->execute();
    $result = $sql->fetchAll();

    $cnt = count($result);

    echo"<div class=\"container\">"."\n";
    
    foreach($result as $loop){	 
        echo"<a href=\"" . "./detail.php?photoid=" . $loop["photo_id"] . "\" class=\"item\">"."\n";
        echo"<img src=\"./upload_img/resize/" . $loop["photo_url"] . "\">"."\n";
        echo"<div class=\"shop_name\">" . $loop["shop_name"] . "</div>"."\n";
        echo"</a>"."\n";
    }

    if($cnt == 0){
        echo("写真がありませんでした、フィルタ内容を確認してください</br>");
        echo("<a href=\"./search_img.php\" class=\"button b_filter\">絞り込みのリセット</a>");
    }else{
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
        <script>
        </script>
        <div class = "header">
            <header class="page_header wrapper">
                <h1>サービス名</h1>
                    <ul class="main_menu" style="<?php echo $logout_style ?>">
                        <li><a>こんにちは <?php echo $nickname ?> さん</a></li>
                        <li><a>写真検索</a></li>
                        <li><a href="./mypage.php">マイページ</a></li>
                        <li><a href="./shop_select.php">投稿</a></li>
                        <li><a href="./public/logout.php">ログアウト</a></li>
                    </ul>
                    <ul class="main_menu" style="<?php echo $login_style ?>">
                        <li><a>未ログイン状態です</a></li>
                        <li><a>写真検索</a></li>
                        <li><a href="./mypage.php">マイページ</a></li>
                        <li><a href="./shop_select.php">投稿する</a></li>
                        <li><a href="./public/login_form.php">ログイン</a></li>
                    </ul>
            </header>

            <header class="title wrapper">
                <a href="javascript:void(0)" class="button b_filter" onClick="filter_show();return false;">フィルタメニュー</a>
                <a href="javascript:void(0)" class="button b_sort" onClick="sort_show();return false;">ソートメニュー</a>
                <a id=filter_msg></a>
            </header>
        </div>

        <div class="img_show wrapper" id="img_show">
            <?php show_img(); ?>
        </div>

        <div class="wrapper" id="filter_panel">
            <a>フィルタ</a>
            <table class="shop_add_t">
                <tbody>
                    <tr>
                        <th><a>ジャンル</a></th>
                        <th><a>都道府県</a></th>
                        <th><a>キーワード</a></th>
                        <th></th>
                        <th></th>
               	    </tr>

                    <tr>
                        <td><select id="genre"><?php select_genre() ?></select></td>
                        <td><select id="prefecture"><?php select_prefecture() ?></select></td>
                        <td><a><input id ="keyword" type="text"></a></td>

                        <td><a href="javascript:void(0)" class="button b_filter" onClick="filter_and_sort_go();return false;">実行</a></td>
                        <td><a href="javascript:void(0)" class="button b_filter" onClick="filter_reset();return false;">リセット</a></td>
               	    </tr>
                </tbody>
            </table>
        </div>

        <div class="wrapper" id="sort_panel">
            <table class="sort_t">
                <tbody>
                    <tr>
                        <th><a>投稿日</a></th>
                        <th></th>
               	    </tr>
                    <td>
                        <select id="sort_date">
                            <option value="0">--</option>
                            <option value="d">新しい順</option>
                            <option value="a">古い順</option>
                        </select>
                    </td>
                    <td>
                        <td><a href="javascript:void(0)" class="button b_filter" onClick="filter_and_sort_go();return false;">実行</a></td>
                        <td><a href="javascript:void(0)" class="button b_filter" onClick="sort_reset();return false;">リセット</a></td>
                    </td>
                </tbody>
            </table>
        </div>

        <script>
            var filter_genre_id = "<?php echo $filter_genre_id; ?>";
            var filter_prefecture_id = "<?php echo $filter_prefecture_id; ?>";
            var filter_keyword = "<?php echo $filter_keyword; ?>";
            if(filter_genre_id != "-1") document.getElementById("genre").value = filter_genre_id;
            if(filter_prefecture_id != "-1") document.getElementById("prefecture").value = filter_prefecture_id;
            if(filter_keyword != "-1") document.getElementById("keyword").value = filter_keyword;

            var sort_date = "<?php echo $sort_date; ?>";
            if(sort_date != "-1") document.getElementById("sort_date").value = sort_date;
        </script>
        <script type="text/javascript" src="js/search_img.js"></script>
    </body>

    <footer>
        <p><small>&copy;2021 プロジェクト実習G15</small></p>
    </footer>
</html>