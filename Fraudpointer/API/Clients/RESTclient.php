<?php

namespace FraudPointer\API\Clients;

require_once "HTTP/Request2.php";

/**
* @package FraudPointer.API.Clients
*/
class RESTClient {
	 
    public function CreateJsonPostRequest($url, $data) {
		$req = new \HTTP_Request2($url);
		$req->setMethod(\HTTP_Request2::METHOD_POST);
		$req->setHeader('Content-type', 'application/json');
		$req->setHeader('Accept-Charset', 'utf-8');
		$req->setHeader('Accept', 'application/json'); 		
		$req->setBody(json_encode($data));
		return json_decode($req->send()->getBody());      	
	 } // CreateJsonPostRequest ()
    //----------------------------
    
} // class RESTClient
//---------------------

?>
