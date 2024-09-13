<?php

require_once __DIR__ . "/vendor/autoload.php";

session_start();
define('__ROOT__', dirname(__FILE__));

require_once(__DIR__.'/route/Route.php');

// echo 'index.phpです<br/>';

//現在アクセスされているパスを取得
$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$route = trim($request, '/');
// echo "ルートパス「{$route}」 <br/>";
// echo __DIR__;
$routes = [
        new Route("","MangaViewController"),
        new Route("login","LoginController"),
        new Route("logout","LogoutController", true),
        new Route("newuser","NewUserController", true),
        new Route("manga_view","MangaViewController", true),
        new Route("manga_edit","MangaEditController", true),
        new Route("chapter_view","ChapterViewController", true),
        new Route("chapter_edit","ChapterEditController", true),
        new Route("chapter_create","ChapterCreateController", true),
        new Route("manga_create","MangaCreateController",true),
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
    $controller_name = $matched_route->getController();
    
    // 未ログイン状態でログイン必須ページにアクセスした際は、エラーページに飛ばす
    if (!isset($_SESSION["user_id"]) && $matched_route->getLoginRequire()) {
        $controller_name = "AuthorizedErrorController";
    }
} else {
    // ルートが定義されていない場合は404エラーページ
    $controller_name = 'NotFoundController';
}

$controller_name = "App\\Controller\\{$controller_name}";
$controller = new $controller_name();
$controller->get();

// $controller_path = __DIR__ . '/controllers/' . $controller;

// // コントローラファイルが存在するか確認
// if (file_exists($controller_path)) {
//     // include_once $controller_path;

//     (new LoginController())->get();
// } else {
//     // コントローラファイルが存在しない場合は404エラーページ
//     header("HTTP/1.0 404 Not Found");
//     echo "404 Not Found";
//     exit();
// }

?>
