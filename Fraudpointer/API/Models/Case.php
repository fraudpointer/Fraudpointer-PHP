<?php

namespace Fraudpointer\API\Models;

/**
 * A Case is created for every <b>final</b> {@link FraudAssessment FraudAssessment}. <br/>
 * <br/>
 * The Case is meant to be handled by a human interacting with the Fraudpointer Application. When a Case is created, it has
 * an "Open" status (unless you have rules in your Fraudpointer application configuration that automatically put them in other statuses).
 * When a Case is inspected by a human, human might decide to set a {@link Kase::resolution resolution} to it.
 * 
 * Note: We use the class identifier "Kase" instead of "Case" because php compiler had problem with this.
 * 
 * @package Fraudpointer\API\Models
 */
class Kase {
	/**
    * The unique identifier of the Case related to a final FraudAssessment.
    *
    * @var string $id	
    */
	public $id;

	/**
     * The Resolution of a Case as set by human (or automatically according to Fraudpointer application configuration). <br/>
     * <br/>
     * Can take the following values:<br/>
     * <br/>
     * <ul>
     *     <li>"Accept"</li>
     *     <li>"Review"</li>
     *     <li>"Reject"</li>
     * </ul>
     * @var string $resolution
 	  */
	public $resolution;	

    /**
     * Case status. <br/>
     * <br/>
     * Can take the following values:<br/>
     * <br/>
     * <ul>
     *     <li>"Open"</li>
     *     <li>"Closed"</li>
     * </ul>
     * @var string $status
     */	
	public $status;
	
   /**
     * Last time the Case was updated
     * @var timestamp $updated_at
     */
	public $updated_at;
	
} // class Profile ()
//---------------------

?>