<?php

namespace FraudPointer\API;

require_once dirname(__FILE__) . "/Fraudpointer/API/IClient.php";
require_once dirname(__FILE__) . "/helpers/Application.php"; 

session_start();

// check whether we have to start over
CheckForStartOver();
//-------------------------------------

// access necessary SESSION variables
$client = $_SESSION["fraudpointer_client"];
$assessment_session = GetOrCreateAssessmentSession($client);

$number_of_failed_payment_attempts = $_SESSION["number_of_failed_payment_attempts"];
$acme_order_number = $_SESSION["acme_order_number"];

// create and send checkout event
CreateAndSendCheckoutEvent($client, $assessment_session);

// I do not have any more data to send to server, I will ask for Fraud Assessment, an "interim" one not final		
$fraud_assessment = $client->CreateFraudAssessment($assessment_session, true);

?>

<html>
	<head>
		<title>Result of Payment</title>
	</head>

	<body>

		<?php
		
		if ( $fraud_assessment->result == "Accept" ) { ?>
				
				<?php
				
					$charge_result = SendDataToBankForCharging();
					if ( $charge_result ) { 
					
 						 // let us create a successful payment event
 						 CreateAndSendSuccessfulPaymentEvent($client, $assessment_session); 						  						  						 		
			
						 $fraud_assessment = $client->CreateFraudAssessment($assessment_session, false);               

 						 ResetSessionVars();
 						 												
			   ?>
						
						<h2>You have been successfully charged. Go <a href="index.php">BACK to purchase again!</a></h2>						
						
				<?php	}
				
					else {
						
						CreateAndSendFailedPaymentEvent($client, $assessment_session);
														
						$number_of_failed_payment_attempts = $number_of_failed_payment_attempts + 1;
						$_SESSION["number_of_failed_payment_attempts"] = $number_of_failed_payment_attempts;
						
						if ( $number_of_failed_payment_attempts > 3 ) {
								// too many failed payment attempts
								ResetSessionVars();
								header("Location: index.php");								
								exit();
						} // when number of failed payment attempts > 3
						else {
							header("Location: checkout.php");
							exit();																	
						} // when number of failed payment attempts <= 3
					
					} // when payment to bank failed
				?>      
				
		<?php 
		} 
      elseif ( $fraud_assessment->result == "Review" ) {      	 

				ResetSessionVars();      
												
      ?>     
            <h1>We will hold your purchase request data and come back to you soon. Sorry for the delay. (<a href="index.php">[ACME Home Page]</a>)</h1>                         				
				            
      <?php
      }
      else { // this is a Reject case       					  						  						 		
			
			  $fraud_assessment = $client->CreateFraudAssessment($assessment_session, false);               
       
           ResetSessionVars();
                             
      ?>
      		<h1>Sorry, but we cannot process your request. Your data has been declined.</h1>      	
      	
      <?php	
      }
                 		
		?>
					
	
	</body>
</html>