<?php

/**
Classe d'appel au portail 

*/
class CallPortail{
	var $proxy="http://10.225.23.242:8080/";
	var $base_url="http://portal.axa-cloud.com";
	var $cookie;
	function __construct(){
		$this->setCookie();
	}
	function getBubbles(){
		//$return=array();
		$url=$this->base_url."/rest/projects/";
		$json= $this->Call($url);
		$tabjson= json_decode($json);
		return $tabjson;
	}
	function getBubble($bubbleid){
		$url=$this->base_url."/rest/projects/".$bubbleid."";
                //$url="http://portal.axa-cloud.com/rest/projects/4/flavors";
                $json= $this->Call($url);
                $tabjson= json_decode($json);
                return $tabjson;
	}
	function getFlavor($bubbleid){
		$url=$this->base_url."/rest/projects/".$bubbleid."/flavors";
		//$url="http://portal.axa-cloud.com/rest/projects/4/flavors";
                $json= $this->Call($url);
                $tabjson= json_decode($json);
                return $tabjson;
	}
	function getImages($bubbleid){
		$url=$this->base_url."/rest/projects/".$bubbleid."/images";
		$json= $this->Call($url);
                $tabjson= json_decode($json);
                return $tabjson;
	}
	function getNetwork($bubbleid){
		$url=$this->base_url."/rest/projects/".$bubbleid."/networks";
                $json= $this->Call($url);
                $tabjson= json_decode($json);
                return $tabjson;
	}
	function getEnvironment($bubbleid){
                $url=$this->base_url."/rest/projects/".$bubbleid."/environments";
                $json= $this->Call($url);
                $tabjson= json_decode($json);
                return $tabjson;
        }
	function setCookie(){
		$this->cookie= "JSESSIONID=76658DDC6398FC556BF89459B0005273; path=/; domain=portal.axa-cloud.com; HttpOnly"	;
	}
	function getCookie(){
		return  $this->cookie;
	}
	private function Call($url,$method="GET",$type="",$data="",$verbose=false){
		echo "CALL $url $method : $type : $data ";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
		curl_setopt($ch, CURLOPT_PROXY, "$this->proxy");
//		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_VERBOSE,$verbose);
	//	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_ENCODING, "");
	//	curl_setopt($ch, CURLOPT_CUSTOMREQUEST,$method);
		curl_setopt ($ch, CURLOPT_COOKIE, "$this->cookie" );
	//	curl_setopt ($ch, CURLOPT_HEADER, 1);
		if($type!=""){
			curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/$type",
    			'Content-Length: ' . strlen($data)));
		}
		if($data!=""){
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		}
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
		
		$content=curl_exec($ch);
		echo "FIN DE CURL"; 
		$curl_info = curl_getinfo($ch);
		var_dump($curl_info);
		var_dump($content);
		//die;
		curl_close($ch);
		return $content;
	}
	private function CallApi($url,$data=""){
		$method="PUT";
		$type="json";
		$ch = curl_init();
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_VERBOSE, false);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch,CURLOPT_ENCODING, "");
              	curl_setopt($ch, CURLOPT_CUSTOMREQUEST,$method);
                curl_setopt ($ch, CURLOPT_COOKIE, "$this->cookie" );
                if($type!=""){
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                        "Content-Type: application/$type",
                        'Content-Length: ' . strlen($data))
                        );
                }
                if($data!=""){
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                }
                curl_setopt($ch, CURLOPT_TIMEOUT, 60);
                curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
          //      echo "CURL";
                $content=curl_exec($ch);
                //ho "FIN DE CURL";
                $curl_info = curl_getinfo($ch);
                
	//	var_dump($curl_info);
                //var_dump($content);
                curl_close($ch);
		echo "???";
                return $content;
	}
	/**
	decode json to create a plateform
	*/
	public function decode_message_create($message){
        	$tab = json_decode($message);
        	$bubbleid= $tab->platformRemoteId;
        	$vms=$tab->vms;
        	foreach($vms as $vm){
                	$return = $this->createVM($vm->name,$bubbleid,$vm->adminPass);
			sleep(10);
			$idApi=$vm->id;
			$idVm=$return->providerId;
			$this->updateVmInfos($bubbleid,$idVm,$idApi);
        	}
		return true;
	}

	private function createVM($name,$bubble,$adminPass="password"){
        	$image="MzA4OTJmNDItMWJhZi00MWZkLTg5MTItMDY1OWIwYTBmNTljLzJmZjQ1ZmJkLTQyODktNGE1Zi1hZjYyLWMxY2M4YTIwNTRjNA==";
        	$call = new CallPortail();
        	$call->setCookie();
        	$b=$call->getBubble($bubble);
		if($b == null or $b == ""){
			echo "Soucis de cookie ou ... de bubble";
			return false;
			//	exit;
		} else {
        	$f=$call->getFlavor($bubble);
        	$i=$call->getImages($bubble);
        	$n=$call->getNetwork($bubble);
        	$e=$call->getEnvironment($bubble);
        	$subnet=$n[0]->subnets[0]->providerId;
        	$acreate=array("type"=>$b->type,"name"=>$name,"networks"=>$n[0]->providerId,"flavor"=>$f[0]->providerId,"image"=>$image,"adminPass"=>$adminPass,"environmentId"=>$e[0]->providerId,"subnetId"=>$subnet);
        	//var_dump($acreate);
       		$json=json_encode($acreate);
		$url=$this->base_url."/rest/projects/".$bubble."/instances";
        	//$url="http://portal.axa-cloud.com/rest/projects/4/instances";
		$return=$call->Call($url,"POST","json",$json,false);
       	//	var_dump($return);
		echo "VM OK $return";
	//	die;
		return json_decode($return);
		}
		
	}
	public function updateVmInfos($idBubble,$idVm,$idApi){
                //$return=array();
                $url=$this->base_url."/rest/projects/".$idBubble."/instances/".$idVm;
		//ex:http://portal.axa-cloud.com/rest/projects/4/instances/NjE2NjRjZjAtYzMzOS00Y2YzLWJkODgtMDY2NjdjZjE2MGZjLzJmZjQ1ZmJkLTQyODktNGE1Zi1hZjYyLWMxY2M4YTIwNTRjNA==/
                //rl="http://portal.axa-cloud.com/rest/projects/4/instances/MjhjOWNhODYtZjUyYi00ODdmLThhNmItMDZiM2UzZTkzYWRkLzJmZjQ1ZmJkLTQyODktNGE1Zi1hZjYyLWMxY2M4YTIwNTRjNA==";
		$json=$this->Call($url,"GET","","",true);
                $tabjson= json_decode($json);
         /*       var_dump($tabjson);
	 	$message='{

    "accessIPv4": null,
    "accessIPv6": null,
    "addresses": [
        {
            "dnsName": null,
            "ipAddress": null,
            "ipVersion": null,
            "macAddress": "00:15:5D:04:CC:24",
            "network": null,
            "providerId": "9745746c-ac9e-4101-9aa8-62c921ad91b9/2ff45fbd-4289-4a5f-af62-c1cc8a2054c4",
            "type": null
        }
    ],
    "availabilityZone": null,
    "created": "2014-05-05T17:52:10Z",
    "fault": [
        {
            "code": "DeleteCheckpoint",
            "completed": true,
            "created": "2014-09-24T19:41:24Z",
            "details": "",
            "error": false,
            "message": "Remove checkpoint",
            "progressValue": "100",
            "username": null
        },
        {
            "code": "DeleteCheckpoint",
            "completed": true,
            "created": "2014-09-24T19:38:05Z",
            "details": "",
            "error": false,
            "message": "Remove checkpoint",
            "progressValue": "100",
            "username": null
        }
    ],
    "flavor": {
        "disk": "45860503552",
        "name": "2 CPU / 4.00 Gb RAM",
        "providerId": null,
        "ram": 4096,
        "swap": null,
        "vcpus": "2"
    },
    "generation": 1,
    "host": null,
    "image": {
        "created": null,
        "description": "Red Hat Enterprise Linux 6 (64 bit)",
        "metadata": null,
        "minDisk": null,
        "minRam": null,
        "name": "Red Hat Enterprise Linux 6 (64 bit)",
        "osType": "Linux",
        "progress": null,
        "providerId": null,
        "size": null,
        "status": null,
        "updated": null
    },
    "instanceName": "CLOUDX0XSL8ELAE",
    "keyName": null,
    "metadata": null,
    "name": "EURREWEBRHEL001",
    "progress": null,
    "providerId": "NjE2NjRjZjAtYzMzOS00Y2YzLWJkODgtMDY2NjdjZjE2MGZjLzJmZjQ1ZmJkLTQyODktNGE1Zi1hZjYyLWMxY2M4YTIwNTRjNA==",
    "securityGroups": null,
    "state": "Running",
    "status": "running",
    "updated": "2014-09-25T03:26:25Z"

}';	
	$tabjson= json_decode($message);*/
	//echo "Update Infos";
	//var_dump($tabjson);
	sleep(1);
	//die;
	$tabforapi=array("id"=>$idApi,"VMRemoteId"=>$tabjson->providerId,"name"=>$tabjson->name,"instanceName"=>$tabjson->instanceName,"ip"=>$tabjson->accessIPv4,"state"=>$tabjson->state,"status"=>$tabjson->status);
	//var_dump(json_encode($tabforapi));
	$jsonsend=json_encode($tabforapi);
	$this->CallApi("http://api.local/rest/vm/".$idApi,$jsonsend);
	return true;	
	}

}

?>
