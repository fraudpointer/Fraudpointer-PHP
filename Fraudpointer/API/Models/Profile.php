<?php

namespace Fraudpointer\API\Models;

/**
* The Profile used to carry out the Fraud Assessment.
*
* A Models.FraudAssessment carries out Assessment using the rules that belong to a specific Profile. 
* There are might be many Profile configured in your Account, but only one is selected for the 
* Models.FraudAssessment at hand. This is done at run-time using the Profile Selection Rules.
*
* @package Fraudpointer\API\Models
*/
class Profile {
	/**
        * The unique identifier of the Profile used for Models.FraudAssessment.
        *
        * @var string $id	
        */
	public $id;

	/**
        * The name of the Profile used for FraudAssessment
	*
        * @var string $name
 	*/
	public $name;	
} // class Profile ()
//---------------------

?>
