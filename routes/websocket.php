<?php


use Illuminate\Http\Request;
use SwooleTW\Http\Websocket\Facades\Websocket;

/*
|--------------------------------------------------------------------------
| Websocket Routes
|--------------------------------------------------------------------------
|
| Here is where you can register websocket events for your application.
|
*/

Websocket::on('open', function ($websocket, Request $request) {
    // called while socket on connect
    \Illuminate\Support\Facades\Log::info('[connect]',['websocket'=>$websocket,'request'=>$request]);
    echo "connect";

});



Websocket::on('connect', function ($websocket, Request $request) {
    // called while socket on connect
    \Illuminate\Support\Facades\Log::info('[connect]',['websocket'=>$websocket,'request'=>$request]);
    echo "connect";

});

Websocket::on('disconnect', function ($websocket) {
    // called while socket on disconnect
    echo "disconnect";
});

Websocket::on('example', function ($websocket, $data) {
    $websocket->emit('message', $data);
});
