<?php
// header('Location: /newUser');
// exit();

session_start();

if (isset($_SESSION['user_id'])) {
    // SESSION[user_id]に値入っていればログインしたとみなす
    echo "既にログインしています";
    echo "セッション維持ID: " . $_SESSION['user_id'] . "<br/>";
    header("Location: manga_view");
    exit();
}


switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        include_once __DIR__ . '/../access/login.php';
        break;
    case "POST":
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

        $sql = "SELECT * FROM  adm_admin_users WHERE mail_address=? && password=?";
        $stmt = $mysqli->prepare($sql);
        //$sqlの?のところに、$user_name(html文で取得した)を挿入、string型で
        $stmt->bind_param('ss', $mail_address, $password_hash);
        //実行
        $result_bool = $stmt->execute();

        $result = $stmt->get_result();

        $user_data = $result->fetch_assoc();

        //dbにユーザーデータが存在している場合
        if (!is_null($user_data)) {
            $_SESSION['user_id'] = $user_data['id'];
            echo "<br>user_id: {$user_data['id']}です。 <br/>";
            // 切断
            $stmt->close();
            $mysqli->close();
            header("Location: manga_view");
            exit();

        }

        // 切断
        $stmt->close();
        $mysqli->close();
        break;


}

?>