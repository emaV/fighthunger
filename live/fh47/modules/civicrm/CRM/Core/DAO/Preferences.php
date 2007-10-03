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
require_once 'CRM/Core/DAO.php';
require_once 'CRM/Utils/Type.php';
class CRM_Core_DAO_Preferences extends CRM_Core_DAO
{
    /**
     * static instance to hold the table name
     *
     * @var string
     * @static
     */
    static $_tableName = 'civicrm_preferences';
    /**
     * static instance to hold the field values
     *
     * @var array
     * @static
     */
    static $_fields = null;
    /**
     * static instance to hold the FK relationships
     *
     * @var string
     * @static
     */
    static $_links = null;
    /**
     * static instance to hold the values that can
     * be imported / apu
     *
     * @var array
     * @static
     */
    static $_import = null;
    /**
     * static instance to hold the values that can
     * be exported / apu
     *
     * @var array
     * @static
     */
    static $_export = null;
    /**
     * static value to see if we should log any modifications to
     * this table in the civicrm_log table
     *
     * @var boolean
     * @static
     */
    static $_log = false;
    /**
     *
     * @var int unsigned
     */
    public $id;
    /**
     * Which Domain owns this contact
     *
     * @var int unsigned
     */
    public $domain_id;
    /**
     * FK to Contact ID
     *
     * @var int unsigned
     */
    public $contact_id;
    /**
     * Is this the record for the domain setting?
     *
     * @var boolean
     */
    public $is_domain;
    /**
     * Number of locations to be displayed on edit page?
     *
     * @var int unsigned
     */
    public $location_count;
    /**
     * What tabs are displayed in the contact summary
     *
     * @var string
     */
    public $contact_view_options;
    /**
     * What tabs are displayed in the contact edit
     *
     * @var string
     */
    public $contact_edit_options;
    /**
     * What tabs are displayed in the advanced search screen
     *
     * @var string
     */
    public $advanced_search_options;
    /**
     * What tabs are displayed in the contact edit
     *
     * @var string
     */
    public $user_dashboard_options;
    /**
     * What fields are displayed from the address table
     *
     * @var string
     */
    public $address_options;
    /**
     * Format to display the address
     *
     * @var text
     */
    public $address_format;
    /**
     * Format to display a mailing label
     *
     * @var text
     */
    public $mailing_format;
    /**
     * Format to display a individual name
     *
     * @var text
     */
    public $individual_name_format;
    /**
     * object name of provider for address standarization
     *
     * @var string
     */
    public $address_standardization_provider;
    /**
     * user id for provider login
     *
     * @var string
     */
    public $address_standardization_userid;
    /**
     * url of address standardization service
     *
     * @var string
     */
    public $address_standardization_url;
    /**
     * class constructor
     *
     * @access public
     * @return civicrm_preferences
     */
    function __construct() 
    {
        parent::__construct();
    }
    /**
     * return foreign links
     *
     * @access public
     * @return array
     */
    function &links() 
    {
        if (!(self::$_links)) {
            self::$_links = array(
                'domain_id' => 'civicrm_domain:id',
                'contact_id' => 'civicrm_contact:id',
            );
        }
        return self::$_links;
    }
    /**
     * returns all the column names of this table
     *
     * @access public
     * @return array
     */
    function &fields() 
    {
        if (!(self::$_fields)) {
            self::$_fields = array(
                'id' => array(
                    'name' => 'id',
                    'type' => CRM_Utils_Type::T_INT,
                    'required' => true,
                ) ,
                'domain_id' => array(
                    'name' => 'domain_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'required' => true,
                ) ,
                'contact_id' => array(
                    'name' => 'contact_id',
                    'type' => CRM_Utils_Type::T_INT,
                ) ,
                'is_domain' => array(
                    'name' => 'is_domain',
                    'type' => CRM_Utils_Type::T_BOOLEAN,
                ) ,
                'location_count' => array(
                    'name' => 'location_count',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Location Count') ,
                ) ,
                'contact_view_options' => array(
                    'name' => 'contact_view_options',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Contact View Options') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
                ) ,
                'contact_edit_options' => array(
                    'name' => 'contact_edit_options',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Contact Edit Options') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
                ) ,
                'advanced_search_options' => array(
                    'name' => 'advanced_search_options',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Advanced Search Options') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
                ) ,
                'user_dashboard_options' => array(
                    'name' => 'user_dashboard_options',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('User Dashboard Options') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
                ) ,
                'address_options' => array(
                    'name' => 'address_options',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Address Options') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
                ) ,
                'address_format' => array(
                    'name' => 'address_format',
                    'type' => CRM_Utils_Type::T_TEXT,
                    'title' => ts('Address Format') ,
                ) ,
                'mailing_format' => array(
                    'name' => 'mailing_format',
                    'type' => CRM_Utils_Type::T_TEXT,
                    'title' => ts('Mailing Format') ,
                ) ,
                'individual_name_format' => array(
                    'name' => 'individual_name_format',
                    'type' => CRM_Utils_Type::T_TEXT,
                    'title' => ts('Individual Name Format') ,
                ) ,
                'address_standardization_provider' => array(
                    'name' => 'address_standardization_provider',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Address Standardization Provider') ,
                    'maxlength' => 64,
                    'size' => CRM_Utils_Type::BIG,
                ) ,
                'address_standardization_userid' => array(
                    'name' => 'address_standardization_userid',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Address Standardization Userid') ,
                    'maxlength' => 64,
                    'size' => CRM_Utils_Type::BIG,
                ) ,
                'address_standardization_url' => array(
                    'name' => 'address_standardization_url',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Address Standardization Url') ,
                    'maxlength' => 255,
                    'size' => CRM_Utils_Type::HUGE,
                ) ,
            );
        }
        return self::$_fields;
    }
    /**
     * returns the names of this table
     *
     * @access public
     * @return string
     */
    function getTableName() 
    {
        return self::$_tableName;
    }
    /**
     * returns if this table needs to be logged
     *
     * @access public
     * @return boolean
     */
    function getLog() 
    {
        return self::$_log;
    }
    /**
     * returns the list of fields that can be imported
     *
     * @access public
     * return array
     */
    function &import($prefix = false) 
    {
        if (!(self::$_import)) {
            self::$_import = array();
            $fields = &self::fields();
            foreach($fields as $name => $field) {
                if (CRM_Utils_Array::value('import', $field)) {
                    if ($prefix) {
                        self::$_import['preferences'] = &$fields[$name];
                    } else {
                        self::$_import[$name] = &$fields[$name];
                    }
                }
            }
        }
        return self::$_import;
    }
    /**
     * returns the list of fields that can be exported
     *
     * @access public
     * return array
     */
    function &export($prefix = false) 
    {
        if (!(self::$_export)) {
            self::$_export = array();
            $fields = &self::fields();
            foreach($fields as $name => $field) {
                if (CRM_Utils_Array::value('export', $field)) {
                    if ($prefix) {
                        self::$_export['preferences'] = &$fields[$name];
                    } else {
                        self::$_export[$name] = &$fields[$name];
                    }
                }
            }
        }
        return self::$_export;
    }
}
?>