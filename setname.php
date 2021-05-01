<?php
session_start();
$isPost = isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD']) === 'POST';
if($isPost){
    $_SESSION['username'] = filter_input(INPUT_POST,'username',FILTER_SANITIZE_STRING);
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE>
<html lang="de">
<head>
    <title>Chat</title>
    <meta charset="UTF-8"/>
</head>
<body>
<form method="post">
   <label for="username">name:</label><input name="username" type="text" id="username" placeholder="sag mir deinen namen">
    <button>Senden</button>
</form>
</body>
</html>
