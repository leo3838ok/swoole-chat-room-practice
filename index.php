<html>
<head>
    <meta charset="UTF-8">
    <title>Swoole Web sockets practice</title>
    <script>
        try {
            // 連接服務器
            var ws = new WebSocket("ws://127.0.0.1:9501");
            ws.onmessage = function (event) {
                var chat_content = document.getElementById("chat_content");
                chat_content.innerHTML = chat_content.innerHTML + event.data + "<br />";
            };
            ws.onclose = function () {
                alert("聊天室已斷線");
            };
            ws.onerror = function () {
                alert("WebSocket異常");
            };
        } catch (ex) {
            alert(ex.message);
        }

        function SendData() {
            try {
                var content = document.getElementById("content").value;
                if (content) {
                    ws.send(content);
                }

            } catch (ex) {
                alert(ex.message);
            }
        }
    </script>
</head>
<body>
<textarea title="發送內容" id="content"></textarea>
<button id='ToggleConnection' type="button" onclick='SendData();'>發送</button>
<br/><br/>
<div id="chat_content"></div>
</body>
</html>