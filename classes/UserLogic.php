<?php
require_once '../dbconnect.php';
//requireと違い外部ファイルがすでに読み込まれてないかチェックを行う

class UserLogic //Userlogicという名のクラス名定義
{

    /**
     * ユーザを登録する
     * @param array $userData　第一引数(userDtaの配列)
     * @return bool $result　戻り値(resultが真or偽)
     */
    public static function createUser($userData)
    {
        $result = false;

        $sql = 'INSERT INTO users (user_name, nickname, pass) VALUES (?, ?, ?)';

         //ユーザデータを配列に入れる
         $arr = [];
         $arr[] = $userData['username'];
         $arr[] = $userData['nickname'];
         $arr[] = password_hash($userData['pass'],
         PASSWORD_DEFAULT); //passwordのハッシュ化

        try {
            $stmt = connect()->prepare($sql);
            $result = $stmt->execute($arr);
            return $result;
        }catch(\Exception $e) {
            return $result;
        }
    }
    /**
     * ログイン処理
     * @param string $nickname
     * @param string $pass
     * @return bool $result
     */
    public static function login($nickname, $pass)
    {
        //結果
        $result = false;

        //ユーザをニックネームから検索取得
        $g15 = self::getUserByNickname($nickname);

        if (!$g15){
            $_SESSION['msg'] = 'ニックネームが一致しません';
            return $result;
        }
        //パスワードの照会
        if (password_verify($pass, $g15['pass'])){
            //ログイン成功
            session_regenerate_id(true);
            $_SESSION['login_user'] = $g15;
            $result = true;
            return $result;
        }
        $_SESSION['msg'] = 'パスワードが一致しません';
        return $result;
    }


    /**
     * ニックネームからユーザ名を取得
     * @param string $nickname
     * @return array|bool $result|false
     */
    public static function getUserByNickname($nickname)
    {
        //SQLの準備
        //SQLの実行
        //SQLの結果を返す

        $sql = 'SELECT * FROM users WHERE nickname = ?';

        //ニックネームを配列に入れる
        $arr = [];
        $arr[] = $nickname;

       try {
           $stmt = connect()->prepare($sql);
           $stmt->execute($arr);
           //SQLの結果を返す
           $g15 = $stmt->fetch();
           return $g15;
       }catch(\Exception $e) {
           return false;
       }
    }
        /**
     * ログインチェック
     * @param void
     * @return bool $result
     */
    public static function checkLogin()
    {
        $result = false;
        //セッションにログインユーザが入ってないならfalse
        if (isset($_SESSION['login_user'])&& $_SESSION['login_user']['user_id']>0){
            return $result = true;
        }
    }


    /**
     * ログアウト処理
     */
    public static function logout(){
        $_SESSION = array();
        session_destroy();
    }
    /*
    論理削除処理
    */
    public static function logicalDeletion(){
 
    }
}