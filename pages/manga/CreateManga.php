<?php
echo "CreateMangaページです<br>";

// 接続
$mysqli = new mysqli('localhost', 'hirotada', 'hirotada', 'comics');

//接続状況の確認
if ($mysqli->connect_error) {
    echo $mysqli->connect_error;
    exit();
} else {
    $mysqli->set_charset('utf8');
}


//POST通信された時
if ($_SERVER["REQUEST_METHOD"] == "POST") {



    $manga_name = htmlspecialchars($_POST["manga_name"], ENT_QUOTES);
    $author_name = htmlspecialchars($_POST["author_name"], ENT_QUOTES);
    $summery = htmlspecialchars($_POST["summery"], ENT_QUOTES);

    //マンガ名が追加されている時
    if (isset($manga_name)) {
        $sql = "INSERT INTO mst_titles (`name`, `author_name`, `summary`)values(?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sss", $manga_name, $author_name, $summery);

        if ($stmt->execute()) {
            echo "追加成功";
            $stmt->close();
            $mysqli->close();
            header("Location: manga_view");
        } else {
            echo "追加に失敗しました";
        }
    }

    // 切断
    $stmt->close();
    $mysqli->close();
}


?>



<!DOCTYPE html>
<html>
<section>
    <form action="" method="post">
        manga_name:<br>
        <input type="text" name="manga_name" value="" required><br>
        <br>
        author_name:<br>
        <input type="text" name="autor_name" value=""><br>
        <br>
        summery:<br>
        <input type="text" name="summery" value="" required><br>
        <input type="submit" value="登録">
    </form>
</section>

</html>