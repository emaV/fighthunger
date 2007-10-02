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
class CRM_Contact_DAO_Individual extends CRM_Core_DAO
{
    /**
     * static instance to hold the table name
     *
     * @var string
     * @static
     */
    static $_tableName = 'civicrm_individual';
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
     * Unique Individual ID
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
     * First Name.
     *
     * @var string
     */
    public $first_name;
    /**
     * Middle Name.
     *
     * @var string
     */
    public $middle_name;
    /**
     * Last Name.
     *
     * @var string
     */
    public $last_name;
    /**
     * Prefix or Title for name (Ms, Mr...). FK to prefix ID
     *
     * @var int unsigned
     */
    public $prefix_id;
    /**
     * Suffix for name (Jr, Sr...). FK to suffix ID
     *
     * @var int unsigned
     */
    public $suffix_id;
    /**
     * Preferred greeting format.
     *
     * @var enum('Formal', 'Informal', 'Honorific', 'Custom', 'Other')
     */
    public $greeting_type;
    /**
     * Custom greeting message.
     *
     * @var string
     */
    public $custom_greeting;
    /**
     * Job Title
     *
     * @var string
     */
    public $job_title;
    /**
     * FK to gender ID
     *
     * @var int unsigned
     */
    public $gender_id;
    /**
     * Date of birth
     *
     * @var date
     */
    public $birth_date;
    /**
     *
     * @var boolean
     */
    public $is_deceased;
    /**
     * Date of deceased
     *
     * @var date
     */
    public $deceased_date;
    /**
     * OPTIONAL FK to civicrm_contact_household record. If NOT NULL, direct phone communications to household rather than individual location.
     *
     * @var int unsigned
     */
    public $phone_to_household_id;
    /**
     * OPTIONAL FK to civicrm_contact_household record. If NOT NULL, direct phone communications to household rather than individual location.
     *
     * @var int unsigned
     */
    public $email_to_household_id;
    /**
     * OPTIONAL FK to civicrm_contact_household record. If NOT NULL, direct mail communications to household rather than individual location.
     *
     * @var int unsigned
     */
    public $mail_to_household_id;
    /**
     * class constructor
     *
     * @access public
     * @return civicrm_individual
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
                'first_name' => array(
                    'name' => 'first_name',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('First Name') ,
                    'maxlength' => 64,
                    'size' => CRM_Utils_Type::BIG,
                    'import' => true,
                    'where' => 'civicrm_individual.first_name',
                    'headerPattern' => '/^first|(f(irst\s)?name)$/i',
                    'dataPattern' => '/^\w+$/',
                    'export' => true,
                ) ,
                'middle_name' => array(
                    'name' => 'middle_name',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Middle Name') ,
                    'maxlength' => 64,
                    'size' => CRM_Utils_Type::BIG,
                    'import' => true,
                    'where' => 'civicrm_individual.middle_name',
                    'headerPattern' => '/^middle|(m(iddle\s)?name)$/i',
                    'dataPattern' => '/^\w+$/',
                    'export' => true,
                ) ,
                'last_name' => array(
                    'name' => 'last_name',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Last Name') ,
                    'maxlength' => 64,
                    'size' => CRM_Utils_Type::BIG,
                    'import' => true,
                    'where' => 'civicrm_individual.last_name',
                    'headerPattern' => '/^(l(ast\s)?name)$/i',
                    'dataPattern' => '/^\w+(\s\w+)?+$/',
                    'export' => true,
                ) ,
                'prefix_id' => array(
                    'name' => 'prefix_id',
                    'type' => CRM_Utils_Type::T_INT,
                ) ,
                'suffix_id' => array(
                    'name' => 'suffix_id',
                    'type' => CRM_Utils_Type::T_INT,
                ) ,
                'greeting_type' => array(
                    'name' => 'greeting_type',
                    'type' => CRM_Utils_Type::T_ENUM,
                    'title' => ts('Greeting Type') ,
                ) ,
                'custom_greeting' => array(
                    'name' => 'custom_greeting',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Custom Greeting') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
                ) ,
                'job_title' => array(
                    'name' => 'job_title',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Job Title') ,
                    'maxlength' => 64,
                    'size' => CRM_Utils_Type::BIG,
                    'import' => true,
                    'where' => 'civicrm_individual.job_title',
                    'headerPattern' => '/^job|(j(ob\s)?title)$/i',
                    'dataPattern' => '//',
                    'export' => true,
                ) ,
                'gender_id' => array(
                    'name' => 'gender_id',
                    'type' => CRM_Utils_Type::T_INT,
                ) ,
                'birth_date' => array(
                    'name' => 'birth_date',
                    'type' => CRM_Utils_Type::T_DATE,
                    'title' => ts('Birth Date') ,
                    'import' => true,
                    'where' => 'civicrm_individual.birth_date',
                    'headerPattern' => '/^birth|(b(irth\s)?date)|D(\W*)O(\W*)B(\W*)$/i',
                    'dataPattern' => '/\d{4}-?\d{2}-?\d{2}/',
                    'export' => true,
                ) ,
                'is_deceased' => array(
                    'name' => 'is_deceased',
                    'type' => CRM_Utils_Type::T_BOOLEAN,
                    'title' => ts('Is Deceased') ,
                    'import' => true,
                    'where' => 'civicrm_individual.is_deceased',
                    'headerPattern' => '/i(s\s)?d(eceased)$/i',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'deceased_date' => array(
                    'name' => 'deceased_date',
                    'type' => CRM_Utils_Type::T_DATE,
                    'title' => ts('Deceased Date') ,
                    'import' => true,
                    'where' => 'civicrm_individual.deceased_date',
                    'headerPattern' => '/^deceased|(d(eceased\s)?date)$/i',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'phone_to_household_id' => array(
                    'name' => 'phone_to_household_id',
                    'type' => CRM_Utils_Type::T_INT,
                ) ,
                'email_to_household_id' => array(
                    'name' => 'email_to_household_id',
                    'type' => CRM_Utils_Type::T_INT,
                ) ,
                'mail_to_household_id' => array(
                    'name' => 'mail_to_household_id',
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
                        self::$_import['individual'] = &$fields[$name];
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
                        self::$_export['individual'] = &$fields[$name];
                    } else {
                        self::$_export[$name] = &$fields[$name];
                    }
                }
            }
        }
        return self::$_export;
    }
    /**
     * returns an array containing the enum fields of the civicrm_individual table
     *
     * @return array (reference)  the array of enum fields
     */
    static function &getEnums() 
    {
        static $enums = array(
            'greeting_type',
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
                'greeting_type' => array(
                    'Formal' => ts('Formal') ,
                    'Informal' => ts('Informal') ,
                    'Honorific' => ts('Honorific') ,
                    'Custom' => ts('Custom') ,
                    'Other' => ts('Other') ,
                ) ,
            );
        }
        return $translations[$field][$value];
    }
    /**
     * adds $value['foo_display'] for each $value['foo'] enum from civicrm_individual
     *
     * @param array $values (reference)  the array up for enhancing
     * @return void
     */
    static function addDisplayEnums(&$values) 
    {
        $enumFields = &CRM_Contact_DAO_Individual::getEnums();
        foreach($enumFields as $enum) {
            if (isset($values[$enum])) {
                $values[$enum.'_display'] = CRM_Contact_DAO_Individual::tsEnum($enum, $values[$enum]);
            }
        }
    }
}
?>