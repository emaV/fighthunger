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

require_once 'CRM/Contact/DAO/Contact.php';
require_once 'CRM/Contact/DAO/Individual.php';

/**
 * BAO object for crm_individual table
 */
class CRM_Contact_BAO_Individual extends CRM_Contact_DAO_Individual 
{
    /**
     * This is a contructor of the class.
     */
    function __construct() 
    {
        parent::__construct();
    }
    
    /**
     * takes an associative array and creates a contact object
     *
     * the function extract all the params it needs to initialize the create a
     * contact object. the params array could contain additional unused name/value
     * pairs
     *
     * @param array  $params (reference ) an assoc array of name/value pairs
     * @param array $ids    the array that holds all the db ids
     *
     * @return object CRM_Contact_BAO_Individual object
     * @access public
     * @static
     */
    static function add(&$params, &$ids)
    {
        if ( ! self::dataExists($params, $ids ) ) {
            return;
        }

        $individual =& new CRM_Contact_BAO_Individual();
        if ( $individual->id = CRM_Utils_Array::value( 'individual', $ids ) ) {
            $individual->find(true);
        }
        $individual->copyValues($params);
        
        if ( $date = CRM_Utils_Array::value('birth_date', $params) ) {
            if (is_array($date)) {
                $individual->birth_date = CRM_Utils_Date::format( $date );
            } else {
                $individual->birth_date = preg_replace('/[^0-9]/', '', $date);
            }
        } else if ( $individual->birth_date ) {
            $individual->birth_date = CRM_Utils_Date::isoToMysql( $individual->birth_date );
        }
        if ( $date = CRM_Utils_Array::value('deceased_date', $params) ) {
            if (is_array($date)) {
                $individual->deceased_date = CRM_Utils_Date::format( $date );
            } else {
                $individual->deceased_date = preg_replace('/[^0-9]/', '', $date);
            }
        } else if ( $individual->deceased_date ) {
            $individual->deceased_date = CRM_Utils_Date::isoToMysql( $individual->deceased_date );
        }
        if ( $middle_name = CRM_Utils_Array::value('middle_name', $params)) {
            $individual->middle_name = $middle_name;
        }
        // hack to make db_do save a null value to a field
        if ( ! $individual->birth_date ) {
            $individual->birth_date = 'NULL';
        }

        if (!array_key_exists('is_deceased', $params) && !$individual->is_deceased) {
            $individual->is_deceased = 0;
        }

        $individual->id = CRM_Utils_Array::value( 'individual', $ids );
        $individual->save( );
        return $individual;
    }

    /**
     * Given the list of params in the params array, fetch the object
     * and store the values in the values array
     *
     * @param array $params input parameters to find object
     * @param array $values output values of the object
     * @param array $ids    the array that holds all the db ids
     *
     * @return CRM_Contact_BAO_Individual|null the found object or null
     * @access public
     * @static
     */
    static function getValues( &$params, &$values, &$ids ) {
        $individual =& new CRM_Contact_BAO_Individual( );
        
        $individual->copyValues( $params );
        if ( $individual->find(true) ) {
            $ids['individual'] = $individual->id;
            CRM_Core_DAO::storeValues( $individual, $values );

            if ( isset( $individual->birth_date ) ) {
                $values['birth_date'] = CRM_Utils_Date::unformat( $individual->birth_date );
            }

            CRM_Contact_DAO_Individual::addDisplayEnums($values);
            return $individual;

        }
        return null;
    }

    /**
     * regenerates display_name for contacts with given prefixes/suffixes
     *
     * @param array $ids     the array with the prefix/suffix id governing which contacts to regenerate
     * @param int   $action  the action describing whether prefix/suffix was UPDATED or DELETED
     *
     * @return void
     */
    static function updateDisplayNames(&$ids, $action) {
        
        // get the proper field name (prefix_id or suffix_id) and its value
        $fieldName = '';
        foreach ($ids as $key => $value) {
            switch ($key) {
            case 'individualPrefix':
                $fieldName = 'prefix_id';
                $fieldValue = $value;
                break 2;
            case 'individualSuffix':
                $fieldName = 'suffix_id';
                $fieldValue = $value;
                break 2;
            }
        }
        if ($fieldName == '') return;

        // query for the affected individuals
        $fieldValue = CRM_Utils_Type::escape($fieldValue, 'Integer');
        $individual =& new CRM_Contact_BAO_Individual();
        $individual->$fieldName = $fieldValue;
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
    }

    /**
     * creates display name
     *
     * @return string  the constructed display name
     */
    function displayName()
    {
        $prefix =& CRM_Core_PseudoConstant::individualPrefix();
        $suffix =& CRM_Core_PseudoConstant::individualSuffix();
        return str_replace('  ', ' ', trim($prefix[$this->prefix_id] . ' ' . $this->first_name . ' ' . $this->middle_name . ' ' . $this->last_name . ' ' . $suffix[$this->suffix_id]));
    }

    /** 
     * Check if there is data to create the object 
     * 
     * @param array  $params         (reference ) an assoc array of name/value pairs 
     * @param array  $ids            the array that holds all the db ids 
     * 
     * @return boolean 
     * @access public 
     * @static 
     */ 
    static function dataExists(&$params, &$ids) {
        // if we should not overwrite, then the id is not relevant. 
        if ( is_array( $ids ) && CRM_Utils_Array::value('individual', $ids ) ) {
            return true; 
        } 

        $fields =& CRM_Contact_DAO_Individual::fields( );

        foreach ( $fields as $key => $field ) {
            if ( ! empty($params[$key]) &&  $key != 'contact_id') {
                return true;
            }
        }

        return false;
    }

}

?>
