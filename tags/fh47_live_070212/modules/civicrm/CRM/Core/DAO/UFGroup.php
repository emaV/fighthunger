<?php
/*
+--------------------------------------------------------------------+
| CiviCRM version 1.5                                                |
+--------------------------------------------------------------------+
| Copyright CiviCRM LLC (c) 2004-2006                                |
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
require_once 'CRM/Core/DAO.php';
require_once 'CRM/Utils/Type.php';
class CRM_Core_DAO_UFGroup extends CRM_Core_DAO {
    /**
    * static instance to hold the table name
    *
    * @var string
    * @static
    */
    static $_tableName = 'civicrm_uf_group';
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
    * Unique table ID
    *
    * @var int unsigned
    */
    public $id;
    /**
    * Which Domain owns this form.
    *
    * @var int unsigned
    */
    public $domain_id;
    /**
    * Is this form currently active? If false, hide all related fields for all sharing contexts.
    *
    * @var boolean
    */
    public $is_active;
    /**
    * Type of form.
    *
    * @var enum('CiviCRM Profile')
    */
    public $form_type;
    /**
    * Form title.
    *
    * @var string
    */
    public $title;
    /**
    * Will this group be in collapsed or expanded mode on initial display ?
    *
    * @var int unsigned
    */
    public $collapse_display;
    /**
    * Description and/or help text to display before fields in form.
    *
    * @var text
    */
    public $help_pre;
    /**
    * Description and/or help text to display after fields in form.
    *
    * @var text
    */
    public $help_post;
    /**
    * Group id, foriegn key from civicrm_group
    *
    * @var int unsigned
    */
    public $limit_listings_group_id;
    /**
    * Redirect to URL.
    *
    * @var string
    */
    public $post_URL;
    /**
    * foreign key to civicrm_group_id
    *
    * @var int unsigned
    */
    public $add_to_group_id;
    /**
    * Should a CAPTCHA widget be included this Profile form.
    *
    * @var boolean
    */
    public $add_captcha;
    /**
    * Do we want to map results from this profile.
    *
    * @var boolean
    */
    public $is_map;
    /**
    * Redirect to URL when Cancle button clik .
    *
    * @var string
    */
    public $cancel_URL;
    /**
    * class constructor
    *
    * @access public
    * @return civicrm_uf_group
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
                'domain_id'=>'civicrm_domain:id',
                'limit_listings_group_id'=>'civicrm_group:id',
                'add_to_group_id'=>'civicrm_group:id',
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
                'id'=>array(
                    'name'=>'id',
                    'type'=>CRM_Utils_Type::T_INT,
                    'required'=>true,
                ) ,
                'domain_id'=>array(
                    'name'=>'domain_id',
                    'type'=>CRM_Utils_Type::T_INT,
                    'required'=>true,
                ) ,
                'is_active'=>array(
                    'name'=>'is_active',
                    'type'=>CRM_Utils_Type::T_BOOLEAN,
                ) ,
                'form_type'=>array(
                    'name'=>'form_type',
                    'type'=>CRM_Utils_Type::T_ENUM,
                    'title'=>ts('Form Type') ,
                ) ,
                'title'=>array(
                    'name'=>'title',
                    'type'=>CRM_Utils_Type::T_STRING,
                    'title'=>ts('Title') ,
                    'maxlength'=>64,
                    'size'=>CRM_Utils_Type::BIG,
                ) ,
                'collapse_display'=>array(
                    'name'=>'collapse_display',
                    'type'=>CRM_Utils_Type::T_INT,
                    'title'=>ts('Collapse Display') ,
                ) ,
                'help_pre'=>array(
                    'name'=>'help_pre',
                    'type'=>CRM_Utils_Type::T_TEXT,
                    'title'=>ts('Help Pre') ,
                    'rows'=>4,
                    'cols'=>80,
                ) ,
                'help_post'=>array(
                    'name'=>'help_post',
                    'type'=>CRM_Utils_Type::T_TEXT,
                    'title'=>ts('Help Post') ,
                    'rows'=>4,
                    'cols'=>80,
                ) ,
                'limit_listings_group_id'=>array(
                    'name'=>'limit_listings_group_id',
                    'type'=>CRM_Utils_Type::T_INT,
                ) ,
                'post_URL'=>array(
                    'name'=>'post_URL',
                    'type'=>CRM_Utils_Type::T_STRING,
                    'title'=>ts('Post Url') ,
                    'maxlength'=>255,
                    'size'=>CRM_Utils_Type::HUGE,
                ) ,
                'add_to_group_id'=>array(
                    'name'=>'add_to_group_id',
                    'type'=>CRM_Utils_Type::T_INT,
                ) ,
                'add_captcha'=>array(
                    'name'=>'add_captcha',
                    'type'=>CRM_Utils_Type::T_BOOLEAN,
                    'title'=>ts('Add Captcha') ,
                ) ,
                'is_map'=>array(
                    'name'=>'is_map',
                    'type'=>CRM_Utils_Type::T_BOOLEAN,
                ) ,
                'cancel_URL'=>array(
                    'name'=>'cancel_URL',
                    'type'=>CRM_Utils_Type::T_STRING,
                    'title'=>ts('Cancel Url') ,
                    'maxlength'=>255,
                    'size'=>CRM_Utils_Type::HUGE,
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
            foreach($fields as $name=>$field) {
                if (CRM_Utils_Array::value('import', $field)) {
                    if ($prefix) {
                        self::$_import['uf_group'] = &$fields[$name];
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
            foreach($fields as $name=>$field) {
                if (CRM_Utils_Array::value('export', $field)) {
                    if ($prefix) {
                        self::$_export['uf_group'] = &$fields[$name];
                    } else {
                        self::$_export[$name] = &$fields[$name];
                    }
                }
            }
        }
        return self::$_export;
    }
    /**
    * returns an array containing the enum fields of the civicrm_uf_group table
    *
    * @return array (reference)  the array of enum fields
    */
    static function &getEnums() 
    {
        static $enums = array(
            'form_type',
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
                'form_type'=>array(
                    'CiviCRM Profile'=>ts('CiviCRM Profile') ,
                ) ,
            );
        }
        return $translations[$field][$value];
    }
    /**
    * adds $value['foo_display'] for each $value['foo'] enum from civicrm_uf_group
    *
    * @param array $values (reference)  the array up for enhancing
    * @return void
    */
    static function addDisplayEnums(&$values) 
    {
        $enumFields = &CRM_Core_DAO_UFGroup::getEnums();
        foreach($enumFields as $enum) {
            if (isset($values[$enum])) {
                $values[$enum.'_display'] = CRM_Core_DAO_UFGroup::tsEnum($enum, $values[$enum]);
            }
        }
    }
}
?>