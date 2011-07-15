<?php

require_once "helpers/Application.php";

// I need the session access
session_start();

//-------------- access to fraud pointer service -------------------------------------------------------------------------------
$client = GetFraudpointerClient();
//------------------------------------------------------------------------------------------------------------------------------

?>

<html>
<head>

   <link href="css/main.css" type="text/css" rel="stylesheet" /></link>

   <!-- ----------------------------------- -->
	<!-- This is necessary for FRAUD POINTER -->
	<!--                                     -->
   <script language="javascript" type="text/javascript" src='<?= FraudPointerScriptSource() ?>'></script>
   <script language="javascript" type="text/javascript">
        window.onload = function() {
            fraudpointer.fp('<?= HiddenFraudPointerSessionIdField(); ?>');
        }
   </script>
   <!-- end of FRAUD POINTER related javascript stuff -->
   <!-- --------------------------------------------- -->
    
   <script language="javascript" type="text/javascript" src="scripts/checkout.js"></script>
   
	<title>ACME e-shop - Checkout</title>
	
</head>
	
<body>

  <a href="/index.php"><img src="images/acme_logo.png" alt="ACME logo"/></img></a>
  <hr/>
  <?= "Order Number: " . GetAcmeOrderNumber(); ?>
  <br/>
  
  <!-- Print the Number of Failed Payment Attempts -->
  <span class='failed_payment_attempts'><?= GetNumberOfFailedPaymentAttempts()>=1 ? "Failed Payment Attempts: " . GetNumberOfFailedPaymentAttempts() : "" ?></span>
  <!-- ------------------------------------------- -->
    
  <form action="do_purchase.php" method="post">
  
  	  <input type="hidden" id="<?= HiddenFraudPointerSessionIdField(); ?>" name="<?= HiddenFraudPointerSessionIdField(); ?>" value='<?= GetOrCreateAssessmentSession($client)->id; ?>' />  	  	  
	  <fieldset>
	        
	        <legend>Tickets from Europe to USA - Pick up your tickets</legend>  
	        
	        <label for="select_city_from" >Travel From:</label>
	        <select id="select_city_from" name="select_city_from">
					<option value='<?= array_search("London", $cities_from); ?>' selected>London</option>
					<option value='<?= array_search("Madrid", $cities_from); ?>'>Madrid</option>
					<option value='<?= array_search("Paris", $cities_from); ?>'>Paris</option>
					<option value='<?= array_search("Rome", $cities_from); ?>'>Rome</option>        
	        </select>
	        
	        <br/>
	        
	        <label for="select_city_to" >Travel To:</label>
	        <select id="select_city_to" name="select_city_to">
					<option value='<?= array_search("Atlanta", $cities_to); ?>' selected>Atlanta</option>
					<option value='<?= array_search("Chicago", $cities_to); ?>'>Chicago</option>
					<option value='<?= array_search("New York", $cities_to); ?>'>New York</option>
					<option value='<?= array_search("Los Angeles", $cities_to); ?>'>Los Angeles</option>        
	        </select>
	        
	        <br/>
	        
	        <input type="button" value="Calculate Price" onclick="javascript: return calculate_price('select_city_from', 'select_city_to', 'price_value');" />     
	        
	        <fieldset>
					<legend>Charging Details:</legend>
					
					<label for="price_value">Price:</label>
					<label id="price_value" name="price_value"></label><br/>       
		        
		         <label for="txtbx_credit_card_number">Credit Card Number:</label>
		         <input type="text" id="txtbx_credit_card_number" name="txtbx_credit_card_number"/><br/>
		         
		         <label for="txtbx_expires">Expires:</label>
					<select id="select_expires_month" name="select_expires_month">
						<?php
							for ($month = 1; $month <= 12; $month++ ) { ?>					
						  <option value='<?= $month ?>'><?= $month ?></option>				 
						<?php		}
						?>					
					</select>
					<select id="select_expires_year" name="select_expires_year">
						<?php
							for ($year = 2011; $year <= 2031; $year++ ) { ?>					
						  <option value='<?= $year ?>'><?= $year ?></option>				 
						<?php		}
						?>					
					</select>
					
					<br/>
					
		         <label for="txtbx_card_holder_name">Card Holder Name:</label>
		         <input type="text" id="txtbx_card_holder_name" name="txtbx_card_holder_name"/><br/>
		         
		         <label for="txtbx_bank_name_of_card">Bank Name of Card:</label>
		         <input type="text" id="txtbx_bank_name_of_card" name="txtbx_bank_name_of_card"/><br/>
		
		         <label for="txtbx_ccv">CCV:</label>
		         <input type="text" id="txtbx_ccv" name="txtbx_ccv"/><br/>
	         
	        </fieldset>   
	  
	  		
	      <input type="submit" value="Buy Tickets" id="sbmt_buy_tickets" name="sbmt_buy_tickets"/><br/>
	      <input type="checkbox" id="chckbx_succeed_with_payment" name="chckbx_succeed_with_payment" checked='checked'/>Succeed with payment
	      <small>[Clear this if you want to check failed payment attempt flow]</small>
	                                           
	  </fieldset>

	  <?= "Assessment Session Id: " . GetOrCreateAssessmentSession($client)->id;  ?>
	  <br/>
	  <input type="submit" value="Clear Assessment Session and Start Over" id="sbmt_clear_as_and_start_over" name="sbmt_clear_as_and_start_over" />
	         
  </form>
  
  
</body>

</html>