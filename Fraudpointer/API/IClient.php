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
* @example main_example.php A Console application that can be used to play with API
*
* @package Fraudpointer.API
*/
interface IClient
{
   /**
     * Communicates with FraudPointer Server and gets back a ready to use AssessmentSession object.This is the first method that you should call. <br/>
     * <br/>
     * It should be used as soon as you want to start using the FraudPointer service. Without an AssessmentSession object
     * you cannot do anything.<br/>
     * <br/>
     * @return AssessmentSession An object of type AssessmentSession as returned by FraudPointer Server. Object should contain the AssessmentSession.id
     * that uniquely identifies this AssessmentSession. You should use this AssessmentSession.id in whichever place the
     * AssessmentSession id is required.<br/>
     * <br/>
     * Method always returns a valid object, unless an exception is thrown.
     * It may throw a ClientException if an error occurs
     */
	public function CreateAssessmentSession();
	
	/**
     * Appends an Event to an AssessmentSession object. <br/>
     * <br/>
     * This method also communicates with the FraudPointer Server, which will store all Event data alongside with the AssessmentSession
     * given.<br/>
     * <br/>
     * You can call this method as many times as you want (as long as you pass a new Event on each call).
     * It is actually recommended to do so. In other words, it is recommended to create various Events, of
     * various types and send them to FraudPointer to be stored against an AssessmentSession.
     * <br/>
     *
     * @param AssessmentSession $assessment_session Should be constructed with a call to createAssessmentSession()
     * @param Event $param_event Should be constructed with a call to new Event(event_type)
     * @return Event An object of type Event as returned by the FraudPointer Server.<br/>
     * <br/>
     * Method always returns a valid object, unless an exception is thrown.
     * It may throw a ClientException if an error occurs
     */	    
	public function AppendEventToAssessmentSession($assessment_session, $param_event);

   /**
     * This method should be called in order to evaluate the fraud level of an AssessmentSession. <br/>
     * <br/>
     * This method should be called after having sent to FraudPointer Server one or more Events. It is used
     * to evaluate the fraud risk of the AssessmentSession. Note that you can ask multiple interim 
     * Fraud Assessments, but you can only ask for one final (non-interim) Fraud Assessment. Interim Fraud Assessments
     * can be requested in a cycle of interactions with the FraudPointer Server such as the following:<br/>
     * <br/>
     * <ul>
     * <li> create assessment session </li>
     * <li> create event  </li>
     * <li> add data to event  </li>
     * <li> add data to event  </li>
     * <li> ...  </li>
     * <li> add data to event  </li>
     * <li> append event to assessment session  </li>
     * <li> create another event </li>
     * <li> add data to new event </li>
     * <li> add data to new event </li>
     * <li> ...  </li>
     * <li> add data to new event  </li>
     * <li> append new event to assessment session </li>
     * <li> create interim fraud assessment </li>
     * <li> create one more event </li>
     * <li> add data to last created event </li>
     * <li> add data to last created event </li>
     * <li> ... </li>
     * <li> add data to last created event </li>
     * <li> append last created event to assessment session </li>
     * <li> create interim fraud assessment </li>
     * <li> .... </li>
     * <li> create final (non<li>interim) fraud assessment </li>
     * </ul>
     * <br/>
     * The last and only final (non-interim) fraud assessment will also create a CASE in FraudPointer Application.
     * <br/>
     * @param AssessmentSession $assessment_session  A valid AssessmentSession, previously created with createAssessmentSession()
     * @param boolean $interim A boolean value true or false. If true, then an interim FraudAssessment will be created.
     * If false, a final (non-interim) FraudAssessment will be created
     * @return FraudAssessment A FraudAssessment valid object filled in with information returned by FraudPointer Server. It contains the Fraud
     * Assessment Result. It may throw a ClientException if an error occurs
     */
	public function CreateFraudAssessment($assessment_session, $interim);	

   /**
     * Use this method to generate a hash of a credit card number. Use the generated hash to send the encrypted credit card number to FraudPointer Server instead of the credit card number itself. <br/>
     * <br/>
     * FraudPointer Server tries to identify the existence of the same credit card number in various transactions. These
     * transactions either take place during the same session or take place in different sessions, but at the same time, or
     * took place in a session in the past. However, FraudPointer Server does not want to store the credit card numbers in clear format
     * and it does not need to do that in order to accomplish its goal. Hence, you need to encrypt them using the method provided here.
     * Note that hash is one-way encryption method and FraudPointer Server cannot derive the credit card number from the hash.
     * <br/>
     * This method does not communicate with the FraudPointer Server to generate the hash. Works locally.<br/>
     * <br/>
     * @param string $credit_card_number The credit card number that you want to get its hash value.
     * @return string The hash of the credit card number. Note that if an empty string is returned, then something has gone wrong during the encryption.
     * in that case, method throws a ClientException.
     */
	public function CreditCardHash($credit_card_number);
	
	/**
	* Communicates with server and gets back to you a full FraudAssessment object that
	* has been previously created with a call to CreateFraudAssessment.
	*
	* You can use this method to request from server a 'reload' of a FraudAssessment object
	* that you already own. This logical reload might bare extra information within it. Especially,
	* information about the Case and its Resolution.
	*  
	* @param AssessmentSession $assessment_session
	* @param string $fraud_assessment_id
	* @return FraudAssessment The FraudAssessment object requested.
	*/
	public function GetFraudAssessment($assessment_session, $fraud_assessment_id);
} // interface IClient
//---------------------


?>
