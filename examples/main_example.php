<?php

require_once dirname(__FILE__) . "/Fraudpointer/API/ClientFactory.php";
require_once dirname(__FILE__) . "/Fraudpointer/API/Models/AssessmentSession.php";
require_once dirname(__FILE__) . "/Fraudpointer/API/Models/Case.php";
require_once dirname(__FILE__) . "/Fraudpointer/API/Models/Event.php";
require_once dirname(__FILE__) . "/Fraudpointer/API/Models/FraudAssessment.php";
require_once dirname(__FILE__) . "/Fraudpointer/API/Models/Profile.php";

/*
Call this script from the command line. Give it as an argument to php.

Example:

php main_example.php -u <base fraudpointer api url> -k <your api key>

Note that <base fraudpointer api url> needs to be "https://production.fraudpointer.com/api" and <your api key> needs to be the
api key that corresponds to the domain you have activated in your Fraudpointer Account.
 
*/

function Main($base_url, $api_key)
{
       // Instantiate the client object that implements the IClient interface
       //
       print "About to instantiate the Fraudpointer Client...\n";       
       $client = \FraudPointer\API\ClientFactory::Construct($base_url, $api_key);
       print "...done\n"; 

       // 1st STEP: Create Assessment Session. This goes to the FraudPointer Server and gets back an 
       //           Assessment Session object
       //
       print "About to create an Assessment Session...\n";
       $assessment_session = $client->CreateAssessmentSession();
       print "...done. Assessment Session Id: " . $assessment_session->id . "\n";  	                       
       
       // 2nd STEP: Create an Event object of "Generic" Type. 
       //
       print "About to create a Generic Event...\n";
       $event = new \Fraudpointer\API\Models\Event(\Fraudpointer\API\Models\Event::$GENERIC_EVENT);

       // 3rd STEP: Add data to the created Event
       $event->AddData("BookingCode", "B2YB32");
                     
       $start_date_timestamp = strtotime("2011-08-05T10:42:00+03:00");       
       // echo "Start date timestamp: " . $start_date_timestamp . "\n";
       $event->AddDateData("StartDate", $start_date_timestamp);
       
		 $current_time_stamp = time();
		 //echo "Current timestamp: " . $current_time_stamp . "\n";       
       $event->AddDateData("ReservationDate", $current_time_stamp);            
		 print "...done.\n";

       // 4th STEP: Send Event to FraudPointer Server to store it in the database
       //           alongside with the already stored AssessmentSession
       //
       print "About to send Event to Fraudpointer Server...\n";
       $event_created = $client->AppendEventToAssessmentSession($assessment_session, $event);
       print "...done. Event\n"; 
              
       // Repeat step 3 and 4. Note that you can create as many Events as you like for the 
       //                      same Assessment Session
       //
       print "About to create a Failed Payment Event...\n"; 
       $event = new \Fraudpointer\API\Models\Event(\Fraudpointer\API\Models\Event::$FAILED_PAYMENT);

       $event->AddDateData("AttemptDate", time());

       $event->AddData("BILLING_ADDRESS_STREET_NAME", "Othonos");
		 print "...done\n";   
   
       print "About to send Event to Fraudpointer Server...\n";
       $event_created = $client->AppendEventToAssessmentSession($assessment_session, $event);
       print "...done\n";
       
       // 5th STEP: Ask FraudPointer Server to carry out a Fraud Assessment. This is the real value
       //           of the Service because it can tell you real time about the fraud risk of the
       //           transaction that you are trying to handle.
       // 
       print "About to ask for a Final Fraud Assessment...\n";
       $fa = $client->CreateFraudAssessment($assessment_session, false);
       print "...done\n";
 	    PrintFraudAssessmentDetails($fa);
 	          
       // For demonstartion purposes, Ask to get a specific fraud assessment
       print "About to get a specific Fraud Assessment, previously created...\n";
       $fa_retrieved = $client->GetFraudAssessment($assessment_session, $fa->id);
       print "...done\n";
       PrintFraudAssessmentDetails($fa_retrieved);
} // Main ()
//-----------

function PrintFraudAssessmentDetails($fraud_assessment)
{
	print "...Fraud Assessment Id: " . $fraud_assessment->id . "\n";
	print "...Fraud Assessment Score: " . $fraud_assessment->score . "\n";
	print "...Fraud Assessment Result: " . $fraud_assessment->result . "\n";  
	print "...Fraud Assessment Deciding Factor: " . $fraud_assessment->deciding_factor . "\n";
	print "...Fraud Assessment Updated At: " . $fraud_assessment->updated_at . "\n"; 
	print "......Profile Id: " . $fraud_assessment->profile->id . "\n";
	print "......Profile Name: " . $fraud_assessment->profile->name . "\n";
	print "......Profile Updated At: " . $fraud_assessment->profile->updated_at . "\n";
	print "......Case Id: " . $fraud_assessment->case->id . "\n";
	print "......Case Resolution: " . $fraud_assessment->case->resolution . "\n";
	print "......Case Status: " . $fraud_assessment->case->status . "\n";
	print "......Case Updated At: " . $fraud_assessment->case->updated_at . "\n"; 	
} // PrintFraudAssessmentDetails ()
//---------------------------------

$arguments = getopt("u:k:");
if (!(array_key_exists("u", $arguments) && array_key_exists("k", $arguments))) {
	   print "Wrong Syntax! pass two options: -u <fraudpointer base url for api> -k <your api key> \n";
	   print "For <fraudpointer base url for api> try https://production.fraudpointer.com/api \n";
		return;	
}

$base_url = $arguments["u"];
$api_key = $arguments["k"];

Main($base_url, $api_key);

?>