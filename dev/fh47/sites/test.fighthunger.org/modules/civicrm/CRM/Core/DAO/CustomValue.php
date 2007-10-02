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
class CRM_Core_DAO_CustomValue extends CRM_Core_DAO
{
    /**
     * static instance to hold the table name
     *
     * @var string
     * @static
     */
    static $_tableName = 'civicrm_custom_value';
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
     * Unique ID
     *
     * @var int unsigned
     */
    public $id;
    /**
     * Foreign key to civicrm_ext_property.
     *
     * @var int unsigned
     */
    public $custom_field_id;
    /**
     * physical tablename for entity being extended by this data, e.g. civicrm_contact
     *
     * @var string
     */
    public $entity_table;
    /**
     * FK to record in the entity table specified by entity_table column.
     *
     * @var int unsigned
     */
    public $entity_id;
    /**
     * stores data for ext property data_type = integer. This col supports signed integers.
     *
     * @var int
     */
    public $int_data;
    /**
     * stores data for ext property data_type = float.
     *
     * @var float
     */
    public $float_data;
    /**
     * stores data for ext property data_type = money.
     *
     * @var float
     */
    public $decimal_data;
    /**
     * data for ext property data_type = text.
     *
     * @var string
     */
    public $char_data;
    /**
     * data for ext property data_type = date.
     *
     * @var date
     */
    public $date_data;
    /**
     * data for ext property data_type = memo.
     *
     * @var text
     */
    public $memo_data;
    /**
     * FK to civicrm_file
     *
     * @var int unsigned
     */
    public $file_id;
    /**
     * class constructor
     *
     * @access public
     * @return civicrm_custom_value
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
                'custom_field_id' => 'civicrm_custom_field:id',
                'file_id' => 'civicrm_file:id',
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
                'custom_field_id' => array(
                    'name' => 'custom_field_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'required' => true,
                ) ,
                'entity_table' => array(
                    'name' => 'entity_table',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Entity Table') ,
                    'maxlength' => 64,
                    'size' => CRM_Utils_Type::BIG,
                ) ,
                'entity_id' => array(
                    'name' => 'entity_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'required' => true,
                ) ,
                'int_data' => array(
                    'name' => 'int_data',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Int Data') ,
                ) ,
                'float_data' => array(
                    'name' => 'float_data',
                    'type' => CRM_Utils_Type::T_FLOAT,
                    'title' => ts('Float Data') ,
                ) ,
                'decimal_data' => array(
                    'name' => 'decimal_data',
                    'type' => CRM_Utils_Type::T_MONEY,
                    'title' => ts('Decimal Data') ,
                ) ,
                'char_data' => array(
                    'name' => 'char_data',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Char Data') ,
                    'maxlength' => 255,
                    'size' => CRM_Utils_Type::HUGE,
                ) ,
                'date_data' => array(
                    'name' => 'date_data',
                    'type' => CRM_Utils_Type::T_DATE,
                    'title' => ts('Date Data') ,
                ) ,
                'memo_data' => array(
                    'name' => 'memo_data',
                    'type' => CRM_Utils_Type::T_TEXT,
                    'title' => ts('Memo Data') ,
                ) ,
                'file_id' => array(
                    'name' => 'file_id',
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
                        self::$_import['custom_value'] = &$fields[$name];
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
                        self::$_export['custom_value'] = &$fields[$name];
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