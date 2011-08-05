<?php

namespace Fraudpointer\API;

/**
* Thrown by all IClient methods in case an error takes place and method cannot return its results normally.
* 
* You should always put your IClient methods into <kbd>try/catch</kbd> blocks and make sure you catch this exception. 
* If you catch this exception, the message borne will be adequate to explain to you what has gone wrong. Especially the
* message borne by the <kbd>InnerException</kbd> of the <kbd>Exception</kbd> caught.
*
* Here is how you can wrap your code arround <kbd>try/catch</kbd> block
* and on error get information about what has gone wrong:
* <code>
* try {
*  $event = new \Fraudpointer\API\Models\Event(\Fraudpointer\API\Models\Event::$FAILED_PAYMENT);
*  $event->AddData("MERCHANT_REFERENCE", GetAcmeOrderNumber());
*		
*  // PURCHASE_DATE
*  $event->AddDateValue("PURCHASE_DATE", time()); 
*
*  $event_returned = $client->AppendEventToAssessmentSession($assessment_session, $event);
*  return $event_returned;
* }
* catch (\Fraudpointer\API\ClientException $e) {
   return $e->getMessage();
* } </code>     
*
* @package Fraudpointer.API
*/
class ClientException extends Exception {
	
} // class ClientException
//------------------------- 
	
?>
