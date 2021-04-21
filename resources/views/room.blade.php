<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
</head>
<body>
<img src="" id="photo">
<script type="text/javascript">
    if(window.WebSocket){
        // var webSocket = new WebSocket("ws://192.168.138.129:9510");
        // var webSocket = new WebSocket("ws://120.27.242.10:9510");
        var webSocket = new WebSocket("wss://cs.coolr.top/wss/");

        webSocket.onopen = function (event) {
            console.log(event);
            console.log('webSocket 链接成功');
        };
        //收到服务端消息回调
        webSocket.onmessage = function (event) {
            console.log(event);
            var photo = document.getElementById('photo');
            photo.src = event.data;
        };

    }else{
        console.log("您的浏览器不支持WebSocket");
    }
</script>


</body>
</html>
