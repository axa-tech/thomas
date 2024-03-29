<?php

require_once __DIR__ . '/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
require_once "AutoLoad.php";
$exchangeName = "create-platform";
$queueName = "create-platform";
$connection = new AMQPConnection('10.226.0.134', 5672, 'axa', 'password','/axa');
$channel = $connection->channel();
$channel->queue_declare($queueName,false, true, false, false);
$channel->exchange_declare($exchangeName, 'direct', true, false, false);
$channel->queue_bind($exchangeName,$queueName);
echo ' [*] Waiting for messages. To exit press CTRL+C', "\n";

$callback = function($msg){
  echo " [x] Received ", $msg->body, "\n";
  sleep(substr_count($msg->body, '.'));
  echo " [x] Done", "\n";
  $call=new CallPortail;
  //$call->decode_message_create($msg->body);
//  $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
};

$channel->basic_qos(null, 1, null);
$channel->basic_consume($queueName, '', false, false, false, false, $callback);

while(count($channel->callbacks)) {
    $channel->wait();
}

$channel->close();
$connection->close();

?>
