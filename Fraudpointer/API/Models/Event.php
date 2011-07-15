<?php

namespace Fraudpointer\API\Models;

/**
* Event object groups a set of Data under an Event Type. This whole set, is then associated to a
* Models.AssessmentSession by calling API.IClient.AppendEventToAssessmentSession().
*
* When you are using the FraudPointer Service, you need to collect data and sent it over to 
* FraudPointer Server. In order to do that you instantiate a Models.Event object and then you
* call Event.AddData() method. Then you call API.IClient.AppendEventToAssessmentSession() and
* Event packaged data are sent over.
* 
* It is important, though, to instantiate the appropriate Event Type. The following event types are 
* supported:
*
* - Event.GenericEvent
* - Event.CheckoutEvent
* - Event.FailedPaymentEvent
* - Event.PurchaseEvent
*
* @package Fraudpointer.API.Models
*/
class Event {
	
	/**
	* Use this constant to instantiate an Event that is not one of the other types. 
	* 
	* Usually, you group your data into a more specific Type. However, if the data that you want
	* to group cannot be categorized below one of the specific Types, you can use the
	* GenericEvent Type.
	*
	* @var string
	*/
	public static $GENERIC_EVENT = "GenericEvent";

	/**
	* Use this constant to instantiate an Event that is related to Checkout data.
	*
	* You can create a CheckoutEvent when you get data from a check out form. You essentially want
	* to store in FraudPointer server the fact that your customer is trying to check out together with
	* relevant check out data. Example of Data that you might want to store below the CheckoutEvent Type might be:
	*
	* - Firstname
	* - Lastname        
	* - Customer e-mail
	* - Customer address
	* - Product to Purchase
	* - Price
	* - e.t.c.
	* 
	* @var string
	*/
	public static $CHECKOUT_EVENT = "CheckoutEvent";

	/**
	* Use this constant to instantiate an Event to mark a Failed Payment.
	*
	* When you decide to proceed with charging your customer, with whatever data you have at your hand,
	* your attempt to charge its credit card might fail. In that case, record this failed payment by 
	* instantiating a FailedPayment Event Type and sending it over to FraudPointer, without necessarily
	* attaching any data to it. Only the FailedPayment Event is enough to mark the fact that the payment
	* carried out during this session failed.
	*
	* You can, and you probably should, instantiate this event many times. One for each failed payment attempt
	* during the same assessment session.
	*
	* @var string 
	*/
	public static $FAILED_PAYMENT = "FailedPaymentEvent";

	/**
	* Use this constant to instantiate an Event to mark a Successful Payment.
	*
	* When you decide to proceed with charging your customer, with whatever data you have at your hand,
	* your attempt to charge its credit card might succeed. In that case, record this successful payment by
	* instantiating a Purchase Event Type and sending it over to FraudPointer, without necessarily
	* attaching any data to it. Only the PurchaseEvent Event is enough to mark the fact that the payment
	* carried out during this session has succeeded.
	*
	* @var string
	*/
	public static $PURCHASE_EVENT = "PurchaseEvent";

	/**
        * Event Type to send to the server for event creation
        *
	* @var string
	*/
	public $type;

	/**
        * Returns the Data appended to the particular Models.Event
        *
        * Note that if you want to append data to the Models.Event use one of the methods Models.Event.AddData
        *
	* @var array
	*/
	public $data = array();

	/**
	* Instantiate a Models.Event by calling <kbd>new</kbd> on this Constructor
	*
	* Use
	* <code>
	* $event = new \Fraudpointer\API\Models\Event(\Fraudpointer\API\Models\Event::$CHECKOUT_EVENT);
	* </code>
	* to instantiate a new Event before start filling it with data. The argument should be one of the 
	* valid event types.
	* 
	* @param string $type One of:
	* - Event.CheckoutEvent
	* - Event.FailedPayment
	* - Event.GenericEvent
	* - Event.PurchaseEvent
	*/ 
	function __construct($type) {
	$this->type = $type;
	} // __construct()
	//----------------
   
	/**
	* Adds one datum to the particular Models.Event. The datum is actually a value of an Attribute and 
        * value given is an <kbd>int</kbd> instance or literal.
        *
        * Use this method if you want to add a datum of type <kbd>int</kbd> in your Event at hand.
        *
        * Here is an example:
        * <code>
        * $event = new \Fraudpointer\API\Models\Event(\Fraudpointer\API\Models\Event::$CHECKOUT_EVENT);
        * $event->AddData("CC_CARD_HOLDER_NAME", "John Papas");
        * </code>
        *
        * @param string $key KEY value of the Attribute that you want to use. The Attributes are either System
        * or Custom Account Attributes. The Custom Account Attributes
        * should be given to you by the person who has created them in the FraudPointer Application.
        * 
        * @param string $value Value of the Attribute. A <kbd>string</kbd> instance or literal should be given.
        * @return Event The current Event instance, in order to continue adding data.  
	*/
	public function AddData ($key, $value) {
		$this->data[$key] = $value;				
	} // AddData
	//----------

	/**
        * Adds one datum to the particular Models.Event. The datum is actually a value of an Attribute and 
        * value given is an <kbd>int</kbd> instance or literal.
        *
        * Use this method if you want to add a datum of type <kbd>int</kbd> in your Event at hand.
        * 
        * Here is an example:
        * <code>
        * $event = new \Fraudpointer\API\Models\Event(\Fraudpointer\API\Models\Event::$CHECKOUT_EVENT);
        * $event.AddIntData("NUMBER_OF_PURCHASED_GOODS", 4);
        * </code>
	*
        * @param string $key KEY value of the Attribute that you want to use. The Attributes are either System
        * or Custom Account Attributes. The Custom Account Attributes
        * should be given to you by the person who has created them in the FraudPointer Application.
	*        
        * @param integer $int_value The value of the Attribute. An <kbd>integewr</kbd> instance or literal should be given.
        * @return Event The current Event instance, in order to continue adding data.
        */
	public function AddIntData ($key, $int_value) {
		$this->data[$key] = strval($int_value);
	} // AddIntData for int value
	//-------------------------------

	/**
        * Adds one datum to the particular Models.Event. The datum is actually a value of an Attribute and 
        * value given is a <kbd>boolean</kbd> instance or literal.
        *
        * Use this method if you want to add a datum of type <kbd>boolean</kbd> in your Event at hand.
        * 
        * Here is an example:
        * <code>
        * $event = new \Fraudpointer\API\Models\Event(\Fraudpointer\API\Models\Event::$CHECKOUT_EVENT);
        * $event.AddBoolData("TWO_WAY_TRIP", false);
        * </code>
	*
        * @param string $key KEY value of the Attribute that you want to use. The Attributes are either System
        * or Custom Account Attributes. The Custom Account Attributes should be given to you by the person who 
        * has created them in the FraudPointer Application.
        *
        * @param boolean $bool_value Value of the Attribute. A <kbd>bool</kbd> instance or literal should be given.
        * @return Event The current Event instance, in order to continue adding data.
        */
	public function AddBoolData ($key, $bool_value) {
		$this->data[$key] = $bool_value ? "true" : "false";
	} // AddBoolData
	//-----------------

	/**
        * Adds one datum to the particular Models.Event. The datum is actually a value of an Attribute and 
        * value given is a <kbd>float</kbd> instance or literal.
        * 
        * Use this method if you want to add a datum of type <kbd>decimal</kbd> in your Event at hand.
        *  
        * Here is an example:
        * <code>
        * $event = new \Fraudpointer\API\Models\Event(\Fraudpointer\API\Models\Event::$CHECKOUT_EVENT);
        * $event.AddDecimalData("PURCHASE_AMOUNT", 32.56);
        * </code>
	*
        * @param string $key KEY value of the Attribute that you want to use. The Attributes are either System
        * or Custom Account Attributes. The Custom Account Attributes
        * should be given to you by the person who has created them in the FraudPointer Application.
        * 
        * @param float $decimal_value Value of the Attribute. A <kbd>float</kbd> instance or literal should be given.
        * @return Event instance, in order to continue adding items.
        */
	public function AddDecimalData ($key, $decimal_value) {
		$this->data[$key] = strval($decimal_value);
	} // AddDecimalData ()
	//-----------------------	

        /**
        * Adds one datum to the particular Models.Event. The datum is actually a value of an Attribute and 
        * value given is a <kbd>timestamp</kbd> instance.
        *
        * Use this method if you want to add a datum of type <kbd>DateTime</kbd> in your Event at hand.
        * 
        * Here is an example:
        * <code>
        * $event = new \Fraudpointer\API\Models\Event(\Fraudpointer\API\Models\Event::$CHECKOUT_EVENT);
        * $event.AddDateData("PURCHASE_DATE", time());
        * </code>
        * Internally, the <kbd>timestmp</kbd> is converted to a string representation that follows the 
        * expanded version of the standard ISO 8601. Hence, the example given above will be converted to something like
        * <kbd>"2011-05-27T23:15:30+03:00"</kbd> if the local time zone is 3 hours ahead of UTC.
        * 
        * @param string $key KEY value of the Attribute that you want to use. The Attributes are either System
        * or Custom Account Attributes. The Custom Account Attributes
        * should be given to you by the person who has created them in the FraudPointer Application.
        *
        * @param timestamp $timestamp_value Value of the Attribute. A <kbd>timestamp</kbd> instance should be given.
        * @return Event The current Event instance, in order to continue adding items.
        */
	public function AddDateData ($key, $timestamp_value) {		
		// It should be stored in ISO 8601/RFC3339 format "YYYY-MM-DDTHH:MM:SS(+/-)HH:MM		
		$this->data[$key] = date("c", $timestamp_value);						
	} // AddDateData ()
	//--------------------
	
} // class Event
//---------------


?>
