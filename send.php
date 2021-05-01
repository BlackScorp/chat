<?php
$isPost = isset($_SERVER['REQUEST_METHOD']) && strtoupper($_SERVER['REQUEST_METHOD']) === 'POST';
if(!$isPost){
    header('Location: index.php');
    exit;
}

/** @var PDO $pdo */
$pdo = require_once __DIR__ . '/connection.php';
header('Content-Type:application/json;charset=utf-8');

$username = $_SESSION['username'];
if(!$username){
    http_response_code(500);
    $output = ['status'=>'failed','message' =>'Username is not set'];
    return;
}

$text = filter_input(INPUT_POST, 'text', FILTER_SANITIZE_STRING);
if (!$text) {
    $output = ['status'=>'success','message' =>'OK'];
    echo json_encode($output);
    return;
}
$sql = "INSERT INTO chat SET username=:username, text=:text";
try{
    $statement = $pdo->prepare($sql);
    $statement->execute([':username' => $username, ':text' => $text]);
    $output = ['status'=>'success','message' =>'OK'];
}catch(Exception $e){
    http_response_code(500);
    $output = ['status'=>'failed','message' => $e->getMessage()];
}

echo json_encode($output);