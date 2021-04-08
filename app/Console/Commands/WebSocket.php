<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class WebSocket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'run:WebSocket';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '启动WebSocket';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //创建server
        $server = new \Swoole\WebSocket\Server("0.0.0.0", 9503);
        //连接成功回调
        $server->on('open', function (\Swoole\WebSocket\Server $server, $request) {
            $this->info($request->fd . '链接成功');
        });

        //连接成功回调
        $server->on('open', function (\Swoole\WebSocket\Server $server, $request) {
            $this->info($request->fd . '链接成功');
        });

        //收到消息回调
        $server->on('message', function (\Swoole\WebSocket\Server $server, $frame) {
            $content = $frame->data;

            //推送给所有链接
            foreach ($server->connections as $fd){
                $server->push($fd,$content);
            }
        });
        //关闭链接回调
        $server->on('close', function ($ser, $fd) {
            $this->info($fd . '断开链接');
        });
        $server->start();
    }
}
