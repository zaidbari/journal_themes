<?php

use App\Lib\Config;
use \Core\View;
use \Core\Router;
/**
 * Routing
 */
$router = new Router();

// Add the routes
$router->res('/', 'Home@index');

$router->res('/current-issue', 'Articles@current');
$router->res('/latest-articles', 'Articles@latest');
$router->res('/track-article', 'Articles@track');
$router->res('/outstanding-reviewers', 'Pages@reviewers');

$router->res('/'. Config::env('DOI') . '-[i:id]', 'Articles@show');
$router->res('/'. Config::env('DOI') . '-[i:id]/pdf', 'Articles@pdf');

$router->res('/archives', 'Archives@index');

$router->res('/[i:year]/[i:volume]/[:issue]?-[i:id]', 'Issues@index');

$router->res('/page/[*:slug]', 'Pages@index');

$router->res('/receive-file', 'ReceiveFiles@file', 'POST');
$router->res('/receive-url', 'ReceiveFiles@url', 'POST');
$router->res('/receive-url-dev', 'ReceiveFiles@dev', 'POST');

$router->res('/log/request', 'ErrorLog@request');
$router->res('/log/error', 'ErrorLog@error');

$router->onHttpError(function ($code) { View::render('error/index', ['code' => $code]); });;

// Dispatch the router
$router->dispatch();