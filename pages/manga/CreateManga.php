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

// POST通信された時
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $manga_name = htmlspecialchars($_POST["manga_name"], ENT_QUOTES);
    $author_name = htmlspecialchars($_POST["author_name"], ENT_QUOTES);
    $summary = htmlspecialchars($_POST["summery"], ENT_QUOTES);

    // マンガ名が追加されている時
    if (isset($manga_name)) {
        $sql = "INSERT INTO mst_titles (`name`, `author_name`, `summary`) VALUES (?, ?, ?)";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("sss", $manga_name, $author_name, $summary);

        if ($stmt->execute()) {
            echo "追加成功";
            $stmt->close();
            $mysqli->close();
            header("Location: manga_view");
        } else {
            echo "追加に失敗しました";
        }
    }

    $stmt->close();
    $mysqli->close();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Manga</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        section {
            max-width: 600px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"] {
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <section>
        <h2>マンガ登録</h2>
        <form action="" method="post">
            <label for="manga_name">マンガ名:</label>
            <input type="text" name="manga_name" id="manga_name" value="" required>

            <label for="author_name">著者名:</label>
            <input type="text" name="author_name" id="author_name" value="" required>

            <label for="summary">概要:</label>
            <input type="text" name="summery" id="summary" value="" required>

            <input type="submit" value="登録">
        </form>
    </section>
</body>

</html>
