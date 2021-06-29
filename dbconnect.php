<?php
require_once 'env.php';
function connect()
{
    $host = DB_HOST;
    $db   = DB_NAME;
    $users = DB_USER;
    $pass = DB_PASS;

    $dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
    //$dsn = "利用するDBname:host=$host;dbname=$db;charset=utf8mb4";
    //try-catch プログラマが想像出来ないバグを検知するために利用
    //tryで例外を見つけたならcatchの中身の処理に移る
    try{
        $pdo = new PDO($dsn, $users, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
        /*データベースに接続する方法
        =>インスタンス名 = new PDO("データベースの種類:host=接続先アドレス, dbname=データベース名,charset=文字エンコード" "ユーザー名", "パスワード", オプション)
        オプション説明(SQL実行でエラーが起こった際にどう処理するかを指定)
        =>PDO::ERRMODE_EXCEPTION エラー発生時、PDOがPDOExceptionを投げる
        　投げられたPDOExceptionをcatchで受け取る($eに格納)
        =>PDO::FETCH_ASSOC
        参照(PDOのfetchで取得出来る配列パターン一覧)
        */https://kinocolog.com/pdo_fetch_pattern/
        return $pdo;
    }catch(PDOException $e){
        echo '接続失敗です'. $e->getMessage();
        //e->getMessage例外のメッセージ文字列を$eに返す
        exit();
    }   
}