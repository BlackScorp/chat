<?php

session_start();
if (!isset($_SESSION['username'])) {
    header('Location: setname.php');
    exit;
}
?>

<!DOCTYPE>
<html lang="de">
<head>
    <title>Chat</title>
    <meta charset="UTF-8"/>
    <style>
        body {
            position: absolute;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            margin: 0;
            padding: 0;
        }

        #chat {
            position: relative;
            border: 2px solid black;
            height: 400px;
            width: 500px;
            margin: 100px auto;

        }

        #chatcontent {
            position: relative;
            overflow: hidden;
            height: 378px;
            overflow-y: scroll;
        }

        .ajaxform {
            position: absolute;
            border-top: 1px solid black;
            width: 100%;
            bottom: 0;
            background: white;
            margin: 0;
        }

        .ajaxform input {
            width: 80%;
        }
    </style>
</head>
<body>
<div id="chat">
    <div id="chatframe">
        <div id="chatcontent">
            <ul>

            </ul>
        </div>
    </div>
    <form method="post" action="send.php" class="ajaxform">
        <input type="text" name="text" placeholder="sag was">
        <button type="submit">Senden</button>
    </form>
</div>
<script>
 document.addEventListener('DOMContentLoaded', function () {
    const textField = document.querySelector('.ajaxform input[name="text"]');

    const chatField = document.querySelector('#chatcontent ul');
    const chatFrame = document.getElementById('chatcontent');

    document.querySelector('.ajaxform')
    .addEventListener('submit', function (event) {
            event.preventDefault();
            const formData = new FormData(this);
            const url = this.action;
            const method = this.method;

            if (textField.value.length === 0) {
                return;
            }
            fetch(url, {
                method: method,
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status === "failed") {
                        throw Error(data.message);
                    }
                    textField.value = "";
                })
                .catch((error) => {
                    textField.value = "";
                });
    });


    if (typeof (EventSource) !== "undefined") {
        const source = new EventSource("read.php");
        source.addEventListener('updateText',function(event){
            const data = JSON.parse(event.data);
            const newElement = document.createElement("li");
            newElement.innerHTML = data.message;

            chatField.appendChild(newElement);
            chatFrame.scrollTop = chatFrame.scrollHeight;
        });
    } else {
            alert("Please use another browser");
    }


 });
</script>
</body>
</html>