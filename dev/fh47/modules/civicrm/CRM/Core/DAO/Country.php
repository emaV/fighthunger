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
class CRM_Core_DAO_Country extends CRM_Core_DAO
{
    /**
     * static instance to hold the table name
     *
     * @var string
     * @static
     */
    static $_tableName = 'civicrm_country';
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
     * Country Id
     *
     * @var int unsigned
     */
    public $id;
    /**
     * Country Name
     *
     * @var string
     */
    public $name;
    /**
     * ISO Code
     *
     * @var string
     */
    public $iso_code;
    /**
     * National prefix to be used when dialing TO this country.
     *
     * @var string
     */
    public $country_code;
    /**
     * International direct dialing prefix from within the country TO another country
     *
     * @var string
     */
    public $idd_prefix;
    /**
     * Access prefix to call within a country to a different area
     *
     * @var string
     */
    public $ndd_prefix;
    /**
     * class constructor
     *
     * @access public
     * @return civicrm_country
     */
    function __construct() 
    {
        parent::__construct();
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
                'name' => array(
                    'name' => 'name',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Country') ,
                    'maxlength' => 64,
                    'size' => CRM_Utils_Type::BIG,
                    'import' => true,
                    'where' => 'civicrm_country.name',
                    'headerPattern' => '/country/i',
                    'dataPattern' => '/^[A-Z][a-z]+\.?(\s+[A-Z][a-z]+){0,3}$/',
                    'export' => true,
                ) ,
                'iso_code' => array(
                    'name' => 'iso_code',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Iso Code') ,
                    'maxlength' => 2,
                    'size' => CRM_Utils_Type::TWO,
                ) ,
                'country_code' => array(
                    'name' => 'country_code',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Country Code') ,
                    'maxlength' => 4,
                    'size' => CRM_Utils_Type::FOUR,
                ) ,
                'idd_prefix' => array(
                    'name' => 'idd_prefix',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Idd Prefix') ,
                    'maxlength' => 4,
                    'size' => CRM_Utils_Type::FOUR,
                ) ,
                'ndd_prefix' => array(
                    'name' => 'ndd_prefix',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Ndd Prefix') ,
                    'maxlength' => 4,
                    'size' => CRM_Utils_Type::FOUR,
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
                        self::$_import['country'] = &$fields[$name];
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
                        self::$_export['country'] = &$fields[$name];
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