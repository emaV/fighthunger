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
class CRM_Core_DAO_ActivityHistory extends CRM_Core_DAO
{
    /**
     * static instance to hold the table name
     *
     * @var string
     * @static
     */
    static $_tableName = 'civicrm_activity_history';
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
     * table record id
     *
     * @var int unsigned
     */
    public $id;
    /**
     * physical tablename for entity whose activity history is tracked, e.g. civicrm_contact
     *
     * @var string
     */
    public $entity_table;
    /**
     * FK to entity table specified in entity_table column
     *
     * @var int unsigned
     */
    public $entity_id;
    /**
     * sortable label for this activity assigned be registering module or user (e.g. Phone Call)
     *
     * @var string
     */
    public $activity_type;
    /**
     * Display name of module which registered this activity
     *
     * @var string
     */
    public $module;
    /**
     * Function to call which will return URL for viewing details
     *
     * @var string
     */
    public $callback;
    /**
     * FK to details item - passed to callback
     *
     * @var int unsigned
     */
    public $activity_id;
    /**
     * brief description of activity for summary display - as populated by registering module
     *
     * @var string
     */
    public $activity_summary;
    /**
     * when did this activity occur
     *
     * @var datetime
     */
    public $activity_date;
    /**
     * OPTIONAL FK to civicrm_relationship.id. Which relationship (of this contact) potentially triggered this activity, i.e. he donated because he was a Board Member of Org X / Employee of Org Y
     *
     * @var int unsigned
     */
    public $relationship_id;
    /**
     * OPTIONAL FK to civicrm_group.id. Was this part of a group communication that triggered this activity?
     *
     * @var int unsigned
     */
    public $group_id;
    /**
     *
     * @var boolean
     */
    public $is_test;
    /**
     * class constructor
     *
     * @access public
     * @return civicrm_activity_history
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
                'relationship_id' => 'civicrm_relationship:id',
                'group_id' => 'civicrm_group:id',
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
                    'title' => ts('Contact ID (match to contact)') ,
                    'required' => true,
                    'import' => true,
                    'where' => 'civicrm_activity_history.entity_id',
                    'headerPattern' => '/contact(.?id)?/i',
                    'dataPattern' => '/^\d+$/',
                    'export' => true,
                ) ,
                'activity_type' => array(
                    'name' => 'activity_type',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Activity Type') ,
                    'maxlength' => 64,
                    'size' => CRM_Utils_Type::BIG,
                    'import' => true,
                    'where' => 'civicrm_activity_history.activity_type',
                    'headerPattern' => '/activity type/i',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'module' => array(
                    'name' => 'module',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Module') ,
                    'maxlength' => 64,
                    'size' => CRM_Utils_Type::BIG,
                    'import' => true,
                    'where' => 'civicrm_activity_history.module',
                    'headerPattern' => '/module/i',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'callback' => array(
                    'name' => 'callback',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Callback Method') ,
                    'maxlength' => 64,
                    'size' => CRM_Utils_Type::BIG,
                    'import' => true,
                    'where' => 'civicrm_activity_history.callback',
                    'headerPattern' => '/callback|method/i',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'activity_id' => array(
                    'name' => 'activity_id',
                    'type' => CRM_Utils_Type::T_INT,
                    'title' => ts('Activity ID') ,
                    'required' => true,
                    'import' => true,
                    'where' => 'civicrm_activity_history.activity_id',
                    'headerPattern' => '/actvity(.?id)?/i',
                    'dataPattern' => '/^\d+$/',
                    'export' => true,
                ) ,
                'activity_summary' => array(
                    'name' => 'activity_summary',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Activity Summary') ,
                    'maxlength' => 255,
                    'size' => CRM_Utils_Type::HUGE,
                    'import' => true,
                    'where' => 'civicrm_activity_history.activity_summary',
                    'headerPattern' => '/activity summary/i',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'activity_date' => array(
                    'name' => 'activity_date',
                    'type' => CRM_Utils_Type::T_DATE+CRM_Utils_Type::T_TIME,
                    'title' => ts('Activity Date') ,
                    'import' => true,
                    'where' => 'civicrm_activity_history.activity_date',
                    'headerPattern' => '/activity date/i',
                    'dataPattern' => '',
                    'export' => true,
                ) ,
                'relationship_id' => array(
                    'name' => 'relationship_id',
                    'type' => CRM_Utils_Type::T_INT,
                ) ,
                'group_id' => array(
                    'name' => 'group_id',
                    'type' => CRM_Utils_Type::T_INT,
                ) ,
                'is_test' => array(
                    'name' => 'is_test',
                    'type' => CRM_Utils_Type::T_BOOLEAN,
                    'title' => ts('Test') ,
                    'import' => true,
                    'where' => 'civicrm_activity_history.is_test',
                    'headerPattern' => '',
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
                        self::$_import['activity_history'] = &$fields[$name];
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
                        self::$_export['activity_history'] = &$fields[$name];
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