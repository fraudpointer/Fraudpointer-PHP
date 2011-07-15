<?php

namespace Fraudpointer\API\Models;

/**
* This is an object that is returned by FraudPointer Server when requesting a Fraud Assessment.
*
* Models.FraudAssessment is returned back to you when you ask for a Fraud Assessment. The API to ask for a
* Fraud Assessment is 
* <code>
* $client->CreateFraudAssessment($assessment_session, true/false);
* </code>
*
* The most important properties of the object that you need to take into account are:
* - FraudAssessment.Result: Gives the Assessment Result. Can take one of the values:
* -- "Accept"
* -- "Review"
* -- "Reject"
* - FraudAssessment.Score: An integer value that gives the Assessment Score
* - DecidingFactor: Gives a hint about why the Assessment gave such a FraudAssessment.Result. Takes the values:
* - "Profile thresholds": This means that the FraudAssessment.Score fell into a range that, according to Profile thresholds, returned a result equal to the value that exists in the FraudAssessment.Result property.
* - or the name of the Deciding Rule.
* - Profile: The name of the Profile that has been used to carry out the assessment.
*
* It is up to your business to decide what to do with this FraudAssessment.Result. For example, if the FraudAssessment.Result is "Reject", 
* you can stop from carrying out the actual transaction with your bank.
*
* @package Fraudpointer.API.Models
*/
class FraudAssessment {

	/**
	* @var string $id Unique <kbd>Id</kbd> as returned by FraudPointer Server.
	*/
	public $id;

	/**
        * The Score of the Fraud Assessment. 
        * 
        * The Score of the Fraud Assessment has significance only if the Deciding Factor is "Profile thresholds".
        * If the Deciding Factor has the name of a Rule, then do not take into account Score.
	*
 	* @var string $score
	*/
	public $score;	

	/**
        * Boolean to indicate interim or final (non-interim) Fraud Assessment.
        *
        * Indicates whether the Fraud Assessment is final (non-interim) or not (interim). <kbd>true</kbd> means that
        * the Fraud Assessment is interim (non-final) whereas <kbd>false</kbd> means that Fraud Assessment is 
        * final (non-interim). Note that final Fraud Assessments create a Case in Fraud Pointer Application.
        *
        * @var boolean $interim
        */
	public $interim;

	/**
        * An indicator about how the FraudAssessment.Result has been calculated.
	*        
        * The Deciding Factor takes two values:
        *  - "Profile thresholds"
        *  - Or the name of the Deciding Rule that matched.
        * 
        * If the Deciding Factor has the value "Profile thresholds", then you can see the FraudAssessment.Score that the 
        * Fraud Assessment calculated. This FraudAssessment.Score normally falls into one of the ranges that are defined in the
        * Profile level that has been used to carry out the Fraud Assessment. According to this range, the
        * Result is set. 
        * 
        * If the Deciding Factor has a value different from "Profile thresholds", then it has the value of
        * the Rule that matched. That Rule is a Deciding Rule and its FraudAssessment.Result was set as Result of the Fraud Assessment
        * at hand.
        *
        * @var string $deciding_factor
        */
	public $deciding_factor;


        /**
        * The Result of the Fraud Assessment.
        * 
        * Takes the values:
        * - "Accept"
        * - "Reject"
        * - "Review"
        *
        * You need to evaluate the Result of the Fraud Assessment and take necessary actions.        
        *
        * @var string $result
        */
	public $result;


	/**
        * The Profile used to carry out the Fraud Assessment.
        *
        * The Fraud Assessment uses a set of Fraud Assessment Rules that belong to one specific Profile. The Profile is
        * selected at run-time using the Profile Selection Rules. This property returns an instance of the object that
        * represents the Profile that has been selected in order to carry out the Fraud Assessment at hand.
        * 
        * @var Profile $profile
        */
	public $profile;	
} // FraudAssessment ()
//------------------------------------------------------------------------------------------------------------//


?>
