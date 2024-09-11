<?php
// header('Location: /newUser');
// exit();

session_start();

if (isset($_SESSION['user_id'])) {
    // SESSION[user_id]に値入っていればログインしたとみなす
    echo "既にログインしています";
    echo "セッション維持ID: " . $_SESSION['user_id'] . "<br/>";
    exit();
}

//html文から入力データ取得
$mail_address = htmlspecialchars($_POST["mail_address"], ENT_QUOTES);
$password = htmlspecialchars($_POST["password"], ENT_QUOTES);

//パスワードをハッシュ化
$password_hash = hash("sha256", $password);

//MySQLに接続
$mysqli = new mysqli('localhost', 'hirotada', 'hirotada', 'comics');

if ($mysqli->connect_error) {
    echo $mysqli->connect_error;
    exit();
}



/**
 * 課題２：データベースにPOSTで取得したusername,password(ハッシュ化)と一致するものがあればセッションを開始し
 * $_SESSION['user_id']にユーザIDを,$_SESSION['user_name']にユーザ名を格納する処理を書いてください
 */

/* // 自分で作成物
$sql = "SELECT * FROM trx_users WHERE ";
$result = $mysqli->query($sql); */

$sql = "SELECT * FROM  adm_admin_users WHERE mail_address=? && password=?";
$stmt = $mysqli->prepare($sql);
//$sqlの?のところに、$user_name(html文で取得した)を挿入、string型で
$stmt->bind_param('ss', $mail_address, $password_hash);
//実行
$result_bool = $stmt->execute();

$result = $stmt->get_result();

$user_data = $result->fetch_assoc();
/* 
var_dump("db_user_name");
var_dump($user_data['user_name']." <br/>");
var_dump("db_password <br/>");
var_dump($user_data['password']." <br/>");
var_dump("input _password");
var_dump($password_hash." <br/>"); */


//dbにユーザーデータが存在している場合

if (!is_null($user_data)) {
    $_SESSION['user_id'] = $user_data['id'];
    echo "user_id: {$user_data['id']}です。 <br/>";
    echo 'ログインできました。<br/>';
    exit();

}

// 切断
$stmt->close();
$mysqli->close();

#ページの呼びだし
include_once __DIR__.'/../access/login.php';


?>