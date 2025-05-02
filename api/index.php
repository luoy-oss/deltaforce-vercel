<?php

// Vercel PHP入口文件

use think\App;

// 定义应用目录
define('APP_PATH', __DIR__ . '/../app/');

// 加载基础文件
require __DIR__ . '/../vendor/autoload.php';

// 执行HTTP应用并响应
$http = (new App())->http;
$response = $http->run();
$response->send();
$http->end($response);