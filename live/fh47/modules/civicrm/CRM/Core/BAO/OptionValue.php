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

require_once 'CRM/Core/DAO/OptionValue.php';

class CRM_Core_BAO_OptionValue extends CRM_Core_DAO_OptionValue 
{

    

    /**
     * class constructor
     */
    function __construct( ) 
    {
        parent::__construct( );
    }
    
    /**
     * Takes a bunch of params that are needed to match certain criteria and
     * retrieves the relevant objects. Typically the valid params are only
     * contact_id. We'll tweak this function to be more full featured over a period
     * of time. This is the inverse function of create. It also stores all the retrieved
     * values in the default array
     *
     * @param array $params   (reference ) an assoc array of name/value pairs
     * @param array $defaults (reference ) an assoc array to hold the flattened values
     *
     * @return object CRM_Core_BAO_OptionValue object
     * @access public
     * @static
     */
    static function retrieve( &$params, &$defaults ) 
    {
        $optionValue =& new CRM_Core_DAO_OptionValue( );
        $optionValue->copyValues( $params );
        if ( $optionValue->find( true ) ) {
            CRM_Core_DAO::storeValues( $optionValue, $defaults );
            return $optionValue;
        }
        return null;
    }

    /**
     * update the is_active flag in the db
     *
     * @param int      $id        id of the database record
     * @param boolean  $is_active value we want to set the is_active field
     *
     * @return Object             DAO object on sucess, null otherwise
     * @static
     */
    static function setIsActive( $id, $is_active ) 
    {
        return CRM_Core_DAO::setFieldValue( 'CRM_Core_DAO_OptionValue', $id, 'is_active', $is_active );
    }

    /**
     * function to add the Option Group
     *
     * @param array $params reference array contains the values submitted by the form
     * @param array $ids    reference array contains the id
     * 
     * @access public
     * @static 
     * @return object
     */
    static function add(&$params, &$ids) 
    {
        
        $params['is_active']  =  CRM_Utils_Array::value( 'is_active', $params, false );
        $params['is_default'] =  CRM_Utils_Array::value( 'is_default', $params, false );
        $params['is_optgroup'] =  CRM_Utils_Array::value( 'is_optgroup', $params, false );
        
        // action is taken depending upon the mode
        $optionValue               =& new CRM_Core_DAO_OptionValue( );
        $optionValue->domain_id    = CRM_Core_Config::domainID( );
        
        $optionValue->copyValues( $params );;
        
        if ($params['is_default']) {
            $query = 'UPDATE civicrm_option_value SET is_default = 0 WHERE  option_group_id = %1';
            $p = array( 1 => array( $params['option_group_id'], 'Integer' ) );
            CRM_Core_DAO::executeQuery( $query, $p );
        }
        
        $optionValue->id = CRM_Utils_Array::value( 'optionValue', $ids );
        $optionValue->save( );
        return $optionValue;
    }
    
    /**
     * Function to delete Option Value 
     * 
     * @param  int  $optionGroupId     Id of the Option Group to be deleted.
     * 
     * @return void
     * 
     * @access public
     * @static
     */
    static function del($optionValueId) 
    {
        $optionValue =& new CRM_Core_DAO_OptionValue( );
        $optionValue->id = $optionValueId;

        if ( self::updateRecords($optionValueId, CRM_Core_Action::DELETE) ){
            return $optionValue->delete();        
        }
        return false;
    }



    /**
     * retrieve the id and decription
     *
     * @param NULL
     * 
     * @return Array            id and decription
     * @static
     * @access public
     */
    static function &getActivityDescription() 
    {
        
        $query =
            "SELECT civicrm_option_value.value, civicrm_option_value.description
FROM civicrm_option_value
LEFT JOIN civicrm_option_group ON ( civicrm_option_value.option_group_id = civicrm_option_group.id )
WHERE civicrm_option_value.is_active =1
AND civicrm_option_value.value >4
AND civicrm_option_group.name = 'activity_type'  ORDER BY civicrm_option_value.name";
        $dao   =& CRM_Core_DAO::executeQuery( $query, CRM_Core_DAO::$_nullArray );
        $description =array();
        while($dao->fetch()) {
            $description[ $dao->value] = $dao->description;
            
        }
        return $description;
    }
    
    /**
     * updates contacts affected by the option value passed.
     *
     * @param Integer $optionValueId     the option value id.
     * @param int     $action            the action describing whether prefix/suffix was UPDATED or DELETED
     *
     * @return void
     */
    static function updateRecords(&$optionValueId, $action) {
        //finding group name
        $optionValue =& new CRM_Core_DAO_OptionValue( );
        $optionValue->id = $optionValueId;
        $optionValue->find(true);
        
        $optionGroup =& new CRM_Core_DAO_OptionGroup( );
        $optionGroup->id = $optionValue->option_group_id;
        $optionGroup->find(true);
        
        $gName = $optionGroup->name; //group name
        $value = $optionValue->value; //value
        
        // get the proper group name & affected field name
        $individuals      = array('gender'              => 'gender_id', 
                                  'individual_prefix'   => 'prefix_id', 
                                  'individual_suffix'   => 'suffix_id');
        $contributions    = array('payment_instrument'  => 'payment_instrument_id');
        $activities       = array('activity_type'       => 'activity_type_id');
        $participant      = array('participant_role'    => 'role_id',
                                  'participant_status'  => 'status_id'
                                  );
        $eventType        = array('event_type'          => 'event_type_id');
        $aclRole          = array('acl_role'            => 'acl_role_id');

        $all = array_merge($individuals, $contributions, $activities, $participant, $eventType, $aclRole);
        $fieldName = '';
        
        foreach($all as $name => $id) {
            if ($gName == $name) {
                $fieldName = $id;
            }
        }
        if ($fieldName == '') return true;
        
        if (array_key_exists($gName, $individuals)) {
            // query for the affected individuals
            require_once 'CRM/Contact/BAO/Individual.php';
            $individual =& new CRM_Contact_BAO_Individual();
            $individual->$fieldName = $value;
            $individual->find();
            
            // iterate through the affected individuals and rebuild their display_names
            require_once 'CRM/Contact/BAO/Contact.php';
            while ($individual->fetch()) {
                $contact =& new CRM_Contact_BAO_Contact();
                $contact->id = $individual->contact_id;
                if ($action == CRM_Core_Action::DELETE) {
                    $individual->$fieldName = 'NULL';
                    $individual->save();
                }
                $contact->display_name = $individual->displayName();
                $contact->save();
            }
            return true;
        }
        
        if (array_key_exists($gName, $contributions)) {
            require_once 'CRM/Contribute/DAO/Contribution.php';
            $contribution =& new CRM_Contribute_DAO_Contribution();
            $contribution->$fieldName = $value;
            $contribution->find();
            while ($contribution->fetch()) {
                if ($action == CRM_Core_Action::DELETE) {
                    $contribution->$fieldName = 'NULL';
                    $contribution->save();
                }
            }
            return true;
        }
        
        if (array_key_exists($gName, $activities)) {
            require_once 'CRM/Activity/DAO/Activity.php';
            $activity =& new CRM_Activity_DAO_Activity( );
            $activity->$fieldName = $value;
            $activity->find();
            while ($activity->fetch()) {
                $activity->delete();
            }
            return true;
        }
        
        //delete participant role, type and event type option value
        if (array_key_exists($gName, $participant)) {
            require_once 'CRM/Event/DAO/Participant.php';
            $participantValue =& new CRM_Event_DAO_Participant( );
            $participantValue->$fieldName = $value;
            if ( $participantValue->find(true)) {
                return false;
            }
            return true;
        }

        //delete event type option value
        if (array_key_exists($gName, $eventType)) {
            require_once 'CRM/Event/DAO/Event.php';
            $event =& new CRM_Event_DAO_Event( );
            $event->$fieldName = $value;
            if ( $event->find(true) ) {
                return false;
            }
            return true;
        }

        //delete event type option value
        if (array_key_exists( $gName, $aclRole )) {
            require_once 'CRM/ACL/DAO/EntityRole.php';
            require_once 'CRM/ACL/DAO/ACL.php';
            $entityRole =& new CRM_ACL_DAO_EntityRole( );
            $entityRole->$fieldName = $value;

            $aclDAO =& new CRM_ACL_DAO_ACL( );
            $aclDAO->entity_id = $value;
            if ( $entityRole->find(true) || $aclDAO->find(true)) {
                return false;
            }
            return true;
        }
    }
}
?>
