<?php

/** @var PDO $pdo */
$pdo = require_once __DIR__ . '/connection.php';

$lastId = 0;

$sql = "SELECT id,username,text,created FROM chat 
WHERE created >= DATE_SUB(NOW(),INTERVAL 2 SECOND ) AND id > :lastId LIMIT 1";
$statement = $pdo->prepare($sql);


header('Content-Type: text/event-stream');
header('Cache-Control: no-cache');

while (true) {
    try{
    $statement->execute(
        [':lastId' => $lastId]
    );
    while ($row = $statement->fetch()) {
        $data = [
            'message' => sprintf('<i>%s</i> <b>%s</b>: %s', $row['created'], $row['username'], $row['text'])
        ];
        echo "event: updateText\n";
        echo 'data: ' . json_encode($data);
        echo "\n\n";
        $lastId = $row['id'];
    }
}catch(Exception $e){
    echo "event: showError\n";
    echo 'data: ' . json_encode(['message' => $e->getMessage()]);
    echo "\n\n";
}
    ob_end_flush();
    flush();
    session_write_close();

    if (connection_aborted()) {
        break;
    }
    sleep(1);
}