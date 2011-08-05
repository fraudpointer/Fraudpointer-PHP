<?php

namespace Fraudpointer\API\RequestWrappers;

/**
 * {@link FraudAssessment FraudAssessment} needs to be wrapped before serialization to <kbd>json</kbd> format.This class helps carry out this wrapping. <br/>
 * <br/>
 * <b>Note: </b>{@link ClientFactory::Construct() ClientFactory::Construct()} method returns an object that implements {@link IClient IClient} interface
 * and hides all the complexity of sending to and receiving data from Fraudpointer Server. If you use this object, you will not be worry about internals of communications.
 *
 * @package Fraudpointer.API.RequestWrappers
 */
class RequestKeyAndFraudAssessment extends RequestKey {
	public $fraud_assessment;
} // class RequestKeyAndFraudAssessment
//---------------------------------------

?>
