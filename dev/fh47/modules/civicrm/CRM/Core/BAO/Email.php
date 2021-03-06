<?php

/*
 +--------------------------------------------------------------------+
 | CiviCRM version 1.8                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2007                                |
 +--------------------------------------------------------------------+
 | This file is a part of CiviCRM.                                    |
 |                                                                    |
 | CiviCRM is free software; you can copy, modify, and distribute it  |
 | under the terms of the Affero General Public License Version 1,    |
 | March 2002.                                                        |
 |                                                                    |
 | CiviCRM is distributed in the hope that it will be useful, but     |
 | WITHOUT ANY WARRANTY; without even the implied warranty of         |
 | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
 | See the Affero General Public License for more details.            |
 |                                                                    |
 | You should have received a copy of the Affero General Public       |
 | License along with this program; if not, contact CiviCRM LLC       |
 | at info[AT]civicrm[DOT]org.  If you have questions about the       |
 | Affero General Public License or the licensing  of CiviCRM,        |
 | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2007
 * $Id$
 *
 */

require_once 'CRM/Core/DAO/Email.php';

/**
 * BAO object for crm_email table
 */
class CRM_Core_BAO_Email extends CRM_Core_DAO_Email {
    /**
     * takes an associative array and creates a contact object
     *
     * the function extract all the params it needs to initialize the create a
     * contact object. the params array could contain additional unused name/value
     * pairs
     *
     * @param array  $params         (reference ) an assoc array of name/value pairs
     * @param array  $ids            the array that holds all the db ids
     * @param int    $locationId
     * @param int    $emailId
     * @param bool   $isPrimary      Has any previous entry been marked as isPrimary?
     *
     * @return object    CRM_Core_BAO_Email object if successful 
     *                   else null will be returned
     * @access public
     * @static
     */
    static function add( &$params, &$ids, $locationId, $emailId, &$isPrimary ) {
        // if no data and we are not updating an exisiting record
        if ( ! self::dataExists( $params, $locationId, $emailId, $ids ) ) {
            return null;
        }
        
        $email =& new CRM_Core_DAO_Email();
        $email->id = CRM_Utils_Array::value( $emailId, $ids['location'][$locationId]['email'] );
        $email->email       = $params['location'][$locationId]['email'][$emailId]['email'];
        if ( empty( $email->email ) ) {
            $email->delete( );
            return null;
        }
        
        $email->location_id = $params['location'][$locationId]['id'];
        
        // set this object to be the value of isPrimary and make sure no one else can be isPrimary
        if ( $isPrimary ) {
            $email->is_primary     = $isPrimary;
            $isPrimary             = false;
        } else {
            $email->is_primary     = $params['location'][$locationId]['email'][$emailId]['is_primary'];
        }
        
        if ( array_key_exists( 'on_hold', $params['location'][$locationId]['email'][$emailId]) ) {
            $values = array(
                      'location' => array( $locationId => $params['location'][$locationId]['id'] ),
                      'email'    => $ids['location'][$locationId]['email']
                      );
            
            self::holdEmail( $email, $values, $locationId, $emailId,
                             CRM_Utils_Array::value( 'on_hold', $params['location'][$locationId]['email'][$emailId], false));
            
            return $email;
        }
        
        return $email->save();
        
    }
    
    /**
     * Check if there is data to create the object
     *
     * @param array  $params         (reference ) an assoc array of name/value pairs
     * @param int    $locationId
     * @param int    $emailId
     * @param array  $ids            the array that holds all the db ids
     *
     * @return boolean
     * @access public
     * @static
     */
    static function dataExists( &$params, $locationId, $emailId, &$ids) {
        if ( CRM_Utils_Array::value( $emailId, $ids['location'][$locationId]['email'] )) {
            return true;
        }

        return CRM_Core_BAO_Block::dataExists('email', array( 'email' ), $params, $locationId, $emailId );
    }

    /**
     * Given the list of params in the params array, fetch the object
     * and store the values in the values array
     *
     * @param array $params        input parameters to find object
     * @param array $values        output values of the object
     * @param array $ids           the array that holds all the db ids
     * @param int   $blockCount    number of blocks to fetch
     *
     * @return boolean
     * @access public
     * @static
     */
    static function &getValues( &$params, &$values, &$ids, $blockCount = 0 ) {
        $email =& new CRM_Core_BAO_Email( );
        return CRM_Core_BAO_Block::getValues( $email, 'email', $params, $values, $ids, $blockCount );
    }

    /**
     * Delete email address records from a location
     *
     * @param int $locationId       Location ID to delete for
     * 
     * @return void
     * 
     * @access public
     * @static
     */
    public static function deleteLocation( $locationId ) {
        $dao =& new CRM_Core_DAO_Email();
        $dao->location_id = $locationId;
        $dao->find();

        require_once 'CRM/Mailing/Event/BAO/Queue.php';
        while ($dao->fetch()) {
            CRM_Mailing_Event_BAO_Queue::deleteEventQueue( $dao->id );
        }
        
        $dao->reset();
        $dao->location_id = $locationId;
        $dao->delete();
    }
    
    /**
     * Method to hold or reset email(s)
     * 
     * This method is used to hold and reset the email(s) according to
     * the 'holodStatus' value provided.
     * 'Values' array contains values required to search for required
     * email record in update mode.
     * An example Values array looks like : 
     * 
     * Values
     *
     * Array
     * (
     * [location] => Array
     *      (
     *       [2] => 92
     *      )
     *
     * [email] => Array
     *      (
     *       [1] => 170
     *       [2] => 171
     *       [3] => 172
     *      )
     *
     * )
     * 
     * @param object  $emailDAO          (referance) email dao object
     * @param array   $values
     * @param int     $locationBlockId   Location Block Number
     * @param int     $emailBlockId      Email Block Number
     * @param boolean $holdStatus        flag to indicate whether hold
     *                                   an email or reset
     *
     */
    public static function holdEmail( &$emailDAO, $values, $locationBlockId = 1, $emailBlockId = 1, $holdStatus = false) {
        if ($holdStatus) {
            $emailDAO->on_hold     = 1;
            $emailDAO->hold_date   = date( 'YmdHis' );
            $emailDAO->reset_date  = '';
        } else if (! empty($values['email'][$emailBlockId])) {
            $emailDAO->save();
            $emailDAO->location_id = $values['location'][$locationBlockId];
            
            $emailDAO->whereAdd('id=' . $values['email'][$emailBlockId]);
            $emailDAO->whereAdd('hold_date IS NOT NULL');
            if ( $emailDAO->find(true) ) {
                $emailDAO->on_hold     = 0;
                $emailDAO->hold_date   = '';
                $emailDAO->reset_date  = date( 'YmdHis' );
            }
        }
        
        $emailDAO->save();
        return true;
    }
}
?>
