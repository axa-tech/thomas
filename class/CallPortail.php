<?php

/**
Classe d'appel au portail 

*/
class CallPortail{
	var $proxy="http://10.225.23.242:8080/";
	var $base_url="http://portal.axa-cloud.com";
	var $cookie;
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
		$this->cookie= "JSESSIONID=B959D9C215A3A3A960A25C6EAD1F34EB; path=/; domain=portal.axa-cloud.com; HttpOnly"	;
	}
	function getCookie(){
		return  $this->cookie;
	}
	private function Call($url,$method="GET",$type="",$data=""){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_HTTPPROXYTUNNEL, 0);
		curl_setopt($ch, CURLOPT_PROXY, "$this->proxy");
//		curl_setopt($ch,CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_VERBOSE, false);
	//	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch,CURLOPT_ENCODING, "");
	//	curl_setopt($ch, CURLOPT_CUSTOMREQUEST,$method);
		curl_setopt ($ch, CURLOPT_COOKIE, "$this->cookie" );
	//	curl_setopt ($ch, CURLOPT_HEADER, 1);
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
		//ho "CURL";
		$content=curl_exec($ch);
		//ho "FIN DE CURL"; 
		$curl_info = curl_getinfo($ch);
		//var_dump($curl_info);
		curl_close($ch);
		return $content;
	}
	/**
	decode json to create a plateform
	*/
	public function decode_message_create($message){
        	$tab = json_decode($message);
        	$bubbleid= $tab->platformId;
        	$vms=$tab->vms;
        	foreach($vms as $vm){
                	$this->createVM($vm->name,$bubbleid,$vm->adminPass);
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
			exit;
		}
        	$f=$call->getFlavor($bubble);
        	$i=$call->getImages($bubble);
        	$n=$call->getNetwork($bubble);
        	$e=$call->getEnvironment($bubble);
        	$subnet=$n[0]->subnets[0]->providerId;
        	$acreate=array("type"=>$b->type,"name"=>$name,"networks"=>$n[0]->providerId,"flavor"=>$f[0]->providerId,"image"=>$image,"adminPass"=>$adminPass,"environmentId"=>$e[0]->providerId,"subnetId"=>$subnet);
        //var_dump($acreate);
       		$json=json_encode($acreate);
		$s->base_url."/rest/projects/".$bubbleid."/instances";
        	$return=$this->Call($url,"GET","json",$json);
       //	var_dump($return);
		return $return;
	}

}

?>
