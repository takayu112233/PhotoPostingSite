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
    
    <title>homepage</title> 
     <style type="text/css">
	<!--
	body {
	background-color: #f0f8ff;
	}
	-->
	</style>
    <link rel="stylesheet" href="homepage.css">     
    
</head>
<body>  
<div class="shell">
  <header class="shell-header">
    <h2 style="text-align:center"><font size="6"><img src="logo_1.png" width="300" height="130"></font>
    
    
  </header>
  <main class="shell-body">
   <h2 style="text-align:center"><font size="6">何をお探しですか？</font></h2>
    <ul id="menu">
    
      
 <?php
          foreach($result as $loop){
          
          echo   "<center><a href=\"search_img.php?g=".$loop["gerne_id"] . "\">" . $loop["gerne_name"] . "</a></center>";
          echo "<center></li></center>";
          //echo $loop["gerne_id"] . $loop["gerne_name"];             
        } 
        ?>
    </ul>
   </main>

      
    </ul>
  </main>
  
</div>
</body>
<footer>
       <center> <p><small>&copy;2021 プロジェクト実習G15</small></p></center>
    </footer>
</html>