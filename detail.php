<?php
//$photoid = $_GET['photo_id'];

ini_set('display_errors', "On");
require("libDB.php");

if(isset($_GET["photoid"])){
    $photoid = $_GET["photoid"];
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
    $shop_name = $loop['shop_name']. "</br>";
    $prefecture = $loop['prefecture_name']. "</br>";
    $photo_url = "./upload_img/" . $loop['photo_url'];
    $comment = $loop['comment']. "</br>";
    }
?>


<html>
    <head>
        <meta charset="UTF-8">
        <title>詳細</title>
        <link rel="stylesheet" href="detail.css">
    </head>
    <body>
        <a href="javascript:history.back()">もどる</a><br>
        
        <a href="" class="btn btn-tag btn-tag--favorite">
            <i class="fas fa-star"></i>お気に入りに追加
        </a>

        <p><img src="<?php echo $photo_url ?>"></p>
        <p>ジャンル： <?php echo $gerne?></p>
        <p>店舗名: <?php echo $shop_name?></p>
        <p>住所: <?php echo $prefecture?></p>
        <p>コメント: <?php echo $comment?></p>

        <a onclick="location.href='./shop_select.php'" class="btn btn-flat"><span>写真追加</span></a>
    </body>
</html>