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
     * @param string $username
     * @param string $pass
     * @return bool $result
     */
    public static function login($username, $pass)
    {
        //結果
        $result = false;

        //ユーザを検索取得
        $user_data = self::getUserData($username);

        if (!$user_data){
            $_SESSION['msg'] = 'ユーザー名を確認してください';
            return $result;
        }
        //パスワードの照会
        if (password_verify($pass, $user_data['pass'])){
            //ログイン成功
            session_regenerate_id(true);
            $_SESSION['login_user_id'] = $user_data['user_id'];
            $_SESSION['login_user_name'] = $user_data['user_name'];
            $_SESSION['login_user_nickname'] = $user_data['nickname'];
            $result = true;

            
            //ここでリダイレクト
            if(strpos($_COOKIE["back_url"],'.php') != false){
                $url = "Location: ../" . $_COOKIE["back_url"];
                setcookie("back_url", "", time() - 30);
                header($url);
            }else{
                header('Location: ../search_img.php');
            }
            
            return $result;
        }
        $_SESSION['msg'] = 'パスワードが一致しません';
        return $result;
    }


    /**
     * ユーザーデータの取得
     * @param string $nickname
     * @return array|bool $result|false
     */
    public static function getUserData($username)
    {
        //SQLの準備
        //SQLの実行
        //SQLの結果を返す

        $sql = 'SELECT * FROM users WHERE user_name = ?';

        //ユーザー情報を配列に格納
        $arr = [];
        $arr[] = $username;

       try {
           $stmt = connect()->prepare($sql);
           $stmt->execute($arr);
           //SQLの結果を返す（ユーザー情報）
           $user_data = $stmt->fetch();
           return $user_data;
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
        if (isset($_SESSION['login_user_name'])){
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