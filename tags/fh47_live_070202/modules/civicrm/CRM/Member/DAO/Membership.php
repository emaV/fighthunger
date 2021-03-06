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
class CRM_Member_DAO_Membership extends CRM_Core_DAO {
    /**
    * static instance to hold the table name
    *
    * @var string
    * @static
    */
    static $_tableName = 'civicrm_membership';
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
    * FK to Contact ID
    *
    * @var int unsigned
    */
    public $contact_id;
    /**
    * FK to Membership Type
    *
    * @var int unsigned
    */
    public $membership_type_id;
    /**
    * Beginning of initial membership period (member since...).
    *
    * @var date
    */
    public $join_date;
    /**
    * Beginning of current uninterrupted membership period.
    *
    * @var date
    */
    public $start_date;
    /**
    * Current membership period expire date.
    *
    * @var date
    */
    public $end_date;
    /**
    *
    * @var string
    */
    public $source;
    /**
    * FK to Membership Status
    *
    * @var int unsigned
    */
    public $status_id;
    /**
    * Admin users may set a manual status which overrides the calculated status. When this flag is true, automated status update scripts should NOT modify status for the record.
    *
    * @var boolean
    */
    public $is_override;
    /**
    * class constructor
    *
    * @access public
    * @return civicrm_membership
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
                'contact_id'=>'civicrm_contact:id',
                'membership_type_id'=>'civicrm_membership_type:id',
                'status_id'=>'civicrm_membership_status:id',
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
                'contact_id'=>array(
                    'name'=>'contact_id',
                    'type'=>CRM_Utils_Type::T_INT,
                    'required'=>true,
                ) ,
                'membership_type_id'=>array(
                    'name'=>'membership_type_id',
                    'type'=>CRM_Utils_Type::T_INT,
                    'required'=>true,
                ) ,
                'join_date'=>array(
                    'name'=>'join_date',
                    'type'=>CRM_Utils_Type::T_DATE,
                    'title'=>ts('Member Since') ,
                ) ,
                'start_date'=>array(
                    'name'=>'start_date',
                    'type'=>CRM_Utils_Type::T_DATE,
                    'title'=>ts('Membership Start Date') ,
                ) ,
                'end_date'=>array(
                    'name'=>'end_date',
                    'type'=>CRM_Utils_Type::T_DATE,
                    'title'=>ts('Membership Expiration Date') ,
                ) ,
                'source'=>array(
                    'name'=>'source',
                    'type'=>CRM_Utils_Type::T_STRING,
                    'title'=>ts('Source') ,
                    'maxlength'=>128,
                    'size'=>CRM_Utils_Type::HUGE,
                ) ,
                'status_id'=>array(
                    'name'=>'status_id',
                    'type'=>CRM_Utils_Type::T_INT,
                    'title'=>ts('Membership Status') ,
                    'required'=>true,
                ) ,
                'is_override'=>array(
                    'name'=>'is_override',
                    'type'=>CRM_Utils_Type::T_BOOLEAN,
                    'title'=>ts('Status Override?') ,
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
                        self::$_import['membership'] = &$fields[$name];
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
                        self::$_export['membership'] = &$fields[$name];
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