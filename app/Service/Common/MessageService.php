<?php


namespace App\Service\Common;


use App\Enums\Common\MessageEnum;
use App\Http\Controllers\Common\ApiHelper;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class MessageService
{

    protected $websocket_config = [];

    public function __construct()
    {
        $this->websocket_config = config('swoole_config.websocket');
    }


    public function close($server, $fd)
    {

    }



    public function request($request, $response)
    {

    }

    public function message($server, $frame)
    {
        $push_data = [];
        $push_data['fd_list'] = [];
        $push_data['push_data'] = [];
        $push_data['message_type'] = 0;


        $server = self::get_server($server);
        $data   =   json_decode($frame->data,true);
        if (empty($data)){
            $push_data['push_data'] = $frame->data;
        }else{
            $push_data['push_data'] = $data;
        }

        if (!empty($data['user_id']) && !empty($data['get_message_user_id'])){

            $push_data['message_type'] = MessageEnum::PUSH_TYPE_PRIVATE;
            $user_info_key = $this->websocket_config['user_prefix'].$push_data['user_id'];
            if (Redis::exists($user_info_key)) {
                $info = Redis::get($user_info_key);
                $user_info = json_decode($info, true);
                $user_fd = (int)$user_info['fd'];
                array_push($push_data['fd_list'], $user_fd);
            }

            $user_info_Key = $this->websocket_config['user_prefix'].$push_data['get_message_user_id'];
            if (Redis::exists($user_info_Key)) {
                $info = Redis::get($user_info_Key);
                $user_info = json_decode($info, true);
                $user_fd = (int)$user_info['fd'];
                array_push($push_data['fd_list'], $user_fd);
            }

        }else{
            $push_data['message_type'] = MessageEnum::PUSH_TYPE_ALL;
        }


        return $push_data;
    }


    /**
     *
     * @param $server
     * @param $request
     * @return array
     */
    public function open($server,$request)
    {
        $server = self::get_server($server);
        $request = self::get_request($request);
        $push_data = [];
        $push_data['fd_list'] = [];
        $push_data['push_data'] = [];

        if (!empty($request['fd'])){
            array_push($push_data['fd_list'],$request['fd']);
        }

        return $push_data;
    }


    /**
     *
     * @param $data  array 数据数组
     * @param $message_type
     * @param int $push_type
     * @return bool
     */

    public function websocket_message_push($data, $message_type, $push_type = MessageEnum::PUSH_TYPE_PRIVATE)
    {
        if (empty($data)) {
            return false;
        }
        $push_data = [];
        $push_data['user_id'] = '';
        $push_data['get_message_user_id'] = '';
        $push_data['fd_list'] = [];
        $push_data['push_data'] = [];
        $push_data['push_type'] = $push_type;

        //构建消息推送内容
        switch ($message_type) {
            //系统消息
            case MessageEnum::TYPE_SYSTEM:
                break;
            //私聊消息
            case MessageEnum::TYPE_PRIVATE_CHAT:
                break;
            //聊天室消息
            case MessageEnum::TYPE_ROOM_CHAT:
                break;
        }


        if ($push_type != MessageEnum::PUSH_TYPE_ALL) {
            $user_info_key = $this->websocket_config['user_prefix'].$push_data['user_id'];
            if (Redis::exists($user_info_key)) {
                $info = Redis::get($user_info_key);
                $user_info = json_decode($info, true);
                $user_fd = (int)$user_info['fd'];
                array_push($push_data['fd_list'], $user_fd);
            }

            $user_info_Key = $this->websocket_config['user_prefix'].$push_data['get_message_user_id'];
            if (Redis::exists($user_info_Key)) {
                $info = Redis::get($user_info_Key);
                $user_info = json_decode($info, true);
                $user_fd = (int)$user_info['fd'];
                array_push($push_data['fd_list'], $user_fd);
            }
        }

        $server = request()->server();
        $url = "http://".$server['SERVER_NAME']."/ws";
        if (!empty($push_data['fd_list'])){
            ApiHelper::httpPost($url, $push_data);
        }
        return true;
    }


    /**
     *获取 $server 数据
     * @param $server
     * @return array
     */
    public  static function get_server($server)
    {
        if (is_object($server)) {
            $server = collect($server)->toArray();

            if (is_object($server['connections'])){
                $server['connections'] = collect($server['connections'])->toArray();
            }
            if (is_object($server['ports'])){
                $server['ports'] = collect($server['ports'])->toArray();
            }
        }
        return $server;
    }

    /**
     *获取 $request 数据
     * @param $request
     * @return array
     */
    public static function get_request($request)
    {
        if (is_object($request)) {
            $request = collect($request)->toArray();
        }
        return $request;
    }

}
