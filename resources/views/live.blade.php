<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> 直播 </title>
</head>
<body>

<video id="video" width="640" height="480" autoplay></video>

<button id="snap">Snap Photo</button>

<canvas id="canvas" width="640" height="480"></canvas>
<h2>按钮模拟拍照</h2>
</body>
<script type="text/javascript">
    const constraints = {
        video: true,
        audio: false
    };

    // var webSocket = new WebSocket("ws://192.168.138.129:9510");
    // var webSocket = new WebSocket("ws://120.27.242.10:9510");
    var webSocket = new WebSocket("wss://cs.coolr.top/wss/");
    var aVideo = document.getElementById('video');
    var aCanvas = document.getElementById('canvas');
    var ctx = aCanvas.getContext('2d');
    navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia; //获取媒体对象（这里指摄像头）
    if (!navigator.getUserMedia) {
      alert('没有获取到摄像头！');
    }
    navigator.getUserMedia(constraints, gotStream, noStream); //参数1获取用户打开权限；参数二成功打开后调用，并传一个视频流对象，参数三打开失败后调用，传错误信息

    function draw() {
        ctx.drawImage(aVideo,0,0);
        webSocket.send(aCanvas.toDataURL('image/jpeg',0.6));
        setTimeout(draw,50);
    }

    webSocket.onopen = function (event) {
        console.log('链接成功');
        draw();
        // gotStream();
    };

    webSocket.onmessage = function (event) {
        console.log(event);
    };

    function gotStream(stream) {

        aVideo.srcObject = stream;

        aVideo.onerror = function() {
            stream.stop();
        };
        stream.onended = noStream;
        aVideo.onloadedmetadata = function() {
            console.log('摄像头成功打开');
        };

    }

    function noStream(err) {
        alert(err);
    }

</script>

</html>
