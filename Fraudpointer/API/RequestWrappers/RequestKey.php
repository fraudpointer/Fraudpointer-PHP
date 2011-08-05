<?php

namespace Fraudpointer\API\RequestWrappers;

/**
 * Whatever we send over to Fraudpointer Server needs to have the api key. So {@link Event Event} and {@link FraudAssessment FraudAssessment} need to be wrapped before serialization to <kbd>json</kbd> format and sent over with the api key. And this class is the parent class that will hold the api key part. <br/>
 * <br/>
 * <b>Note: </b>{@link ClientFactory::Construct() ClientFactory::Construct()} method returns an object that implements {@link IClient IClient} interface
 * and hides all the complexity of sending to and receiving data from Fraudpointer Server. If you use this object, you will not be worry about internals of communications.
 *
 * @package Fraudpointer.API.RequestWrappers
 */
class RequestKey {
	public $key;	
} // class RequestKey
//--------------------

?>
