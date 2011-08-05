<?php

require_once(dirname(__FILE__) . "/../Fraudpointer/API/ClientFactory.php");
require_once(dirname(__FILE__) . "/../Fraudpointer/API/Models/Event.php");

function FraudPointerScriptSource() {
	//return "https://production.fraudpointer.com/fp.js";
	return "http://10.0.0.123:3000/fp.js";	
} // FraudPointerScriptSource ()
//--------------------------------

function HiddenFraudPointerSessionIdField() {
	return "hdn_fraud_pointer_session_id";
} // HiddenFraudPointerSessionIdField()
//--------------------------------------

function BaseUrl () {
	//return "https://production.fraudpointer.com/api/assessment_sessions";
	return "http://10.0.0.123:3000/api";
} // BaseUrl ()
//--------------

function ApiKey () {
	//throw new Exception('<put your domain key in below return statement and uncomment. Then remove this throw statement.');
	return "791cdeef55d0e5a78a357e1d5ffbcc1dc532dff4f2a6d26ad3821af646ff8532"; 
} // ApiKey ()
//-------------

function CalculatePrice($city_from_value, $city_to_value) {
    return ($city_from_value * $city_to_value); 
} // calculate_price()
//-------------------

//---- The Application Database ---------------------------------------//
$cities_from = array(1 => "London", 2 => "Madrid", 3 => "Paris", 4 => "Rome");
$cities_to = array(1 => "Atlanta", 2 => "Chicago", 3 => "New York", 4 => "Los Angeles"); 
//---------------------

function GetFraudpointerClient() {
	// I need the $client object in order to access Fraud Pointer Server. Since, I might have one in $_SESSION (which is not necessary though)
	// let me try that one first
	$client = $_SESSION["fraudpointer_client"];
	if ( $client == null ) {
	   $client = \FraudPointer\API\ClientFactory::Construct(BaseUrl(), ApiKey()); 
	}
	// save client object in SESSION
	$_SESSION["fraudpointer_client"] = $client;
	return $client;
} // GetFraudpointerClient()
//---------------------------

// <summary>Creates Assessment Session</summary>
// <returns>The Assessment Session</returns>
function GetOrCreateAssessmentSession ($client) {

	// get the assessment session stored in SESSION (if any stored). 
	$assessment_session = $_SESSION["fraudpointer_assessment_session"];
	
	if ( $assessment_session == null ) {
  	  try {
          $assessment_session = $client->CreateAssessmentSession();  	                       
  	       // save assessment session to SESSION
  	       $_SESSION["fraudpointer_assessment_session"] = $assessment_session;
  	   	 return $assessment_session;       	       
  	  }
  	  catch (\Fraudpointer\API\ClientException $e) {
  	  		return null;
  	  }
   }
   else {
		return $assessment_session;   	
   }             
} // GetOrCreateAssessmentSession ()
//---------------------------------

function GetNumberOfFailedPaymentAttempts () {
	$number_of_failed_payment_attempts = $_SESSION["number_of_failed_payment_attempts"];
	if ( $number_of_failed_payment_attempts == null ) {
		$number_of_failed_payment_attempts = 0;
		$_SESSION["number_of_failed_payment_attempts"] = $number_of_failed_payment_attempts;	 
   }   
   return $number_of_failed_payment_attempts;
} // GetNumberOfFailedPaymentAttempts ()
//---------------------------------------

function GetAcmeOrderNumber () {
	
	$acme_order_number = $_SESSION["acme_order_number"];
	if ( $acme_order_number == null || $acme_order_number == 0 ) {
		$acme_order_number = rand(1, 100000);
		$_SESSION["acme_order_number"] = $acme_order_number;	
	}
	return $acme_order_number;
	
} // GetAcmeOrderNumber ()
//-------------------------

//---- A function that is used only for testing the bevaviour of evaluating the 
//     charge result
function SendDataToBankForCharging() {
	
	$c = $_POST["chckbx_succeed_with_payment"];
	$success = $c == "on";
	return $success;	
	
} // SendDataToBankForCharging()
//-------------------------------

function CheckForStartOver () {
	
  if ($_POST["sbmt_clear_as_and_start_over"]) {
	  $assessment_session = null;
	  $_SESSION["fraudpointer_assessment_session"] = $assessment_session;
	  header("Location: checkout.php");
	  exit();		
  }
	
} // CheckForStartOver ()
//------------------------

function CreateAndSendCheckoutEvent($client, $assessment_session) {
	
	// Create a new Event Instance
	$event = new \Fraudpointer\API\Models\Event(\Fraudpointer\API\Models\Event::$CHECKOUT_EVENT);
	
	// ACME_DEPARTURE_CITY is an Account Custom Session Attribute defined in Fraud Pointer Application
	$event->AddData("ACME_DEPARTURE_CITY", $cities_from[$_POST["select_city_from"]]);
	
	// ACME_ARRIVAL_CITY is an Account Custome Session Attribute defined in Fraud Pointer Application
	$event->AddData("ACME_ARRIVAL_CITY", $cities_to[$_POST["select_city_to"]]);
	
	// PURCHASE_AMOUNT is a System Session Attribute
	$event->AddDecimalData("PURCHASE_AMOUNT", CalculatePrice($_POST["select_city_from"], $_POST["select_city_to"]));
	
	// CC_HASH is a System Attribute. You should always use the CreditCardHash to hash your 
	// Credit Card Number and send it to Fraud Pointer Server.
	$event->AddData("CC_HASH", $client->CreditCardHash($_POST["txtbx_credit_card_number"]));			
	
	// CC_CARD_HOLDER_NAME is a System Attribute.
	$event->AddData("CC_CARD_HOLDER_NAME", $_POST["txtbx_card_holder_name"]);
	
	// CC_BANK_NAME is a System Attribute
	$event->AddData("CC_BANK_NAME", $_POST["txtbx_bank_name_of_card"]);
	
	// CREDIT_CARD_FIRST_6_DIGITS
	$event->AddData("CREDIT_CARD_FIRST_6_DIGITS", substr($_POST["txtbx_credit_card_number"], 0, 6));
	
	// E_TRAVEL_SA_PURCHASE_DATE	
	$event->AddDateData("PURCHASE_DATE", time()); 
		
	try {		
		// having populated the Event object, we will send it to FraudPointer Server
		$event_returned = $client->AppendEventToAssessmentSession($assessment_session, $event);
		
		return $event_returned;
		
	}
	catch (\Fraudpointer\API\ClientException $e) {
		return $e->getMessage();	
	}
	
} // CreateAndSendCheckoutEvent ()
//--------------------------------

function CreateAndSendSuccessfulPaymentEvent ($client, $assessment_session) {

	// Create a new Event Instance
	$event = new \Fraudpointer\API\Models\Event(\Fraudpointer\API\Models\Event::$PURCHASE_EVENT);
	$event->AddData("MERCHANT_REFERENCE", GetAcmeOrderNumber());
	
        // PURCHASE_DATE
	$event->AddDateData("PURCHASE_DATE", time()); 
	
	try {		
		// having populated the Event object, we will send it to FraudPointer Server
		$event_returned = $client->AppendEventToAssessmentSession($assessment_session, $event);
		return $event_returned;
	}
	catch (\Fraudpointer\API\ClientException $e) {
		return $e->getMessage();
	}

} // CreateAndSendSuccessfulPaymentEvent ()
//-------------------------------------------

function CreateAndSendFailedPaymentEvent ($client, $assessment_session) {
	try {
           $event = new \Fraudpointer\API\Models\Event(\Fraudpointer\API\Models\Event::$FAILED_PAYMENT);
	   $event->AddData("MERCHANT_REFERENCE", GetAcmeOrderNumber());
		
   	   // PURCHASE_DATE
	   $event->AddDateData("PURCHASE_DATE", time()); 

		$event_returned = $client->AppendEventToAssessmentSession($assessment_session, $event);
		return $event_returned;
	}
	catch (\Fraudpointer\API\ClientException $e) {
	  return $e->getMessage();
   }
} // CreateAndSendFailedPaymentEvent ()
//--------------------------------------

function ResetSessionVars () {
							
	$_SESSION["number_of_failed_payment_attempts"] = 0;
						
	$_SESSION["acme_order_number"] = 0;
	
	$_SESSION["fraudpointer_assessment_session"] = null;

} // ResetSessionVars()
//----------------------

	
?>
