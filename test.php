<?php

require_once "AutoLoad.php";
$conf=array('supervisor'=>array('http://git.local/supervisord.conf','/etc/supervisord.conf'));
$docker = new Docker("1234","4567",$conf);
$docker->start();
?>
