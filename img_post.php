<!DOCTYPE html>
<?php 
session_start();

if (!$_SESSION["user_id"]) {
    //ホームへ戻る
  }
  
  if(isset($_FILES["image"])){
    photo_save($_FILES["image"],$_SESSION["user_id"]);
  }else{
    if(isset($_POST["shop_id"])){
      $shop_id = $_POST["shop_id"];
      $shop_name = $_POST["shop_name"];
      $shop_genre = $_POST["genre"];
    }else{
      header('Location: ./shop_select.php');
    }
  }

?>

<script type="text/javascript">

</script>

<html lang="ja">
    <head>
        <meta name="viewport" content="width=device-width">
        <meta charset="utf-8">
        <title>投稿画面</title>
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
            <a href="./shop_select.php" class="button">店舗選択へ戻る</a>
            <a href="./search_img.php" class="button">写真検索へ戻る</a> 
            
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

            <form action="img_save.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="shop_id" value="<?php echo $shop_id; ?>">
                <input type="hidden" name="shop_name" value="<?php echo $shop_name; ?>">
                <input type="hidden" name="genre" value="<?php echo $shop_genre; ?>">     
    	        <h2>アップロード画像</h2>
                <p>(JPGかPNGを指定してください)</p>
                <input type="file" name="image" accept="image/jpeg, image/png"></p>
                <h2>コメント</h2>
                <textarea name="comment" wrap="hard"></textarea><p>
                <input type="submit" name="upload" value="送信"><p>
            </form>
        </div>
        
    </body>

    <footer>
        <p><small>&copy;2021 プロジェクト実習G15</small></p>
    </footer>
</html>