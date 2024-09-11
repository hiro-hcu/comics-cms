<?php
echo"manga viewページです<br>";
// 接続
$mysqli = new mysqli('localhost', 'hirotada', 'hirotada', 'comics');

//接続状況の確認
if($mysqli->connect_error){
        echo $mysqli->connect_error;
        exit();
}

$sql = "SELECT * from mst_titles";
$result = $mysqli->query($sql);

while ($row = $result->fetch_object()) {
    $data[] = [
        "name" => $row->name,
        "author_name"=> $row->author_name,
        "summary" => $row->summary,
        "id"=>$row->id  
    ];
}

include_once __DIR__.'./../pages/manga/ViewManga.php';