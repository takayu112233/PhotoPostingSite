<?php
session_start();

$userid = "";
$nickname = "";

if(isset($_SESSION['login_user_id'])){
  $userid = $_SESSION['login_user_id'];
}else{
  header('Location: ./search_img.php');
}

require("libDB.php");

$preview_img= "";

ini_set('display_errors', "On");

function photo_save($jpg_base64,$user_id){
  
  $jpg_base64 = str_replace(' ' , '+' , $jpg_base64);
  $jpg_base64 = preg_replace('#^data:image/\w+;base64,#i' , '' , $jpg_base64);
  $data = base64_decode($jpg_base64);

  $mime_type = finfo_buffer(finfo_open(), $data, FILEINFO_MIME_TYPE);

  //var_dump($mime_type); 
  //echo $jpg_base64;

  $timestamp = time();
  $hased_string = hash('sha256', $timestamp);

  $extensions = [
    'image/gif' => 'gif',
    'image/jpeg' => 'jpg',
    'image/png' => 'png'
  ];

  $file_path = "./upload_img/kari_box/" . $user_id . "_" . $hased_string . "." . $extensions[$mime_type];
  file_put_contents($file_path, $data);
  $file_name = $user_id . "_" . $hased_string . "." . $extensions[$mime_type];

  return $file_name;
}

if(isset($_POST["image"])){
  $file_name = photo_save($_POST["image"],$userid);
  echo $file_name;
}else{
  echo "err";
}
?>