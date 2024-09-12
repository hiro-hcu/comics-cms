<?php

echo "<br>ChapterEditページです。";

// 接続
$mysqli = new mysqli('localhost', 'hirotada', 'hirotada', 'comics');

//接続状況の確認
if ($mysqli->connect_error) {
    echo $mysqli->connect_error;
    exit();
}

// リクエストがGETまたはPOSTで条件分岐
switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":
        $chapter_id = htmlspecialchars($_GET['chapter_id']);

        $sql = "SELECT * FROM mst_chapters WHERE `id` = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param("i", $chapter_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "チャプターIDは存在します。";
            $row = $result->fetch_assoc();
            $title_id = $row['title_id'];
            $chapter_id = $row['id'];
            $chapter_name = $row['name'];
            $start_date = $row['start_date'];
        } else {
            echo 'チャプターIDは存在しません。';
            exit();
        }
        break;

    case "POST":
        $chapter_id = htmlspecialchars($_POST["chapter_id"]);
        $title_id = htmlspecialchars($_POST["title_id"]);
        $name = htmlspecialchars($_POST["name"]);
        $start_date = date('Y-m-d H:i:s', strtotime($_POST["start_date"]));

        // echo "現在時刻のunixtimestamp: " . strtotime(date("Y-m-d H:i:s")) . "</br>";
        // echo "入力した時刻のunixtimestamp: " . strtotime($_POST["start_date"]) . "</br>";


        // MEMO: 今の時間
        if (strtotime(date("Y-m-d H:i:s")) > strtotime($start_date)) {
            $error = [
                "message" => "過去の日程は選択できません。",
                "location" => "chapter_view?manga_id=$title_id"
            ];
            include_once __DIR__ . '/../error/500.php';
        }

        if (isset($name)) {
            $update_sql = "UPDATE mst_chapters SET `name`= ?, `start_date`= ? WHERE `id`= ?";
            $update_stmt = $mysqli->prepare($update_sql);
            $update_stmt->bind_param("ssi", $name, $start_date, $chapter_id);
        } else {
            echo "nameを入力してください";
        }
        if ($update_stmt->execute()) {
            echo "更新が成功しました。";
            $update_stmt->close();

            //マンガ一覧へ移動
            header("Location: chapter_view?manga_id=$title_id");
        } else {
            echo "更新に失敗しました。";
        }
        break;
}

$mysqli->close();

?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chapter Edit</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f4f4f4;
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

        input[type="text"],
        input[type="date"] {
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

        .message {
            text-align: center;
            margin-bottom: 20px;
            font-size: 14px;
            color: #555;
        }
    </style>
</head>

<body>

    <section>
        <h2>Chapter Edit</h2>

        <div class="message">
            <?= isset($chapter_name) ? "チャプターの編集を行います。" : "" ?>
        </div>

        <form action="" method="post">
            <input type="hidden" name="chapter_id" value="<?= $chapter_id ?>" required>
            <input type="hidden" name="title_id" value="<?= $title_id ?>" required>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="<?= $chapter_name ?>" required>

            <label for="start_date">Start Date:</label>
            <input type="datetime-local" name="start_date" id="start_date" value="<?= $start_date ?>" required>

            <input type="submit" value="更新">
        </form>
    </section>

</body>

</html>