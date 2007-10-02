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
class CRM_Contact_DAO_Organization extends CRM_Core_DAO
{
    /**
     * static instance to hold the table name
     *
     * @var string
     * @static
     */
    static $_tableName = 'civicrm_organization';
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
    static $_log = true;
    /**
     * Unique Organization ID
     *
     * @var int unsigned
     */
    public $id;
    /**
     * FK to Contact ID
     *
     * @var int unsigned
     */
    public $contact_id;
    /**
     * Organization Name.
     *
     * @var string
     */
    public $organization_name;
    /**
     * Legal Name.
     *
     * @var string
     */
    public $legal_name;
    /**
     * Standard Industry Classification Code.
     *
     * @var string
     */
    public $sic_code;
    /**
     * Optional FK to Primary Contact for this organization.
     *
     * @var int unsigned
     */
    public $primary_contact_id;
    /**
     * class constructor
     *
     * @access public
     * @return civicrm_organization
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
                'contact_id' => 'civicrm_contact:id',
                'primary_contact_id' => 'civicrm_contact:id',
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
                'contact_id' => array(
                    'name' => 'contact_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'required' => true,
                ) ,
                'organization_name' => array(
                    'name' => 'organization_name',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Organization Name') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
                    'import' => true,
                    'where' => 'civicrm_organization.organization_name',
                    'headerPattern' => '/^organization|(o(rganization\s)?name)$/i',
                    'dataPattern' => '/^\w+$/',
                    'export' => true,
                ) ,
                'legal_name' => array(
                    'name' => 'legal_name',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Legal Name') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
                    'import' => true,
                    'where' => 'civicrm_organization.legal_name',
                    'headerPattern' => '/^legal|(l(egal\s)?name)$/i',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'sic_code' => array(
                    'name' => 'sic_code',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Sic Code') ,
                    'maxlength' => 8,
                    'size' => CRM_Utils_Type::EIGHT,
                    'import' => true,
                    'where' => 'civicrm_organization.sic_code',
                    'headerPattern' => '/^sic|(s(ic\s)?code)$/i',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'primary_contact_id' => array(
                    'name' => 'primary_contact_id',
                    'type' => CRM_Utils_Type::T_INT,
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
                        self::$_import['organization'] = &$fields[$name];
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
                        self::$_export['organization'] = &$fields[$name];
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