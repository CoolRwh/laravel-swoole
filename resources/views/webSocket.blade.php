<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>

<img src="" id="photo">

{{--<div style="width:600px;margin:0 auto;border:1px solid #ccc;">--}}
{{--    <div id="content" style="overflow-y:auto;height:300px;"></div>--}}
{{--    <hr />--}}
{{--    <div style="height:40px;background:white;">--}}
{{--        <input type="text" class="form-control" id="message"  placeholder="请输入内容">--}}
{{--        <button type="button" class="btn btn-primary" onclick="sendMessage()">Primary</button>--}}

{{--  --}}

{{--    </div>--}}
{{--</div>--}}

<script type="text/javascript">
    if(window.WebSocket){
        // var webSocket = new WebSocket("ws://120.27.242.10:9503?userId="+11);
        // var webSocket = new WebSocket("ws://log.swoole.com/ws?userId="+11);
        var aVideo = document.getElementById('video');
        var aCanvas = document.getElementById('canvas');
        // var ctx = aCanvas.getContext('2d');

        // var webSocket = new WebSocket("ws://192.168.138.129:9510");
        var webSocket = new WebSocket("ws://120.27.242.10:9510");
        webSocket.onopen = function (event) {
            console.log(event);
            console.log('webSocket 链接成功');
        };
        //收到服务端消息回调
        webSocket.onmessage = function (event) {
            console.log(event);
            // var content = document.getElementById('content');
            // content.innerHTML = content.innerHTML.concat('<p style="margin-left:20px;height:20px;line-height:20px;">'+event.data+'</p>');
            // document.getElementById('photo').src=event.data;
            var photo = document.getElementById('photo');
            photo.src = event.data;
        }

        var sendMessage = function(){
            var data = document.getElementById('message').value;
            webSocket.send(data);
        }
    }else{
        console.log("您的浏览器不支持WebSocket");
    }
</script>


</body>
</html>

