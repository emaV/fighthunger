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
class CRM_Activity_DAO_Phonecall extends CRM_Core_DAO
{
    /**
     * static instance to hold the table name
     *
     * @var string
     * @static
     */
    static $_tableName = 'civicrm_phonecall';
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
     * Unique Phone call ID
     *
     * @var int unsigned
     */
    public $id;
    /**
     * Contact ID of person making the call. This will generally an authenticated user.
     *
     * @var int unsigned
     */
    public $source_contact_id;
    /**
     * Name of table where item being referenced is stored.
     *
     * @var string
     */
    public $target_entity_table;
    /**
     * Foreign key to the referenced item.
     *
     * @var int unsigned
     */
    public $target_entity_id;
    /**
     * Short description of the subject of this call.
     *
     * @var string
     */
    public $subject;
    /**
     * Date and time phonecall is scheduled to be made.
     *
     * @var datetime
     */
    public $scheduled_date_time;
    /**
     * Planned or actual duration of call - hours.
     *
     * @var int unsigned
     */
    public $duration_hours;
    /**
     * Planned or actual duration of call - minutes.
     *
     * @var int unsigned
     */
    public $duration_minutes;
    /**
     * Phone ID of the number called (optional - used if an existing phone number is selected).
     *
     * @var int unsigned
     */
    public $phone_id;
    /**
     * Phone number in case the number does not exist in the civicrm_phone table.
     *
     * @var string
     */
    public $phone_number;
    /**
     * Details about the call.
     *
     * @var text
     */
    public $details;
    /**
     * What is the status of this phone call? Completed calls result in activity history entry.
     *
     * @var enum('Scheduled', 'Left Message', 'Unreachable', 'Completed')
     */
    public $status;
    /**
     * Parent phone call ID (if this is a follow-up item). This is not currently implemented
     *
     * @var int unsigned
     */
    public $parent_id;
    /**
     * class constructor
     *
     * @access public
     * @return civicrm_phonecall
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
                'source_contact_id' => 'civicrm_contact:id',
                'phone_id' => 'civicrm_phone:id',
                'parent_id' => 'civicrm_phonecall:id',
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
                'source_contact_id' => array(
                    'name' => 'source_contact_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'required' => true,
                ) ,
                'target_entity_table' => array(
                    'name' => 'target_entity_table',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Target Entity Table') ,
                    'required' => true,
                    'maxlength' => 64,
                    'size' => CRM_Utils_Type::BIG,
                ) ,
                'target_entity_id' => array(
                    'name' => 'target_entity_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'required' => true,
                ) ,
                'subject' => array(
                    'name' => 'subject',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Subject') ,
                    'maxlength' => 64,
                    'size' => CRM_Utils_Type::BIG,
                ) ,
                'scheduled_date_time' => array(
                    'name' => 'scheduled_date_time',
                    'type' => CRM_Utils_Type::T_DATE+CRM_Utils_Type::T_TIME,
                    'title' => ts('Scheduled Date Time') ,
                ) ,
                'duration_hours' => array(
                    'name' => 'duration_hours',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Duration Hours') ,
                ) ,
                'duration_minutes' => array(
                    'name' => 'duration_minutes',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Duration Minutes') ,
                ) ,
                'phone_id' => array(
                    'name' => 'phone_id',
                    'type' => CRM_Utils_Type::T_INT,
                ) ,
                'phone_number' => array(
                    'name' => 'phone_number',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Phone Number') ,
                    'maxlength' => 64,
                    'size' => CRM_Utils_Type::BIG,
                ) ,
                'details' => array(
                    'name' => 'details',
                    'type' => CRM_Utils_Type::T_TEXT,
                    'title' => ts('Details') ,
                ) ,
                'status' => array(
                    'name' => 'status',
                    'type' => CRM_Utils_Type::T_ENUM,
                    'title' => ts('Status') ,
                ) ,
                'parent_id' => array(
                    'name' => 'parent_id',
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
                        self::$_import['phonecall'] = &$fields[$name];
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
                        self::$_export['phonecall'] = &$fields[$name];
                    } else {
                        self::$_export[$name] = &$fields[$name];
                    }
                }
            }
        }
        return self::$_export;
    }
    /**
     * returns an array containing the enum fields of the civicrm_phonecall table
     *
     * @return array (reference)  the array of enum fields
     */
    static function &getEnums() 
    {
        static $enums = array(
            'status',
        );
        return $enums;
    }
    /**
     * returns a ts()-translated enum value for display purposes
     *
     * @param string $field  the enum field in question
     * @param string $value  the enum value up for translation
     *
     * @return string  the display value of the enum
     */
    static function tsEnum($field, $value) 
    {
        static $translations = null;
        if (!$translations) {
            $translations = array(
                'status' => array(
                    'Scheduled' => ts('Scheduled') ,
                    'Left Message' => ts('Left Message') ,
                    'Unreachable' => ts('Unreachable') ,
                    'Completed' => ts('Completed') ,
                ) ,
            );
        }
        return $translations[$field][$value];
    }
    /**
     * adds $value['foo_display'] for each $value['foo'] enum from civicrm_phonecall
     *
     * @param array $values (reference)  the array up for enhancing
     * @return void
     */
    static function addDisplayEnums(&$values) 
    {
        $enumFields = &CRM_Activity_DAO_Phonecall::getEnums();
        foreach($enumFields as $enum) {
            if (isset($values[$enum])) {
                $values[$enum.'_display'] = CRM_Activity_DAO_Phonecall::tsEnum($enum, $values[$enum]);
            }
        }
    }
}
?>