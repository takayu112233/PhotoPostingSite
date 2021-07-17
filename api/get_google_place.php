<?php
if (isset($_GET['s_name'])){
    $name = $_GET['s_name'];

    mb_language("Japanese");
    mb_internal_encoding("UTF-8");  
    $name = urlencode($name);
    $url = "https://maps.googleapis.com/maps/api/place/textsearch/json?key=AIzaSyD7ETzwQaGboRO7zRkC3rMLlEYvEBmssiQ&language=ja&query=" . $name;
  
    $contents = file_get_contents($url);
    echo $contents;
}else{
    echo("err");
}
?>