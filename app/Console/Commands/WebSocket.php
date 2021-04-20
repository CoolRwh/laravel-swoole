<?php

namespace App\Console\Commands;

use App\Enums\Common\MessageEnum;
use App\Service\Common\MessageService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use function PHPSTORM_META\elementType;

class WebSocket extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
//    protected $signature = 'run:WebSocket';

    protected $signature = 'run:websocket {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '启动WebSocket';


    protected $websocket_config = [];


    protected $user_id_p = "websocket:user_id:";

    protected $user_fd = "websocket:fd:";

    protected $host = '0.0.0.0';

    protected $port = '9510';

    protected $server;


    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->websocket_config = config('swoole_config.websocket');
        $this->host = $this->websocket_config['host'] ?? $this->host;
        $this->port = $this->websocket_config['port'] ?? $this->port;
//        $this->user_id_p = $this->websocket_config['user_prefix'] ?? $this->user_id_p;
//        $this->user_fd = $this->websocket_config['fd_prefix'] ?? $this->user_fd;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
//    public function handle()
//    {
//        //创建server
//        $server = new \Swoole\WebSocket\Server("0.0.0.0", 9503);
//
//        //连接成功回调
//        $server->on('open', function (\Swoole\WebSocket\Server $server, $request) {
//
//            $user_id = $request->get(['user_id']);
//
//            Log::info('[连接参数]',['data'=>$request->fd]);
//            $this->info($request->fd .'连接参数');
//        });
//
//        //收到消息回调
//        $server->on('message', function (\Swoole\WebSocket\Server $server, $frame) {
//
//            $content = $frame->data;
//
//            Log::info('[消息]：',['data'=>$content]);
//
//            if (is_object($server->connections)){
//                $data = collect($server->connections)->toArray();
//            }else{
//                $data = [];
//            }
//
//            Log::info('[connections]：',['fd'=>$data]);
//            //推送给所有链接
//            foreach ($server->connections as $fd){
//                Log::info('[fd]：',['fd'=>$fd]);
//                $server->push($fd,$content);
//            }
//
//
//
//        });
//        //关闭链接回调
//        $server->on('close', function ($ser, $fd) {
//            $this->info($fd . '断开链接');
//        });
//        $server->start();
//    }



    public function handle()
     {
        $arg = $this->argument('action');
        switch ($arg) {
            case 'start':
                $this->info('websocket 开启成功~');
               $this->start();
                break;
             case 'stop':
                $this->info('swoole server stoped');
                 break;
             case 'restart':
               $this->info('swoole server restarted');
                 break;
        }
    }



    private function start()
     {

         $this->server =  new \Swoole\WebSocket\Server("0.0.0.0",(int)$this->port);

         $MessageService  =  new MessageService();


         //监听WebSocket连接打开事件
         $this->server->on('open', function ($server, $request) use ($MessageService) {
             $res_data = [];
             $res_data['message'] = '链接成功~';

             $data = $MessageService->open($server,$request);

             Log::info('[open][data]',$data);

           if (!empty($data['fd']) && !empty($data['push_data'])){
               foreach ($data['fd'] as $fd_k => $fd_v){
                   $fd =  (int) $fd_v;
                   if ($server->isEstablished($fd)) {
                       $server->push($fd, json_encode($data['push_data']));
                   }
               }
           }else{
               $server->push($request->fd, json_encode($res_data));
           }

         });
         //监听WebSocket消息事件
         $this->server->on('message', function ($server, $frame)use ($MessageService) {
             $data = $MessageService->message($server, $frame);

             if (is_array($data['push_data'])){
                 $data['push_data'] = json_encode($data['push_data']);
             }

             switch ($data['message_type']){
                 case MessageEnum::PUSH_TYPE_ALL :
                     foreach ($server->connections as $fd) {
                         // 需要先判断是否是正确的websocket连接，否则有可能会push失败
                         if ($server->isEstablished($fd)) {
                             $server->push($fd, $data['push_data']);
                         }
                     }
                     break;

                 case MessageEnum::PUSH_TYPE_PRIVATE :

                     foreach ($data['fd_list'] as $fd_k => $fd_v) {
                         // 需要先判断是否是正确的websocket连接，否则有可能会push失败
                         $fd = (int)$fd_v;
                         if ($server->isEstablished($fd)) {
                             $server->push($fd,$data['push_data']);
                         }
                     }

                     break;

             }
             $this->info("client is SendMessage\n");
         });
         //监听WebSocket主动推送消息事件
         $this->server->on('request', function ($request, $response) use ($MessageService) {
             $res_data = [];
             $data = $MessageService->request($request, $response);

             $this->info("client is PushMessage\n");
         });
         //监听WebSocket连接关闭事件
         $this->server->on('close', function ($server, $fd) use ($MessageService) {
             $data = $MessageService->request($server, $fd);
                 $this->info("client is close\n");
         });
         $this->server->start();
     }





}
