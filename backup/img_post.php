<!DOCTYPE html>
<?php 
session_start();

$nickname = "";

if (isset($_SESSION['login_user_nickname'])){
    $nickname = $_SESSION['login_user_nickname'];
}else{
    header('Location: ./search_img.php');
}
  
if(isset($_FILES["image"])){
    photo_save($_FILES["image"],$_SESSION["login_user_id"]);
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
                    <li><a>こんにちは <?php echo $nickname ?> さん</a></li>
                    <li><a href="./public/logout.php">ログアウト</a></li>
                </ul>
            </header>
        </div>
        <div class = "wrapper">  
            <a href="./shop_select.php" class="button">店舗選択へ戻る</a>
            <a href="./search_img.php" class="button">写真検索へ戻る</a> 
            
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

            <form action="img_save.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="shop_id" value="<?php echo $shop_id; ?>">
                <input type="hidden" name="shop_name" value="<?php echo $shop_name; ?>">
                <input type="hidden" name="genre" value="<?php echo $shop_genre; ?>">   
                 
    	        <h2>アップロード画像</h2>
                <div class="content"> 
                    <p>(JPGかPNGを指定してください)</p>
                    <input type="file" name="image" accept="image/jpeg, image/png"></p>
                </div>
                <h2>コメント</h2>

                <div class="content"> 
                    <textarea style="width:500px;height:200px;" name="comment" wrap="hard"></textarea><p>
                    <input type="submit" name="upload" value="送信"><p>
                </div>
            </form>
        </div>
        
    </body>

    <footer>
        <p><small>&copy;2021 プロジェクト実習G15</small></p>
    </footer>
</html>