<?php

// 創建websocket服務器對象
$ws = new swoole_websocket_server("127.0.0.1", 9501);

// 監聽WebSocket連接打開事件
$ws->on('open', function (swoole_websocket_server $ws, $request) {
    echo $request->server['remote_addr'] . "已連接\n";
    $ws->push($request->fd, "hello, welcome.your ip is {$request->server['remote_addr']}\n");

    // 廣播
    foreach ($ws->connections as $fd) {
        $ws->push($fd, "用戶{$request->fd}已加入聊天室");
    }
});

// 監聽WebSocket消息事件
$ws->on('message', function (swoole_websocket_server $server, $frame) {
    echo $frame->fd . " Message: {$frame->data}\n";
    // 廣播
    foreach ($server->connections as $fd) {
        $server->push($fd, "用戶{$frame->fd}說: {$frame->data}");
    }
});

// 監聽WebSocket連接關閉事件
$ws->on('close', function (swoole_websocket_server $server, $fd) {
    echo "client-{$fd} is closed\n";
    // 廣播
    foreach ($server->connections as $connectedfd) {
        if ($connectedfd == $fd) {
            continue;
        }
        $server->push($connectedfd, "用戶{$fd}已離開聊天室");
    }
});

$ws->start();