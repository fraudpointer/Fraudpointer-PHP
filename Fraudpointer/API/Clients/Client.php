<?php

namespace Fraudpointer\API\Clients;

require_once dirname(__FILE__) . "/../IClient.php";
require_once dirname(__FILE__) . "/RESTclient.php";
require_once dirname(__FILE__) . "/../RequestWrappers/RequestKey.php";
require_once dirname(__FILE__) . "/../RequestWrappers/RequestKeyAndEvent.php";
require_once dirname(__FILE__) . "/../RequestWrappers/RequestKeyAndFraudAssessment.php";
require_once dirname(__FILE__) . "/../Models/FraudAssessment.php";

//-------------------- API Client Proxy Object -------------------------------------------------------------------//
/**
* @package Fraudpointer.API.Clients
*/
class Client implements \Fraudpointer\API\IClient {
	
	private $_base_url;
   private $_api_key;
   	
   //-------- Constructor ----------------------------//
	public function __construct($base_url, $api_key) {
		$this->_base_url = $base_url;
		$this->_api_key = $api_key;
	} // __construct ()
	//------------------//
		
	// ----------------- Create a new Assessment Session from Fraud Pointer Server ---------------------------//
	public function CreateAssessmentSession () {

      try {
			$rest = new RESTclient();
						
		   $data = new \Fraudpointer\API\RequestWrappers\RequestKey();
		   $data->key = $this->_api_key;
		   
			$response = $rest->CreateJsonPostRequest($this->_base_url, $data);			
			
			return $response->assessment_session;      	
      }
      catch (Exception $e) {
      	throw new ClientException("Failed creating assessment session", 0, $e);
      }
		
	} // CreateAssessmentSession ()
   //-------------------------------------------------------------------------------------------------------//
   
   //---------------- Appending Events to Existing Assessment Session --------------------------------------//
   public function AppendEventToAssessmentSession($assessment_session, $event) {

		try {
			$rest = new RESTclient();
	
			$data = new \Fraudpointer\API\RequestWrappers\RequestKeyAndEvent();
			$data->key = $this->_api_key;
			$data->event = $event;
			
			$response = $rest->CreateJsonPostRequest($this->_base_url . "/" . $assessment_session->id . "/events", $data);
			
			return $response->event;		
		}
		catch (Exception $e) {
			throw new \Fraudpointer\API\ClientException("Failed sending events", 0, $e);
		}

   } //AppendEventToAssessmentSession
   //---------------------------------
   
   //----------------- CreateFraudAssessment for existing Assessment Session --------------------------------//
   public function CreateFraudAssessment($assessment_session, $interim) {
   	try {
	   	$rest = new RESTclient();
	   	
	   	$data = new \Fraudpointer\API\RequestWrappers\RequestKeyAndFraudAssessment();
	   	$data->key = $this->_api_key;
	   	$data->fraud_assessment = new \Fraudpointer\API\Models\FraudAssessment();
	   	$data->fraud_assessment->interim = $interim;
	   	
	   	$response = $rest->CreateJsonPostRequest($this->_base_url . "/" . $assessment_session->id . "/fraud_assessments", $data);
	   	
	   	return $response->fraud_assessment;
	   	
   	}
   	catch (Exception $e) {
   		throw new \Fraudpointer\API\ClientException("Failed to create fraud assessment", 0, $e);
   	}

   } // CreateFraudAssessment ()
   //---------------------------
   	
   public function CreditCardHash($credit_card_number) {   	
      $salt = "HVK+gw==";     
   	return base64_encode(hash('sha256', $data . $salt, true));
   } // CreditCardHash()
   //--------------------
   	
} // class Client
//---------------- end of Client class definition ----------------------------------------------------------//

?>
