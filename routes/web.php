<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

use App\CoinWatch\Coins\GetCoins;

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->get('/api/coins', function () {
    return new GetCoins(new \App\CoinWatch\Coins\CoinRepository());
});

$router->get('/api/coin/{id}', function ()
{
    return new \App\CoinWatch\Coins\GetIndividualCoin();
});