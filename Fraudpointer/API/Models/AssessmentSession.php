<?php

namespace Fraudpointer.API.Models;


/** 
* An Assessment Session is the object returned when you call API.IClient.CreateAssessmentSession(), which is the
* first method that you need to call to start interacting with the FraudPointer Server.
* 
* When you want to start interaction with FraudPointer Server you need to call API.IClient.CreateAssessmentSession(). This
* method will return to you an instance of Models.AssessmentSession. After its creation, you use this object in the
* following cases:
*
* - When you want to add a Models.Event object to this Assessment Session.
* - When you want to request a Fraud Assessment
* - When you want to embed the session id in your html content, in the hidden field value that is used by
*   FraudPointer javascript <kbd>fp.js</kbd>.
*
* @package Fraudpointer.API.Models
*/
class AssessmentSession {

	/**
        * Unique id for the created session returned by the server. 
	*
        * When you call API.IClient.CreateAssessmentSession() you get an instantiated object whose only property is
        * AssessmentSession.Id. This <kbd>Id</kbd> itself, is useful when you want to embed it in your html content, in 
        * the hidden field value that is used by FraudPointer javascript <kbd>fp.js</kbd>
	*
        * @var integer
	*/
	public $id;
	
} // AssessmentSession ()
//------------------------

?>

