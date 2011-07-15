<?php

namespace Fraudpointer\API;

/**
* Fraudpointer.API.IClient is the interface that each PHP client should implement in order to be used as a client proxy to access
* FraudPointer Service. <br/><b>Important Note:</b> Fraudpointer PHP client library comes with a ready to use implementation of this
* service. You need to use Fraudpointer.API.ClientFactory to instantiate this implementation and start using FraudPointer Service.
*
* As a user of the FraudPointer Client Library you should declare a variable of IClient type and then use
* ClientFactory::Construct() to get an instance of a class that implements this interface.
*  
* Below, you can see a sample client that uses IClient to communicate with FraudPointer Server:
* 
* <code>
* function BaseUrl () {
*    return "https://production.fraudpointer.com/api/assessment_sessions";
* } // BaseUrl ()
* //--------------
*
* function ApiKey () {
*  return "45440beccd884ddsa688asdfasda18b09"; 
* } // ApiKey ()	
* //-------------
*
* function Main()
* {
*        // Instantiate the client object that implements the IClient interface
*        //
*        $client = \FraudPointer\API\ClientFactory::Construct(BaseUrl(), ApiKey()); 
*
*        // 1st STEP: Create Assessment Session. This goes to the FraudPointer Server and gets back an 
*        //           Assessment Session object
*        //
*        $assessment_session = $client->CreateAssessmentSession();  	                       
*
*        // 2nd STEP: Create an Event object of "Generic" Type. 
*        //
*        $event = new \Fraudpointer\API\Models\Event(\Fraudpointer\API\Models\Event::$GENERIC_EVENT);
*
*        // 3rd STEP: Add data to the created Event
*
*        $event.AddData("BookingCode", "B2YB32");
*        $event.AddData("StartDate", "20110322");
*        $event.AddData("ReservationDate", "20110321");            
*
*        // 4th STEP: Send Event to FraudPointer Server to store it in the database
*        //           alongside with the already stored AssessmentSession
*        //
*        $client.AppendEventToAssessmentSession($assessmentSession, $event);
*     
*        // Repeat step 3 and 4. Note that you can create as many Events as you like for the 
*        //                      same Assessment Session
*        // 
*        $event = new \Fraudpointer\API\Models\Event(\Fraudpointer\API\Models\Event::$FAILED_PAYMENT);
*
*        $event.AddDateData("AttemptDate", time());
*
*        $event.AddData("BILLING_ADDRESS_STREE_NAME", "Othonos");
*    
*        $client.AppendEventToAssessmentSession($assessmentSession, $event);
*
*        // 5th STEP: Ask FraudPointer Server to carry out a Fraud Assessment. This is the real value
*        //           of the Service because it can tell you real time about the fraud risk of the
*        //           transaction that you are trying to handle.
*        // 
*        $fa = $client.CreateFraudAssessment($assessmentSession, false);
*        return $fa.score;
*   }
* </code>
*
* @package Fraudpointer.API
*/
interface IClient
{
	/**
	* @return AssessmentSession An object of type AssessmentSession.
        */
	public function CreateAssessmentSession();
	
        /**
        * @param AssessmentSession $assessment_session Parameter of type AssessmentSession.
	* @param Event $param_event Parameter of type Event.
	* @return Event An object of type Event.
        */
	public function AppendEventToAssessmentSession($assessment_session, $param_event);

        /**
        * @param AssessmentSession $assessment_session 
        * @param boolean $interim 
	* @param FraudAssessment
   	*/
	public function CreateFraudAssessment($assessment_session, $interim);	
	
	/**
	* @param string $credit_card_number The Credit Card number in clear format.
 	* @return string The Credit Card hash that you should send over to FraudPointer Server.
	*/	
	public function CreditCardHash($credit_card_number);
	
} // interface IClient
//---------------------


?>
