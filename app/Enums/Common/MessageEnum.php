<?php


namespace App\Enums\Common;

//消息参数
class MessageEnum
{
    const TYPE_SYSTEM = 1;

    const TYPE_PRIVATE_CHAT = 2;

    const TYPE_ROOM_CHAT = 3;

    const TYPE_FLOWER = 4;

    const TYPE_GOLD = 5;


//    消息类型
    public static $typeDesc = [
        self::TYPE_SYSTEM       => "系统消息！",
        self::TYPE_PRIVATE_CHAT => "私聊消息！",
        self::TYPE_ROOM_CHAT    => "聊天室消息！",
        self::TYPE_FLOWER       => "鲜花消息！",
        self::TYPE_GOLD         => "金币消息！",
    ];


    const PUSH_TYPE_ALL = 1;

    const PUSH_TYPE_PRIVATE = 2;

//    消息推送类型
    public static $pushTypeDesc = [
        self::PUSH_TYPE_ALL     => "推送给全部在线用户！",
        self::PUSH_TYPE_PRIVATE => "推送给单个用户！",
    ];



}
