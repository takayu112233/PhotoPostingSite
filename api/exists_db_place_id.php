<?php
require("../libDB.php");
$db = new libDB();
$pdo = $db->getPDO();

$msg = "";
if (isset($_GET['place_id'])){

    $place_id = $_GET['place_id'];
    $place_id  = htmlspecialchars($place_id, ENT_QUOTES, "UTF-8");

    $db = new libDB();
    $pdo = $db->getPDO();
    $sql = $pdo->prepare("select * from view_shop_select where place_id = \"" . $place_id . "\"");
    $sql->execute();
    $result = $sql->fetchAll();

    $shop_id = -1;
    $shop_genre = -1;
    $shop_name = -1;
    $gerne_name = -1;

    $exisis = false;
    foreach($result as $loop){	   
        $exisis = true;
        $shop_id = $loop["shop_id"];
        $shop_name = $loop["shop_name"];
        $shop_genre = $loop["gerne_name"];
    }

    $array = array(
        "exisis" => $exisis,
        "shop_name" => $shop_name,
        "shop_id" => $shop_id,
        "gerne_name" => $shop_genre
    );

    $json = json_encode($array);
    echo $json;
}else{
    echo("err");
}
?>