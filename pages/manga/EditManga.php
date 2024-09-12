<?php
echo "EditMangaページです<br>";

// 接続
$mysqli = new mysqli('localhost', 'hirotada', 'hirotada', 'comics');

//接続状況の確認
if ($mysqli->connect_error) {
    echo $mysqli->connect_error;
    exit();
}


switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $manga_id = $_GET['manga_id'];

        $check_sql = "SELECT * FROM mst_titles WHERE `id`= ?";
        $check_result = $mysqli->prepare($check_sql);
        $check_result->bind_param("s", $manga_id);
        $check_result->execute();
        $result = $check_result->get_result();

        if ($result->num_rows > 0) {
            echo "マンガid: {$manga_id}は存在します。";
            $row = $result->fetch_assoc();
            $manga_name = $row['name'];
            $manga_author_name = $row['author_name'];
            $manga_summary = $row['summary'];
        } else {
            echo "error: マンガid: {$manga_id}は存在しません。";
            exit();
        }

        break;

    case "POST":
        echo 'ポストされています。';
        $manga_id = htmlspecialchars($_POST["manga_id"]);
        $manga_name = htmlspecialchars($_POST["manga_name"]);
        $manga_author_name = htmlspecialchars($_POST["author_name"]);
        $manga_summary = htmlspecialchars($_POST["summary"]);

        $update_sql = "UPDATE mst_titles SET `name`= ?, `author_name`= ?, `summary`= ? WHERE `id`= ?";
        $update_stmt = $mysqli->prepare($update_sql);
        $update_stmt->bind_param("sssi", $manga_name, $manga_author_name, $manga_summary, $manga_id);

        if ($update_stmt->execute()) {
            echo "更新が成功しました。";
            header("Location: manga_view");
        } else {
            echo "更新に失敗しました。";
        }

        $update_stmt->close();
        break;
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Manga</title>
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
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        form {
            display: flex;
            flex-direction: column;
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

        h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }
    </style>
</head>

<body>

    <section>
        <h2>マンガ編集</h2>
        <form action="" method="post">
            <input type="hidden" name="manga_id" id="manga_name" value="<?= htmlspecialchars($manga_id) ?>" required>
            <label for="manga_name">マンガ名:</label>
            <input type="text" name="manga_name" id="manga_name" value="<?= htmlspecialchars($manga_name) ?>" required>

            <label for="author_name">著者名:</label>
            <input type="text" name="author_name" id="author_name" value="<?= htmlspecialchars($manga_author_name) ?>"
                required>

            <label for="summary">概要:</label>
            <input type="text" name="summary" id="summary" value="<?= htmlspecialchars($manga_summary) ?>" required>

            <input type="submit" value="更新">
        </form>
    </section>

</body>

</html>