<?php

require_once "AutoLoad.php";
/*conf=array('supervisor'=>array('http://git.local/supervisord.conf','/etc/supervisord.conf'),'haproxy'=>array('http://git.local/haproxy.conf','/etc/haproxy/haproxy.conf'));
$docker = new Docker("1234","4567",$conf);
$docker->start();
*/
$call = new CallPortail();

//$call->stopVM(4,"ZTZlYjMwMTctZWVhMy00YThiLWE4MDItNzJlN2YxYjBhNGFjLzJmZjQ1ZmJkLTQyODktNGE1Zi1hZjYyLWMxY2M4YTIwNTRjNA==");

$call->startVM(4,"ZTZlYjMwMTctZWVhMy00YThiLWE4MDItNzJlN2YxYjBhNGFjLzJmZjQ1ZmJkLTQyODktNGE1Zi1hZjYyLWMxY2M4YTIwNTRjNA==");

//$call->updateVmInfos("4","NjE2NjRjZjAtYzMzOS00Y2YzLWJkODgtMDY2NjdjZjE2MGZjLzJmZjQ1ZmJkLTQyODktNGE1Zi1hZjYyLWMxY2M4YTIwNTRjNA==","toot");

//$callr = new CallRabbit;

//$callr->send("update-plateform","{hello}");
/*
 $queue="update-plateform";
$message="{blabla}";
 require_once '/home/thomas/vendor/autoload.php';
                use PhpAmqpLib\Connection\AMQPConnection;
                use PhpAmqpLib\Message\AMQPMessage;


$exchangeName = $queue;
$queueName = $queue;
$connection = new AMQPConnection('localhost', 5672, 'axa', 'password','/axa');
$channel = $connection->channel();
$channel->queue_declare($queueName,false, true, false, false);
$channel->exchange_declare($exchangeName, 'direct', false, false, false);
$channel->queue_bind($exchangeName,$queueName);
*/
/*              $connection = new AMQPConnection('localhost', 5672, 'axa', 'password',"/axa");
                $channel = $connection->channel();


                $channel->queue_declare('hello', false, false, false, false);
*/
/*                $msg = new AMQPMessage($message);
                $channel->basic_publish($msg, '', $queue);

                echo " [x] Sent 'Hello World!'\n";

                $channel->close();
                $connection->close();
*/
?>
