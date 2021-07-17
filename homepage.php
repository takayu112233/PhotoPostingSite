<?php
session_start();

if (!isset($_SESSION[''])) {
    $_SESSION[''] = [];
}

if (isset($_POST['page']) && $_POST['page'] !== 'page') {
    $_SESSION['page']['page'] = $_POST['page'];
}
?>
<!doctype html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>ホームページ</title>
    <style type="text/css">
        * {
            color: #333;
        }
        main {
            width: 500px;
            margin: auto;
        }
        textarea {
            width: 500px;
            box-sizing: border-box;
            font-size: 20px;
            padding: 15px;
            border-radius: 10px;
            border: 1px solid #999;
            outline: none;
        }
        form {
            text-align: center;
            padding: 0 0 15px 0;
        }
        button {
            font-size: 20px;
            padding: 5px 15px;
        }
        .tweet {
            box-sizing: border-box;
            margin: 0px auto;
            padding: 15px;
            border: 1px solid #cccccc;
        }
        .tweet + .tweet {
            border-top: none;
        }
    </style>
</head>
<body>
<main>
    <h1><center>ホームページ</center></h1>
    <h2><center><何を探しますか？></center></h2>
    <h1><center>・カフェ</center></h1>
    <form action= method="post">
        
        <button>選択</button>
    </form>
    <h1><center>・レジャー</center></h1>
    <form action="" method="post">
       
        <button>選択</button>
    </form>
    <h1><center>・グルメ</center></h1>
    <form action= method="post">
        
        <button>選択</button>
    </form>
    <form action="libDB.php" method="post">
        
       
    </form>
    <?php
    foreach (array_reverse($_SESSION['tweets']) as $tweet) {
    ?>
    <div class="tweet"><?= nl2br($tweet) ?></div>
    <?php
    }
    ?>
</main>
</body>
</html>