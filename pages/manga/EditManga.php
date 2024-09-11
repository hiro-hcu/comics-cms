<?php
echo "EditMangaページです<br>";

// 接続
$mysqli = new mysqli('localhost', 'hirotada', 'hirotada', 'comics');

//接続状況の確認
if ($mysqli->connect_error) {
    echo $mysqli->connect_error;
    exit();
}


$manga_id = $_GET['id'];


$check_sql = "SELECT * FROM mst_titles WHERE `id`=$manga_id";
$check_result = $mysqli->query($check_sql);

//入力されたマンガidが存在する場合
if ($check_result->num_rows > 0) {
    echo"マンガid: {$manga_id}は存在します。";
} else {
    echo "error: マンガid: {$manga_id}は存在しません。";
    exit();
}

//POST通信
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo 'ポストされています。';

    $manga_name = htmlspecialchars($_POST["manga_name"]);
    $author_name = htmlspecialchars($_POST["autor_name"]);
    $summary = htmlspecialchars($_POST["summary"]);


    echo "{result->num_rows} : {$check_result->num_rows} ";
    
        $update_sql = "UPDATE mst_titles SET `name`= ?, `author_name`= ?, `summary`= ? WHERE `id`= ?";
        $update_stmt = $mysqli->prepare($update_sql);
        $update_stmt->bind_param("sssi", $manga_name, $author_name, $summary, $manga_id);

        if ($update_stmt->execute()) {
            echo "更新が成功しました。";

            //マンガ一覧へ移動
            header("Location: manga_view");
        } else {
            echo "更新に失敗しました。";
        }

        $update_stmt->close();


    $check_result->close();
}else{
    echo "ポストされていません。";
}


$mysqli->close();




?>

<!DOCTYPE html>
<html>
<section>
    <form action="" method="post">
        manga_name:<br>
        <input type="text" name="manga_name" value="" required><br>
        <br>
        author_name:<br>
        <input type="text" name="autor_name" value="" required><br>
        <br>
        summery:<br>
        <input type="text" name="summary" value="" required><br>
        <input type="submit" value="更新">
    </form>
</section>

</html>