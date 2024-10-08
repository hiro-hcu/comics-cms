<?php

echo "<br>chapter作成ページです。";

// 接続
$mysqli = new mysqli('localhost', 'hirotada', 'hirotada', 'comics');

//接続状況の確認
if ($mysqli->connect_error) {
    echo $mysqli->connect_error;
    exit();
} 

//POST通信された時
switch ($_SERVER["REQUEST_METHOD"]) {
    case "GET":

        //manga_idを取得
        $title_id = $_GET['title_id'];
        echo "{title_id }: $title_id ";
        break;

    case "POST":
        $title_id = $_POST["title_id"];
        echo "{title_idです。POSTの画面}{$title_id}<br>";
        $name = htmlspecialchars($_POST["name"], ENT_QUOTES);
        $start_date = date('Y-m-d H:i:s', strtotime($_POST["start_date"]));

         // MEMO: 今の時間
         if (strtotime(date("Y-m-d H:i:s")) > strtotime($start_date)) {
            $error = [
                "message" => "過去の日程は選択できません。",
                "location" => "chapter_view?manga_id=$title_id"
            ];
            include_once __DIR__ . '/../error/500.php';
        }

        if (isset(($name))) {
            $sql = "INSERT INTO mst_chapters (`title_id`, `name`, `start_date`)values(?, ?, ?)";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param("iss", $title_id, $name, $start_date);

            if ($stmt->execute()) {
                echo "追加成功";

                header("Location: chapter_view?manga_id=$title_id");
            } else {
                echo "追加に失敗しました";
            }

        }
        // 切断
        $stmt->close();
        $mysqli->close();
        break;
}
?>

<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>チャプター作成</title>
    <style>
        body {
            font-family: Arial, sans-serif;
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

        p {
            font-size: 16px;
            color: #555;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        input[type="text"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="submit"] {
            width: 100%;
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
        <h2>チャプター作成</h2>
        <p>title_id: <?= htmlspecialchars($title_id) ?></p>
        <form action="" method="post">
            <label for="name">名前:</label>
            
            <input type="hidden" name="title_id" value="<?=$title_id?>" required>
            <input type="text" name="name" value="" required>

            <label for="start_date">開始日:</label>
            <input type="date" name="start_date" value="" required>

            <input type="submit" value="登録">
        </form>
    </section>
</body>

</html>