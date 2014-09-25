function decode_message ($message){
        $tab = json_decode($message);
        $bubbleid= $tab->platformId;
        $vms=$tab->vms;
        foreach($vms as $vm){
                createVM($vm->name,$bubbleid,$vm->adminPass);
        }
}
decode_message($message);

function createVM($name,$bubble,$adminPass="password"){
        $image="MzA4OTJmNDItMWJhZi00MWZkLTg5MTItMDY1OWIwYTBmNTljLzJmZjQ1ZmJkLTQyODktNGE1Zi1hZjYyLWMxY2M4YTIwNTRjNA==";
        $call = new CallPortail();
        $call->setCookie();
        $b=$call->getBubble($bubble);
        $f=$call->getFlavor($bubble);
        $i=$call->getImages($bubble);
        $n=$call->getNetwork($bubble);
        $e=$call->getEnvironment($bubble);
        $subnet=$n[0]->subnets[0]->providerId;
        $acreate=array("type"=>$b->type,"name"=>$name,"networks"=>$n[0]->providerId,"flavor"=>$f[0]->providerId,"image"=>$image,"adminPass"=>$adminPass,"environmentId"=>$e[0]->providerId,"subnetId"=>$subnet);
        var_dump($acreate);
        $json=json_encode($acreate);

}

