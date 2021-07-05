<!DOCTYPE html>
<?php
session_start();

$userid = "";
$nickname = "";

if(isset($_SESSION['login_user_nickname'])){
  $nickname = $_SESSION['login_user_nickname'];
  $userid = $_SESSION['login_user_id'];
}else{
  header('Location: ./search_img.php');
}

//echo $userid;

require("libDB.php");

$preview_img= "";

ini_set('display_errors', "On");

function photo_resize($raw_file_path,$resize_file_path){
  list($raw_width, $raw_hight) = getimagesize($raw_file_path);
  $resize_max_size = 1080;
  if($raw_width <= $raw_hight) {
    $resize_hight = 1080;
    $resize_width = $raw_width * $resize_hight / $raw_hight;
  }else{
    $resize_width = 1080;
    $resize_hight = $raw_hight * $resize_width / $raw_width;
  }

  $raw_image = imagecreatefromjpeg($raw_file_path);
  $image = imagecreatetruecolor($resize_width, $resize_hight);
  imagecopyresampled($image, $raw_image, 0, 0, 0, 0, $resize_width, $resize_hight, $raw_width, $raw_hight);
  imagejpeg($image , $resize_file_path);

  //echo($resize_file_path . "に圧縮しました<br>");
  //echo("元の画像の大きさは 幅:" . $raw_width . "px 高さ" . $raw_hight . "pxです<br>");
  //echo("圧縮した画像の大きさは 幅:" . $resize_width  . "px 高さ" . $resize_hight . "pxです<br>");
}

function png_to_jpg($raw_file_path,$jpg_file_path){
  $image = @imagecreatefrompng($raw_file_path);
  imagejpeg($image, $jpg_file_path);
  imagedestroy($image);
  unlink($raw_file_path);
  //echo($jpg_file_path . "にjpg保存しました<br>");
  //echo($raw_file_path . "は削除しました<br>");
}

function photo_save($file,$userid)
{ 
  //echo("写真がアップロードされました。<br>");
  $original_file_name = $file["name"];
  $dot_cut_arr = explode(".",$original_file_name);
  $extension = $dot_cut_arr[intval(count($dot_cut_arr)) - 1];

  $timestamp = time();
  $hased_string = hash('sha256', $timestamp);

  if($extension == "JPG"){
    $extension = "jpg";
  }
  $file_path = "./upload_img/" . $userid . "_" . $hased_string . "." . $extension;

  move_uploaded_file($file['tmp_name'], $file_path);
  //echo($file['tmp_name'] . "<br>");
  //echo($file_path . "<br>");

  if($extension == "png" or $extension == "PNG"){
    $jpg_file_path = "./upload_img/" . $userid . "_" . $hased_string . ".jpg";
    png_to_jpg($file_path,$jpg_file_path);
    $file_path = $jpg_file_path;
    $resize_file_path = "./upload_img/resize/" . $userid . "_" . $hased_string . ".jpg";
    $database_file_path = $userid . "_" . $hased_string . ".jpg";
  }
  if($extension == "jpg" or $extension == "JPG"){
    $resize_file_path = "./upload_img/resize/" . $userid . "_" . $hased_string . "." . "jpg";
    $database_file_path = $userid . "_" . $hased_string . "." . "jpg";
  }
  photo_resize($file_path,$resize_file_path);

  insert_database($userid, $database_file_path, $_POST["shop_id"], $_POST["comment"]);

  global $preview_img;
  $preview_img = $database_file_path;
}

function insert_database($userid,$file_path,$shop_id,$comment){
    $db = new libDB();
    $pdo = $db->getPDO();
    
    $shop_id = $shop_id;
    $photo_url = $file_path;
    
    $comment = str_replace(array("\r\n", "\r", "\n"), "<br>", $comment);
    $comment = htmlspecialchars($comment);

    $sql_q = "INSERT INTO photo_data(user_id,shop_id,photo_url,comment) VALUES (". $userid . "," . $shop_id . ",\"" . $photo_url . "\",\"" . $comment . "\")";
    //echo $sql_q;

    $sql = $pdo->prepare($sql_q);
    $sql->execute();
}

if(isset($_FILES["image"])){
  photo_save($_FILES["image"],$userid);

  $comment = htmlspecialchars($_POST["comment"], ENT_QUOTES, "UTF-8");
  $comment = str_replace(array("\r\n", "\r", "\n"), "<br>", $comment);

  $shop_name = $_POST["shop_name"];
  $shop_genre = $_POST["genre"];
}else{
    header('Location: ./shop_select.php');
}



?>
<!doctype html>

<html lang="ja">
    <head>
        <meta name="viewport" content="width=device-width">
        <meta charset="utf-8">
        <title>投稿しました！</title>
        <link href="css/t_style.css" rel="stylesheet">
        <link rel="icon" href="img/favi.ico">
    </head>

    <body>
        <div class = "header">
            <header class="page_header wrapper">
                <h1>投稿</h1>
                <ul class="main_menu">
                    <li><a>ようこそ <?php echo $userid ?> さん</a></li>
                    <li><a href="./search_img.php">写真検索</a></li>
                    <li><a href="./logout.php">ログアウト</a></li>
                </ul>
            </header>
        </div>
        <div class = "wrapper">  
            <a href="./shop_select.php" class="button">店舗選択へ戻る</a>
            <a href="./search_img.php" class="button">写真検索へ戻る</a> 
            
    	    <h1>投稿しました！</h1>

    	    <h2>店情報</h2>
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

    	    <h2>アップロード画像</h2>
          <p><img src="./upload_img/resize/<?php echo $preview_img ?>" height="130"><p>

    	    <h2>コメント</h2>
          <p><?php echo $comment ?><p>

        </div>
        
    </body>

    <footer>
        <p><small>&copy;2021 プロジェクト実習G15</small></p>
    </footer>
</html>