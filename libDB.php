<?php

class libDB{
    //private PDO $pdo;
    private $pdo;
    /**
     * コンストラクタ
     */
    public function __construct()
    {
        $this->pdo = new PDO("mysql:host=localhost;dbname=g15;charset=utf8","g15","g15", 
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING]);

    }

    public function getPDO(){
        return $this->pdo;
    }




}
?>