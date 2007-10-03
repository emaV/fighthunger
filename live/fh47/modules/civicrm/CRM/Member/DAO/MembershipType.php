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
class CRM_Member_DAO_MembershipType extends CRM_Core_DAO
{
    /**
     * static instance to hold the table name
     *
     * @var string
     * @static
     */
    static $_tableName = 'civicrm_membership_type';
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
     * Membership Id
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
     * Name of Membership Type
     *
     * @var string
     */
    public $name;
    /**
     * Description of Membership Type
     *
     * @var string
     */
    public $description;
    /**
     * Owner organization for this membership type. FK to Contact ID
     *
     * @var int unsigned
     */
    public $member_of_contact_id;
    /**
     * If membership is paid by a contribution - what contribution type should be used. FK to Contribution Type ID
     *
     * @var int unsigned
     */
    public $contribution_type_id;
    /**
     * Minimum fee for this membership (0 for free/complimentary memberships).
     *
     * @var float
     */
    public $minimum_fee;
    /**
     * Unit in which membership period is expressed.
     *
     * @var enum('day', 'month', 'year', 'lifetime')
     */
    public $duration_unit;
    /**
     * Number of duration units in membership period (e.g. 1 year, 12 months).
     *
     * @var int
     */
    public $duration_interval;
    /**
     * Rolling membership period starts on signup date. Fixed membership periods start on fixed_period_start_day.
     *
     * @var enum('rolling', 'fixed')
     */
    public $period_type;
    /**
     * For fixed period memberships, month and day (mmdd) on which subscription/membership will start. Period start is back-dated unless after rollover day.
     *
     * @var int
     */
    public $fixed_period_start_day;
    /**
     * For fixed period memberships, signups after this day (mmdd) rollover to next period.
     *
     * @var int
     */
    public $fixed_period_rollover_day;
    /**
     * FK to Relationship Type ID
     *
     * @var int unsigned
     */
    public $relationship_type_id;
    /**
     *
     * @var string
     */
    public $relationship_direction;
    /**
     *
     * @var string
     */
    public $visibility;
    /**
     *
     * @var int
     */
    public $weight;
    /**
     * FK to civicrm_msg_template.id
     *
     * @var int unsigned
     */
    public $renewal_msg_id;
    /**
     * Number of days prior to expiration to send renewal reminder
     *
     * @var int
     */
    public $renewal_reminder_day;
    /**
     * Is this membership_type enabled
     *
     * @var boolean
     */
    public $is_active;
    /**
     * class constructor
     *
     * @access public
     * @return civicrm_membership_type
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
                'member_of_contact_id' => 'civicrm_contact:id',
                'contribution_type_id' => 'civicrm_contribution_type:id',
                'relationship_type_id' => 'civicrm_relationship_type:id',
                'renewal_msg_id' => 'civicrm_msg_template:id',
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
                'name' => array(
                    'name' => 'name',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Name') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
                ) ,
                'description' => array(
                    'name' => 'description',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Description') ,
                    'maxlength' => 255,
                    'size' => CRM_Utils_Type::HUGE,
                ) ,
                'member_of_contact_id' => array(
                    'name' => 'member_of_contact_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'required' => true,
                ) ,
                'contribution_type_id' => array(
                    'name' => 'contribution_type_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'required' => true,
                ) ,
                'minimum_fee' => array(
                    'name' => 'minimum_fee',
                    'type' => CRM_Utils_Type::T_MONEY,
                    'title' => ts('Minimum Fee') ,
                ) ,
                'duration_unit' => array(
                    'name' => 'duration_unit',
                    'type' => CRM_Utils_Type::T_ENUM,
                    'title' => ts('Duration Unit') ,
                ) ,
                'duration_interval' => array(
                    'name' => 'duration_interval',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Duration Interval') ,
                ) ,
                'period_type' => array(
                    'name' => 'period_type',
                    'type' => CRM_Utils_Type::T_ENUM,
                    'title' => ts('Period Type') ,
                ) ,
                'fixed_period_start_day' => array(
                    'name' => 'fixed_period_start_day',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Fixed Period Start Day') ,
                ) ,
                'fixed_period_rollover_day' => array(
                    'name' => 'fixed_period_rollover_day',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Fixed Period Rollover Day') ,
                ) ,
                'relationship_type_id' => array(
                    'name' => 'relationship_type_id',
                    'type' => CRM_Utils_Type::T_INT,
                ) ,
                'relationship_direction' => array(
                    'name' => 'relationship_direction',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Relationship Direction') ,
                    'maxlength' => 6,
                    'size' => CRM_Utils_Type::EIGHT,
                ) ,
                'visibility' => array(
                    'name' => 'visibility',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Visible') ,
                    'maxlength' => 64,
                    'size' => CRM_Utils_Type::BIG,
                ) ,
                'weight' => array(
                    'name' => 'weight',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Weight') ,
                ) ,
                'renewal_msg_id' => array(
                    'name' => 'renewal_msg_id',
                    'type' => CRM_Utils_Type::T_INT,
                ) ,
                'renewal_reminder_day' => array(
                    'name' => 'renewal_reminder_day',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Renewal Reminder Day') ,
                ) ,
                'is_active' => array(
                    'name' => 'is_active',
                    'type' => CRM_Utils_Type::T_BOOLEAN,
                    'title' => ts('Is Active') ,
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
                        self::$_import['membership_type'] = &$fields[$name];
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
                        self::$_export['membership_type'] = &$fields[$name];
                    } else {
                        self::$_export[$name] = &$fields[$name];
                    }
                }
            }
        }
        return self::$_export;
    }
    /**
     * returns an array containing the enum fields of the civicrm_membership_type table
     *
     * @return array (reference)  the array of enum fields
     */
    static function &getEnums() 
    {
        static $enums = array(
            'duration_unit',
            'period_type',
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
                'duration_unit' => array(
                    'day' => ts('day') ,
                    'month' => ts('month') ,
                    'year' => ts('year') ,
                    'lifetime' => ts('lifetime') ,
                ) ,
                'period_type' => array(
                    'rolling' => ts('rolling') ,
                    'fixed' => ts('fixed') ,
                ) ,
            );
        }
        return $translations[$field][$value];
    }
    /**
     * adds $value['foo_display'] for each $value['foo'] enum from civicrm_membership_type
     *
     * @param array $values (reference)  the array up for enhancing
     * @return void
     */
    static function addDisplayEnums(&$values) 
    {
        $enumFields = &CRM_Member_DAO_MembershipType::getEnums();
        foreach($enumFields as $enum) {
            if (isset($values[$enum])) {
                $values[$enum.'_display'] = CRM_Member_DAO_MembershipType::tsEnum($enum, $values[$enum]);
            }
        }
    }
}
?>