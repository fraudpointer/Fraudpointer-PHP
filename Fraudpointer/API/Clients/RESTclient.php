<?php

namespace Fraudpointer\API\Clients;

require_once "HTTP/Request2.php";

/**
* @package Fraudpointer.API.Clients
*/
class RESTClient {
	 
    public function CreateJsonPostRequest($url, $data) {
		$req = new \HTTP_Request2($url);
		$req->setMethod(\HTTP_Request2::METHOD_POST);
		$req->setHeader('Content-type', 'application/json');
		$req->setHeader('Accept-Charset', 'utf-8');
		$req->setHeader('Accept', 'application/json'); 		
		$req->setBody(json_encode($data));
		$body_got = $req->send()->getBody();
		//print "Body returned for posting on url: " . $url . "> " . $body_got . "\n";
		return json_decode($body_got);      	
	 } // CreateJsonPostRequest ()
    //----------------------------
    
    public function CreateJsonGetRequest($url) {
		$req = new \HTTP_Request2($url);
		$req->setMethod(\HTTP_Request2::METHOD_GET);
		$req->setHeader('Content-type', 'application/json');
		$req->setHeader('Accept-Charset', 'utf-8');
		$req->setHeader('Accept', 'application/json'); 		
		$body_got = $req->send()->getBody();
		//print "Body returned for getting from url: " . $url . "> " . $body_got . "\n";		
		return json_decode($req->send()->getBody());
    } // CreateJsonGetRequest()
    //-------------------------
    
} // class RESTClient
//---------------------

?>
