<?php
require("libDB.php");

$preview_img= "";

ini_set('display_errors', "On");

session_start();

$search_img_url = "./search_img.php";

if(isset($_COOKIE["search_parameter"])){
 $search_img_url = "./search_img.php?" . $_COOKIE["search_parameter"];
}

if (!isset($_SESSION['login_user_id'])) {
  setcookie("back_url","./shop_select.php",time()+60*60);
  header('Location: ./public/login_form.php');
}else{
  $nickname = $_SESSION['login_user_nickname'];
}

function photo_move($file_name)
{ 
  $moto_path = "./upload_img/kari_box/" . $file_name;
  $o_path = "./upload_img/" . $file_name;
  $r_path = "./upload_img/resize/" . $file_name;
  copy($moto_path, $o_path);
  copy($moto_path, $r_path);
  unlink($moto_path);
  return $r_path;
}

function insert_database($user_id,$file_path,$shop_id,$comment){
    $db = new libDB();
    $pdo = $db->getPDO();
    
    $shop_id = $shop_id;
    $photo_url = $file_path;
    
    $comment = str_replace(array("\r\n", "\r", "\n"), "<br>", $comment);
    $comment = htmlspecialchars($comment);

    $sql_q = "INSERT INTO photo_data(user_id,shop_id,photo_url,comment) VALUES (". $user_id . "," . $shop_id . ",\"" . $photo_url . "\",\"" . $comment . "\")";
    //echo $sql_q;

    $sql = $pdo->prepare($sql_q);
    $sql->execute();
}

if(isset($_POST["filename"])){
  $type = $_POST["type"];

  $file_name = $_POST["filename"];
  $r_path = photo_move($_POST["filename"]);

  $comment = htmlspecialchars($_POST["comment"], ENT_QUOTES, "UTF-8");
  $comment = str_replace(array("\r\n", "\r", "\n"), "<br>", $comment);

  $shop_name = $_POST["shop_name"];
  $shop_genre = $_POST["shop_genre"];

  $user_id = $_SESSION['login_user_id'];
  $shop_id = $_POST["shop_id"];
  insert_database($user_id,$file_name,$shop_id,$comment);
}else{
    header('Location: ./shop_select.php');
}

$simple_css = "display:none";
$default_css = "";

if($type == "simple"){
    $default_css = "display:none";
    $simple_css = "";
    $type = "simple";
}



?>
<!doctype html>

<html lang="ja">
<head>
  <meta charset="utf-8">
  <title>投稿完了 | ACE</title>
  <link href="css/t_style.css" rel="stylesheet">
</head>

<body>
  <div class = "header">
  <header class="page_header wrapper">
      <img src="img/logo_1.png" alt="logo" height="50" style="margin-top: 25px;"><p style="margin-top: 50px;">写真追加</p>
      <ul class="main_menu" style="<?php echo $default_css ?>">
        <li><a>こんにちは <?php echo $nickname ?> さん</a></li>
        <li><a href="<?php echo $search_img_url ?>">写真検索</a></li>
        <li><a href="./mypage.php">マイページ</a></li>
        <li><a>投稿</a></li>
        <li><a href="./public/logout.php">ログアウト</a></li>
      </ul>
      <ul class="main_menu" style="<?php echo $simple_css ?>">
        <li><a>こんにちは <?php echo $nickname ?> さん</a></li>
      </ul>
  </header>
  </div>

  <div class = "wrapper">
    <div style="<?php echo $default_css ?>">
      <a href="./shop_select.php" class="button">店舗選択へ戻る</a>
      <a href="<?php echo $search_img_url ?>" class="button">写真検索へ戻る</a> 
    </div>  
      <div style="<?php echo $simple_css ?>">
        <a href="#" onClick="window.close(); return false;" class="button">閉じる</a>
      </div>
    <h1>(投稿) 投稿しました！</h1>

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

    <h2>アップロード画像</h2>
    <div class="content"> 
      <p>アップロード画像<br><img src="<?php echo $r_path ?>" style="width: 200px"><p>
      <p>コメント<br><?php echo $comment ?><p>
    </div>

  </div>
</body>
</html>