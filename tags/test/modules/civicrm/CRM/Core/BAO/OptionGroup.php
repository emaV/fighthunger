<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 1.5                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2006                                  |
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
 | License along with this program; if not, contact the Social Source |
 | Foundation at info[AT]socialsourcefoundation[DOT]org.  If you have |
 | questions about the Affero General Public License or the licensing |
 | of CiviCRM, see the Social Source Foundation CiviCRM license FAQ   |
 | at http://www.openngo.org/faqs/licensing.html                       |
 +--------------------------------------------------------------------+
*/

/**
 *
 * @package CRM
 * @author Donald A. Lobo <lobo@yahoo.com>
 * @copyright CiviCRM LLC (c) 2004-2006
 * $Id$
 *
 */

require_once 'CRM/Core/DAO/OptionGroup.php';

class CRM_Core_BAO_OptionGroup extends CRM_Core_DAO_OptionGroup 
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
     * @return object CRM_Core_BAO_OptionGroup object
     * @access public
     * @static
     */
    static function retrieve( &$params, &$defaults ) 
    {
        $optionGroup =& new CRM_Core_DAO_OptionGroup( );
        $optionGroup->copyValues( $params );
        if ( $optionGroup->find( true ) ) {
            CRM_Core_DAO::storeValues( $optionGroup, $defaults );
            return $optionGroup;
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
        return CRM_Core_DAO::setFieldValue( 'CRM_Core_DAO_OptionGroup', $id, 'is_active', $is_active );
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
        
        $params['is_active'] =  CRM_Utils_Array::value( 'is_active', $params, false );
        $params['is_default'] =  CRM_Utils_Array::value( 'is_default', $params, false );
        
        // action is taken depending upon the mode
        $optionGroup               =& new CRM_Core_DAO_OptionGroup( );
        $optionGroup->domain_id    = CRM_Core_Config::domainID( );
        
        $optionGroup->copyValues( $params );;
        
        if ($params['is_default']) {
            $query = "UPDATE civicrm_option_group SET is_default = 0 WHERE domain_id = {$optionGroup->domain_id}";
            CRM_Core_DAO::executeQuery( $query, $p );
        }
        
        $optionGroup->id = CRM_Utils_Array::value( 'optionGroup', $ids );
        $optionGroup->save( );
        return $optionGroup;
    }
    
    /**
     * Function to delete Option Group 
     * 
     * @param  int  $optionGroupId     Id of the Option Group to be deleted.
     * 
     * @return void
     * 
     * @access public
     * @static
     */
    static function del($optionGroupId) 
    {
        // need to delete all option value field before deleting group 
        require_once 'CRM/Core/DAO/OptionValue.php';
        $optionValue =& new CRM_Core_DAO_OptionValue( );
        $optionValue->option_group_id = $optionGroupId;
        $optionValue->delete();

        $optionGroup =& new CRM_Core_DAO_OptionGroup( );
        $optionGroup->id = $optionGroupId;
        $optionGroup->delete();
    }

    /**
     * Function to get title of the option group 
     * 
     * @param  int  $optionGroupId     Id of the Option Group.
     * 
     * @return String title
     * 
     * @access public
     * @static
     */

    static function getTitle( $optionGroupId ) {
        $optionGroup               =& new CRM_Core_DAO_OptionGroup( );
        $optionGroup->id = $optionGroupId;
        $optionGroup->find(true);
        return $optionGroup->name;
        
    }
      
}

?>
