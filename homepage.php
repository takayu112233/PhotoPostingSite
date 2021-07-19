<?php
ini_set('display_errors', "On");

require("libDB.php");

$db = new libDB();
$pdo = $db->getPDO();

$sql = $pdo->prepare("select * from gerne;");

//SQL文の実行
$sql->execute();
//結果の取得
$result = $sql->fetchAll();


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    
    <title>Document</title>
    <link rel="stylesheet" href="homepage.css">
</head>
<body>
<div class="shell">
  <header class="shell-header">
    <img src="logo_1.png" width="300" height="120">
  </header>
  <main class="shell-body">
    <h2>何をお探しですか？</h2>
    <ul id="menu">
      
        <?php
        foreach($result as $loop){
          echo "<li>";
          echo "<a href=\"search_img.php?g=" .  $loop["gerne_id"] . "\">" . $loop["gerne_name"] . "</a>";
          echo "</li>";
          //echo $loop["gerne_id"] . $loop["gerne_name"];
        } 
        ?>
    </ul>
  </main>
  
</div>
</body>
</html>