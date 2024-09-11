<!DOCTYPE html>
<html>

<head>
<meta charset="utf-8">
    <meta property="og:title" content="漫画一覧">
    <title>漫画一覧ページ</title>
    <style>
        .button-create {
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .button-logout{
            display: inline-block;
            padding: 10px 20px;
            background-color: #4CAF50;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            text-align: right;
        }
    </style>
    <a href="manga_create" class="button-create">新規追加</a>
    <a href="logout" class="button-logout">ログアウト</a>
</head>


<body>
    <h2>マンガ一覧画面</h2>
    <table>
        <tr>
            <th>name</th>
            <th>author_name</th>
            <th>summary</th>
        </tr>
        <?php
        foreach($data as $value){
            ?>

            <tr>
                
                <td><?= $value['name'] ?></td>
                <td><?= $value['author_name'] ?></td>
                <td><?= $value['summary'] ?></td>
                <td><?= $value['id']?></td>
              <!--  <td>
                    <form action="manga_edit" method="post">
                        <button type="submit" name="action" value="<?=$value['id']?>">編集</button>
                    </form>
                </td> -->
                <td><a href="manga_edit?id=<?=$value['id']?>" class="button-logout">編集</a></td>
            </tr>
            <?php
        }
        ?>
        
    </table>
    
    
</body>

</html>