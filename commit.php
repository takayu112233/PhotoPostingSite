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
  <meta name="viewport" content="width=device-width">
  <meta charset="utf-8">
  <title>???????????? | ACE</title>
  <link href="css/t_style.css" rel="stylesheet">
</head>

<body>
  <div class = "header">
  <header class="page_header wrapper">
      <a style="<?php echo $default_css ?>" href="homepage.php"><img src="img/logo_1.png" alt="logo" height="50" style="margin-top: 25px;"></a>
      <a style="<?php echo $simple_css ?>"><img src="img/logo_1.png" alt="logo" height="50" style="margin-top: 25px;"></a><p id="m_display_none" style="margin-top: 50px;">????????????</p>
      <ul class="main_menu" style="<?php echo $default_css ?>">
        <li id="m_display_none"><a>??????????????? <?php echo $nickname ?> ??????</a></li>
        <li><a href="<?php echo $search_img_url ?>"><img src="./img/search.svg" width="32" height="32" title="??????"></a></li>
        <li><a href="./mypage.php"><img src="./img/mypage.svg" width="32" height="32" title="???????????????"></a></li>
        <li><a href="./shop_select.php"><img src="./img/post.svg" width="32" height="32" title="??????" style="filter: drop-shadow(1px 1px 5px gold);"></a></li>
        <li><a href="./public/logout.php"><img src="./img/logout.svg" width="32" height="32" title="???????????????"></a></li>
      </ul>
      <ul class="main_menu" style="<?php echo $simple_css ?>">
        <li><a>??????????????? <?php echo $nickname ?> ??????</a></li>
      </ul>
  </header>

  <header class="title wrapper">
    <div style="<?php echo $default_css ?>">
      <a href="./shop_select.php" class="button">?????????????????????</a>
      <a href="<?php echo $search_img_url ?>" class="button">?????????????????????</a> 
    </div>  
      <div style="<?php echo $simple_css ?>">
        <a href="#" onClick="window.close(); return false;" class="button">?????????</a>
      </div>
  </div>

  <div class = "body wrapper">
    <h1>(??????) ?????????????????????</h1>

    <h2>?????????</h2>
      <div class="content"> 
        <table>
          <tbody>
            <tr>
              <th><a>????????????</a></th>
              <th><a>?????????</a></th>
            </tr>
            <tr>
              <td><a><?php echo $shop_genre; ?><a></td>
              <td><a><?php echo $shop_name; ?><a></td>
            </tr>
          </tbody>
        </table>
      </div>

    <h2>????????????????????????</h2>
    <div class="content"> 
      <p><img src="<?php echo $r_path ?>" style="max-width: 500px; width: 100%"><p>
    </div>

    <h2>????????????</h2>
    <div class="content"> 
      <p><?php echo $comment ?><p>
    </div>

  </div>
</body>
</html>