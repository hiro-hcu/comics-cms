<?php

echo "<br>chapter一覧画面です";

// 接続
$mysqli = new mysqli('localhost', 'hirotada', 'hirotada', 'comics');

// 接続状況の確認
if($mysqli->connect_error){
    echo $mysqli->connect_error;
    exit();
}

// title_id
$title_id = $_GET['manga_id'];
echo "{title_id}: $title_id <br>";

$sql = "SELECT * FROM mst_chapters WHERE title_id = $title_id";

if($result = $mysqli->query($sql)){
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_object()) {
            $data[] = [
                "chapter_id" => $row->id,
                "title_id" => $row->title_id,
                "name"=> $row->name,
                "start_date" => $row->start_date,
                "display_start_day" => date('Y年m月d日H時i分s秒', strtotime($row->start_date)),
            ];
        }
    } else {
        echo "レスポンスが0です。<br>";
    }
} else {
    echo "error: データベースエラー";
}

?>

<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
    <meta property="og:title" content="チャプタ一覧">
    <title>チャプタ一覧ページ</title>
    <style>
        /* 全体の背景とフォント設定 */
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-top: 30px;
        }

        /* ボタンスタイル */
        .button {
            display: inline-block;
            padding: 10px 25px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .button:hover {
            background-color: #0056b3;
            transform: translateY(-2px);
        }

        /* ヘッダーボタンのレイアウト */
        .header {
            display: flex;
            justify-content: space-between;
            padding: 20px;
            background-color: #ffffff;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }

        /* テーブルスタイル */
        table {
            width: 80%;
            margin: 0 auto;
            border-collapse: collapse;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        th, td {
            padding: 12px 15px;
            border: 1px solid #ddd;
            text-align: left;
        }

        th {
            background-color: #e9ecef;
            color: #333;
        }

        tr:nth-child(even) {
            background-color: #f8f9fa;
        }

        td a {
            padding: 5px 15px;
            background-color: #6c757d;
            color: white;
            border-radius: 20px;
            text-decoration: none;
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        td a:hover {
            background-color: #343a40;
            transform: translateY(-2px);
        }

    </style>
</head>

<body>

    <div class="header">
        <a href="chapter_create?title_id=<?= $title_id ?>" class="button">新規追加</a>
        <a href="manga_view" class="button">マンガ一覧へ</a>
        <a href="logout" class="button">ログアウト</a>
    </div>

    <h2>チャプタ一一覧画面</h2>

    <table>
        <tr>
            <th>チャプター名</th>
            <th>公開開始日</th>
            <th>操作</th>
        </tr>
        <?php if (!empty($data)) : ?>
            <?php foreach($data as $value): ?>
            <tr>
                <td><?= $value['name'] ?></td>
                <td><?= $value['display_start_day'] ?></td>
                <td><a href="chapter_edit?chapter_id=<?= $value['chapter_id'] ?>" class="button">編集</a></td>
            </tr>
            <?php endforeach; ?>
        <?php else : ?>
            <tr>
                <td colspan="4">チャプタが存在しません。</td>
            </tr>
        <?php endif; ?>
    </table>

</body>

</html>
