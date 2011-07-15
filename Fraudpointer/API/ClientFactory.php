<?php

namespace Fraudpointer\API;

require_once "Clients/Client.php";

/**
* This is a helping class that instantiates an object that implements IClient interface.
*
* Use this method to start with FraudPointer API. It will give you an object that properly implements IClient interface.
*
* @package Fraudpointer.API
*/
class ClientFactory {
	/**
        * Instantiates an object that properly implements IClient interface.
	*        
	* Start your FraudPointer API usage from here. It will give you an instance of an object that properly implements the IClient interface.
	*
        * Here is an example of usage:
        * <code>
        * $client = \FraudPointer\API\ClientFactory::Construct(BaseUrl(), ApiKey()); 
        * </code>
        * where <kbd>BaseUrl()</kbd> might be something like:
        * <code>
	* function BaseUrl () {
	*    return "https://production.fraudpointer.com/api/assessment_sessions";
	* } // BaseUrl ()
	* //--------------
	* </code>
	* and <kbd>ApiKey()</kbd> might be something like:
	* <code>
	* function ApiKey () {
	*  return "45440beccd884ddsa688asdfasda18b09"; 
	* } // ApiKey ()		
	*
	* @param string $base_url This is the URL of the FraudPointer API Service. It has to have the value:
	* <code>https://production.fraudpointer.com/api</code>
	*
        * @param string $api_key This should be the API KEY that corresponds to the domain that you integrate FraudPointer API for.
        * The API KEY is automatically generated when you register an Account in <a href="https://production.fraudpointer.com/a/new" target="_blank">FraudPointer Registration Web Form</a>.
        * Note that your Account in FraudPointer service might have more than one domains registered. You need to provide here the API KEY that
        * corresponds to the domain that you are going to integrate this client with.
        * @return IClient A valid IClient instantiated object. This is a newly created object to work with the FraudPointer Service.
	*/
	public static function Construct ($base_url, $api_key) {
		return new \Fraudpointer\API\Clients\Client($base_url, $api_key);
	} // Construct ()
	//---------------	
	
} // class ClientFactory
//----------------------

?>
