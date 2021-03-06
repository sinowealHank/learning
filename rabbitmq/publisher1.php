<?php

//引用所需文件
require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;

//建立一个连接通道，声明一个可以发送消息的队列hello
$connection = new AMQPStreamConnection('192.168.1.12', 5672, 'guest', 'guest');
$channel = $connection->channel();
$exchangeName = 'test_direct_exchange';
$routingKey = 'test.direct';
//$channel->queue_declare('hello', false, false, false, false);

//定义一个消息，消息内容为Hello World!
$msg = new AMQPMessage('Hello World!');
$channel->basic_publish($msg, $exchangeName, $routingKey);

//发送完成后打印消息告诉发布消息的人：发送成功
echo " [x] Sent 'Hello World!'\n";
//关闭连接
$channel->close();
