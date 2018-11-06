<?php

require_once __DIR__ . '/vendor/autoload.php';
 set_time_limit(0);
use PhpAmqpLib\Connection\AMQPStreamConnection;
$connection = new AMQPStreamConnection('192.168.1.12', 5672, 'guest', 'guest');
$channel = $connection->channel();

$exchangeName = 'test_direct_exchange';
$routingKey = 'test.direct';
$exchangeType = 'direct';
$queueName = 'test_direct_queue';
//$channel->queue_declare('hello', false, false, false, false);
//申明一个交换机
$channel->exchange_declare($exchangeName,$exchangeType,false,false,false,false);
//申明一个队列
$channel->queue_declare($queueName,false,false,false,null);
//建立绑定关系
$channel->queue_bind($queueName,$exchangeName,$routingKey);
$consumer = 'test-consumer-tag';

echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";
$callback = function($msg) {
    echo " [x] Received ", $msg->body, "\n";
};

//在接收消息的时候调用$callback函数
$channel->basic_consume($queueName, '', false, true, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}
