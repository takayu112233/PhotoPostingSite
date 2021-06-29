<!DOCTYPE html>
<?php 
session_start();


require("libDB.php");
$db = new libDB();
$pdo = $db->getPDO();

$msg = "登録ボタンを押してください";

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
            echo "<td><button onclick=\"shop_select(" . $loop["shop_id"] . ");\">選択</button></td>\n";
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
                echo $sql_q;

                $sql = $pdo->prepare($sql_q);
                $sql->execute();
                $msg = $shopname . " を追加しました";
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
<script type="text/javascript">
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

    function shop_add(){
        document.getElementById("system_msg").innerHTML = "実行";

        var genre = document.getElementById("genre").value; 
        var prefecture = document.getElementById("prefecture").value; 
        var shopname = document.getElementById("shopname").value; 
        var postcode = document.getElementById("postcode").value; 
        var address = document.getElementById("address").value; 
        post("", {button:"追加","genre":genre,"prefecture":prefecture,"shopname":shopname,"postcode":postcode,"address":address})
    }


    function shop_select(select_shop_id){
        var shop_data = <?php echo $shop_table_json; ?>;

        for(var i=0 ; i<shop_data.length ; i++){
            if(shop_data[i].shop_id == select_shop_id){
                var shop_id = shop_data[i].shop_id; 
                var shop_name = shop_data[i].shop_name; 
                var genre = shop_data[i].gerne_name; 

                post("img_post.php", {shop_id:shop_id,shop_name:shop_name,genre:genre});
            }
        }
    }
</script>

<html lang="ja">
    <head>
        <meta name="viewport" content="width=device-width">
        <meta charset="utf-8">
        <title>ショップ選択</title>
        <link href="css/t_style.css" rel="stylesheet">
        <link rel="icon" href="img/favi.ico">
    </head>

    <body>
        <div class = "header">
            <header class="page_header wrapper">
                <h1>投稿</h1>
                <ul class="main_menu">
                    <li><a>ようこそ <?php echo $_SESSION["nickname"] ?> さん</a></li>
                    <li><a href="./search_img.php">写真検索</a></li>
                    <li><a href="./logout.php">ログアウト</a></li>
                </ul>
            </header>
        </div>
        <div class = "wrapper">
            <a href="./search_img.php" class="button">写真検索へ戻る</a>
        </div>
        <div class = "shop_add wrapper">   
    	    <h2>ショップ追加</h2>
            <table class="shop_add_t">
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
                        <td><a><input type="text" id="shopname"></a></td>
                        <td><a><input type="text" id="postcode" size="10" maxlength="8" onKeyUp="AjaxZip3.zip2addr(this,'','prefecture','address');"></a></td>
                        <td><select id="prefecture" name="prefecture"><?php select_prefecture() ?></select></td>
                        <td><a><input type="text" name="address" id="address"></a></td>
                        <td><button onclick="shop_add();">追加</button></td>
               	    </tr>
                    <tr>
                        <td colspan="4"><a id = "system_msg"><?php echo $msg?></a></td>
               	    </tr>
                </tbody>
            </table>
        </div>
        

        <div class = "shop_select wrapper">   
    	    <h2>ショップ選択</h2>
            <table class="shop_t">
                <tbody>
                    <tr>
                        <th><a>ジャンル</a></th>
                        <th><a>都道府県</a></th>
                        <th><a>住所</a></th>
                        <th><a>ショップ名</a></th>
                        <th><a></a></th>
               	    </tr>

                    <?php show_shop($shop_result) ?>
                </tbody>
            </table>
        </div>

    </body>

    <footer>
        <p><small>&copy;2021 プロジェクト実習G15</small></p>
    </footer>
</html>