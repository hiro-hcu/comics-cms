<?php
session_start();
define('__ROOT__', dirname(__FILE__));

require_once(__DIR__.'/route/Route.php');


echo 'index.phpです<br/>';

//現在アクセスされているパスを取得
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = trim($request, '/');
echo "ルートパス「{$route}」 <br/>";
echo __DIR__;
$routes = [
        new Route("","MangaViewController.php", true),
        new Route("login","LoginController.php"),
        new Route("logout","LogoutController.php", true),
        new Route("newuser","NewUserController.php", true),
        new Route("manga_view","MangaViewController.php"),
        new Route("manga_edit","MangaEditController.php"),
        new Route("chapter_view","ChapterViewController.php"),
        new Route("chapter_edit","ChapterEditController.php"),
        new Route("manga_create","MangaCreateController.php",true),
        // 追加のルートをここに定義
];

$matched_route = null;
foreach ($routes as $r) {
    if ($r->getPath() === $route) {
        $matched_route = $r;
        break;
    }
}

//echo "<br/>matched_route: {$matched_route->getPath()} <br/>";

if (!is_null($matched_route)) {
    $controller = $matched_route->getController();
    
    // 未ログイン状態でログイン必須ページにアクセスした際は、エラーページに飛ばす
    if (!isset($_SESSION["user_id"]) && $matched_route->getLoginRequire()) {
        $controller = "AuthorizedErrorController.php";
    }
} else {
    // ルートが定義されていない場合は404エラーページ
    $controller = 'NotFoundController.php';
}

$controller_path = __DIR__ . '/controllers/' . $controller;

// コントローラファイルが存在するか確認
if (file_exists($controller_path)) {
    include_once $controller_path;
} else {
    // コントローラファイルが存在しない場合は404エラーページ
    header("HTTP/1.0 404 Not Found");
    echo "404 Not Found";
    exit();
}

?>
