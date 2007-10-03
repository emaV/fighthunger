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
class CRM_Contact_DAO_Contact extends CRM_Core_DAO
{
    /**
     * static instance to hold the table name
     *
     * @var string
     * @static
     */
    static $_tableName = 'civicrm_contact';
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
     * Nick Name.
     *
     * @var string
     */
    public $nick_name;
    /**
     * Which Domain owns this contact
     *
     * @var int unsigned
     */
    public $domain_id;
    /**
     * Type of Contact.
     *
     * @var enum('Individual', 'Organization', 'Household')
     */
    public $contact_type;
    /**
     *
     * @var boolean
     */
    public $do_not_email;
    /**
     *
     * @var boolean
     */
    public $do_not_phone;
    /**
     *
     * @var boolean
     */
    public $do_not_mail;
    /**
     * May be used to over-ride contact view and edit templates.
     *
     * @var string
     */
    public $contact_sub_type;
    /**
     * May be used for SSN, EIN/TIN, Household ID (census) or other applicable unique legal/government ID.
     *
     * @var string
     */
    public $legal_identifier;
    /**
     * Unique trusted external ID (generally from a legacy app/datasource). Particularly useful for deduping operations.
     *
     * @var string
     */
    public $external_identifier;
    /**
     * Name used for sorting different contact types
     *
     * @var string
     */
    public $sort_name;
    /**
     * Formatted name representing preferred format for display/print/other output.
     *
     * @var string
     */
    public $display_name;
    /**
     * optional "home page" URL for this contact.
     *
     * @var string
     */
    public $home_URL;
    /**
     * optional URL for preferred image (photo, logo, etc.) to display for this contact.
     *
     * @var string
     */
    public $image_URL;
    /**
     * What is the preferred mode of communication.
     *
     * @var string
     */
    public $preferred_communication_method;
    /**
     * What is the preferred mode of sending an email.
     *
     * @var enum('Text', 'HTML', 'Both')
     */
    public $preferred_mail_format;
    /**
     *
     * @var boolean
     */
    public $do_not_trade;
    /**
     * Key for validating requests related to this contact.
     *
     * @var string
     */
    public $hash;
    /**
     * Has the contact opted out from receiving all bulk email from the organization or site domain?
     *
     * @var boolean
     */
    public $is_opt_out;
    /**
     * Unique Contact ID
     *
     * @var int unsigned
     */
    public $id;
    /**
     * where domain_id contact come from, e.g. import, donate module insert...
     *
     * @var string
     */
    public $source;
    /**
     * class constructor
     *
     * @access public
     * @return civicrm_contact
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
                'nick_name' => array(
                    'name' => 'nick_name',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Nick Name') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
                    'import' => true,
                    'where' => 'civicrm_contact.nick_name',
                    'headerPattern' => '/n(ick\s)name|nick$/i',
                    'dataPattern' => '/^\w+$/',
                    'export' => true,
                ) ,
                'domain_id' => array(
                    'name' => 'domain_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'required' => true,
                ) ,
                'contact_type' => array(
                    'name' => 'contact_type',
                    'type' => CRM_Utils_Type::T_ENUM,
                    'title' => ts('Contact Type') ,
                ) ,
                'do_not_email' => array(
                    'name' => 'do_not_email',
                    'type' => CRM_Utils_Type::T_BOOLEAN,
                    'title' => ts('Do Not Email') ,
                    'import' => true,
                    'where' => 'civicrm_contact.do_not_email',
                    'headerPattern' => '/d(o )?(not )?(email)/i',
                    'dataPattern' => '/^\d{1,}$/',
                    'export' => true,
                ) ,
                'do_not_phone' => array(
                    'name' => 'do_not_phone',
                    'type' => CRM_Utils_Type::T_BOOLEAN,
                    'title' => ts('Do Not Phone') ,
                    'import' => true,
                    'where' => 'civicrm_contact.do_not_phone',
                    'headerPattern' => '/d(o )?(not )?(call|phone)/i',
                    'dataPattern' => '/^\d{1,}$/',
                    'export' => true,
                ) ,
                'do_not_mail' => array(
                    'name' => 'do_not_mail',
                    'type' => CRM_Utils_Type::T_BOOLEAN,
                    'title' => ts('Do Not Mail') ,
                    'import' => true,
                    'where' => 'civicrm_contact.do_not_mail',
                    'headerPattern' => '/^(d(o\s)?n(ot\s)?mail)|(\w*)?bulk\s?(\w*)$/i',
                    'dataPattern' => '/^\d{1,}$/',
                    'export' => true,
                ) ,
                'contact_sub_type' => array(
                    'name' => 'contact_sub_type',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Contact Sub Type') ,
                    'maxlength' => 64,
                    'size' => CRM_Utils_Type::BIG,
                    'import' => true,
                    'where' => 'civicrm_contact.contact_sub_type',
                    'headerPattern' => '/C(ontact )?(sub-type|sub type)/i',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'legal_identifier' => array(
                    'name' => 'legal_identifier',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Legal Identifier') ,
                    'maxlength' => 32,
                    'size' => CRM_Utils_Type::MEDIUM,
                    'import' => true,
                    'where' => 'civicrm_contact.legal_identifier',
                    'headerPattern' => '/legal\s?id/i',
                    'dataPattern' => '/\w+?\d{5,}/',
                    'export' => true,
                ) ,
                'external_identifier' => array(
                    'name' => 'external_identifier',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('External Identifier') ,
                    'maxlength' => 32,
                    'size' => CRM_Utils_Type::MEDIUM,
                    'import' => true,
                    'where' => 'civicrm_contact.external_identifier',
                    'headerPattern' => '/external\s?id/i',
                    'dataPattern' => '/^\d{11,}$/',
                    'export' => true,
                ) ,
                'sort_name' => array(
                    'name' => 'sort_name',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Sort Name') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
                ) ,
                'display_name' => array(
                    'name' => 'display_name',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Display Name') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
                ) ,
                'home_URL' => array(
                    'name' => 'home_URL',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Home URL') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
                    'import' => true,
                    'where' => 'civicrm_contact.home_URL',
                    'headerPattern' => '/^(home\sURL)|URL|web|site/i',
                    'dataPattern' => '/^[\w\/\:\.]+$/',
                    'export' => true,
                    'rule' => 'url',
                ) ,
                'image_URL' => array(
                    'name' => 'image_URL',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Image Url') ,
                    'maxlength' => 128,
                    'size' => CRM_Utils_Type::HUGE,
                    'import' => true,
                    'where' => 'civicrm_contact.image_URL',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'preferred_communication_method' => array(
                    'name' => 'preferred_communication_method',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Preferred Communication Method') ,
                    'maxlength' => 255,
                    'size' => CRM_Utils_Type::HUGE,
                    'import' => true,
                    'where' => 'civicrm_contact.preferred_communication_method',
                    'headerPattern' => '/^p(ref\w*\s)?c(omm\w*)|( meth\w*)$/i',
                    'dataPattern' => '/^\w+$/',
                    'export' => true,
                ) ,
                'preferred_mail_format' => array(
                    'name' => 'preferred_mail_format',
                    'type' => CRM_Utils_Type::T_ENUM,
                    'title' => ts('Preferred Mail Format') ,
                    'import' => true,
                    'where' => 'civicrm_contact.preferred_mail_format',
                    'headerPattern' => '/^p(ref\w*\s)?m(ail\s)?f(orm\w*)$/i',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'do_not_trade' => array(
                    'name' => 'do_not_trade',
                    'type' => CRM_Utils_Type::T_BOOLEAN,
                    'title' => ts('Do Not Trade') ,
                    'import' => true,
                    'where' => 'civicrm_contact.do_not_trade',
                    'headerPattern' => '/d(o )?(not )?(trade)/i',
                    'dataPattern' => '/^\d{1,}$/',
                    'export' => true,
                ) ,
                'hash' => array(
                    'name' => 'hash',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Hash') ,
                    'maxlength' => 32,
                    'size' => CRM_Utils_Type::MEDIUM,
                ) ,
                'is_opt_out' => array(
                    'name' => 'is_opt_out',
                    'type' => CRM_Utils_Type::T_BOOLEAN,
                    'title' => ts('No Bulk Mail (Opted-out)') ,
                    'required' => true,
                    'import' => true,
                    'where' => 'civicrm_contact.is_opt_out',
                    'headerPattern' => '',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'id' => array(
                    'name' => 'id',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Internal Contact ID') ,
                    'required' => true,
                    'import' => true,
                    'where' => 'civicrm_contact.id',
                    'headerPattern' => '/I(nternal )?C(ontact)?|ID(\s)/i',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'contact_source' => array(
                    'name' => 'source',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Source of Contact Data') ,
                    'maxlength' => 255,
                    'size' => CRM_Utils_Type::HUGE,
                    'import' => true,
                    'where' => 'civicrm_contact.source',
                    'headerPattern' => '/(S(ource\s)?o(f\s)?C(ontact\s)?Data)$/i',
                    'dataPattern' => '',
                    'export' => true,
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
                        self::$_import['contact'] = &$fields[$name];
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
                        self::$_export['contact'] = &$fields[$name];
                    } else {
                        self::$_export[$name] = &$fields[$name];
                    }
                }
            }
        }
        return self::$_export;
    }
    /**
     * returns an array containing the enum fields of the civicrm_contact table
     *
     * @return array (reference)  the array of enum fields
     */
    static function &getEnums() 
    {
        static $enums = array(
            'contact_type',
            'preferred_mail_format',
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
                'contact_type' => array(
                    'Individual' => ts('Individual') ,
                    'Organization' => ts('Organization') ,
                    'Household' => ts('Household') ,
                ) ,
                'preferred_mail_format' => array(
                    'Text' => ts('Text') ,
                    'HTML' => ts('HTML') ,
                    'Both' => ts('Both') ,
                ) ,
            );
        }
        return $translations[$field][$value];
    }
    /**
     * adds $value['foo_display'] for each $value['foo'] enum from civicrm_contact
     *
     * @param array $values (reference)  the array up for enhancing
     * @return void
     */
    static function addDisplayEnums(&$values) 
    {
        $enumFields = &CRM_Contact_DAO_Contact::getEnums();
        foreach($enumFields as $enum) {
            if (isset($values[$enum])) {
                $values[$enum.'_display'] = CRM_Contact_DAO_Contact::tsEnum($enum, $values[$enum]);
            }
        }
    }
}
?>