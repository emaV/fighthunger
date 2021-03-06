<?php
/*
+--------------------------------------------------------------------+
| CiviCRM version 1.1                                                |
+--------------------------------------------------------------------+
| Copyright (c) 2005 Social Source Foundation                        |
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
* @copyright Donald A. Lobo 01/15/2005
* $Id$
*
*/
$GLOBALS['_CRM_CORE_DAO_MAPPING']['_tableName'] =  'civicrm_mapping';
$GLOBALS['_CRM_CORE_DAO_MAPPING']['_fields'] =  null;
$GLOBALS['_CRM_CORE_DAO_MAPPING']['_links'] =  null;
$GLOBALS['_CRM_CORE_DAO_MAPPING']['_import'] =  null;
$GLOBALS['_CRM_CORE_DAO_MAPPING']['_export'] =  null;
$GLOBALS['_CRM_CORE_DAO_MAPPING']['enums'] =  array(
            'mapping_type',
        );
$GLOBALS['_CRM_CORE_DAO_MAPPING']['translations'] =  null;

require_once 'CRM/Core/DAO.php';
require_once 'CRM/Utils/Type.php';
class CRM_Core_DAO_Mapping extends CRM_Core_DAO {
    /**
    * static instance to hold the table name
    *
    * @var string
    * @static
    */
    
    /**
    * static instance to hold the field values
    *
    * @var array
    * @static
    */
    
    /**
    * static instance to hold the FK relationships
    *
    * @var string
    * @static
    */
    
    /**
    * static instance to hold the values that can
    * be imported / apu
    *
    * @var array
    * @static
    */
    
    /**
    * static instance to hold the values that can
    * be exported / apu
    *
    * @var array
    * @static
    */
    
    /**
    * Mapping ID
    *
    * @var int unsigned
    */
    var $id;
    /**
    * Domain to which this mapping belongs
    *
    * @var int unsigned
    */
    var $domain_id;
    /**
    * Name of Mapping
    *
    * @var string
    */
    var $name;
    /**
    * Description of Mapping.
    *
    * @var string
    */
    var $description;
    /**
    * Type of Mapping.
    *
    * @var enum('Export', 'Import', 'Export Contributions', 'Import Contributions')
    */
    var $mapping_type;
    /**
    * class constructor
    *
    * @access public
    * @return civicrm_mapping
    */
    function CRM_Core_DAO_Mapping() 
    {
        parent::CRM_Core_DAO();
    }
    /**
    * return foreign links
    *
    * @access public
    * @return array
    */
    function &links() 
    {
        if (!($GLOBALS['_CRM_CORE_DAO_MAPPING']['_links'])) {
            $GLOBALS['_CRM_CORE_DAO_MAPPING']['_links'] = array(
                'domain_id'=>'civicrm_domain:id',
            );
        }
        return $GLOBALS['_CRM_CORE_DAO_MAPPING']['_links'];
    }
    /**
    * returns all the column names of this table
    *
    * @access public
    * @return array
    */
    function &fields() 
    {
        if (!($GLOBALS['_CRM_CORE_DAO_MAPPING']['_fields'])) {
            $GLOBALS['_CRM_CORE_DAO_MAPPING']['_fields'] = array(
                'id'=>array(
                    'name'=>'id',
                    'type'=>CRM_UTILS_TYPE_T_INT,
                    'required'=>true,
                ) ,
                'domain_id'=>array(
                    'name'=>'domain_id',
                    'type'=>CRM_UTILS_TYPE_T_INT,
                    'required'=>true,
                ) ,
                'name'=>array(
                    'name'=>'name',
                    'type'=>CRM_UTILS_TYPE_T_STRING,
                    'title'=>ts('Name') ,
                    'maxlength'=>64,
                    'size'=>CRM_UTILS_TYPE_BIG,
                ) ,
                'description'=>array(
                    'name'=>'description',
                    'type'=>CRM_UTILS_TYPE_T_STRING,
                    'title'=>ts('Description') ,
                    'maxlength'=>255,
                    'size'=>CRM_UTILS_TYPE_HUGE,
                ) ,
                'mapping_type'=>array(
                    'name'=>'mapping_type',
                    'type'=>CRM_UTILS_TYPE_T_ENUM,
                    'title'=>ts('Mapping Type') ,
                ) ,
            );
        }
        return $GLOBALS['_CRM_CORE_DAO_MAPPING']['_fields'];
    }
    /**
    * returns the names of this table
    *
    * @access public
    * @return string
    */
    function getTableName() 
    {
        return $GLOBALS['_CRM_CORE_DAO_MAPPING']['_tableName'];
    }
    /**
    * returns the list of fields that can be imported
    *
    * @access public
    * return array
    */
    function &import($prefix = false) 
    {
        if (!($GLOBALS['_CRM_CORE_DAO_MAPPING']['_import'])) {
            $GLOBALS['_CRM_CORE_DAO_MAPPING']['_import'] = array();
            $fields = &CRM_Core_DAO_Mapping::fields();
            foreach($fields as $name=>$field) {
                if (CRM_Utils_Array::value('import', $field)) {
                    if ($prefix) {
                        $GLOBALS['_CRM_CORE_DAO_MAPPING']['_import']['mapping'] = &$fields[$name];
                    } else {
                        $GLOBALS['_CRM_CORE_DAO_MAPPING']['_import'][$name] = &$fields[$name];
                    }
                }
            }
        }
        return $GLOBALS['_CRM_CORE_DAO_MAPPING']['_import'];
    }
    /**
    * returns the list of fields that can be exported
    *
    * @access public
    * return array
    */
    function &export($prefix = false) 
    {
        if (!($GLOBALS['_CRM_CORE_DAO_MAPPING']['_export'])) {
            $GLOBALS['_CRM_CORE_DAO_MAPPING']['_export'] = array();
            $fields = &CRM_Core_DAO_Mapping::fields();
            foreach($fields as $name=>$field) {
                if (CRM_Utils_Array::value('export', $field)) {
                    if ($prefix) {
                        $GLOBALS['_CRM_CORE_DAO_MAPPING']['_export']['mapping'] = &$fields[$name];
                    } else {
                        $GLOBALS['_CRM_CORE_DAO_MAPPING']['_export'][$name] = &$fields[$name];
                    }
                }
            }
        }
        return $GLOBALS['_CRM_CORE_DAO_MAPPING']['_export'];
    }
    /**
    * returns an array containing the enum fields of the civicrm_mapping table
    *
    * @return array (reference)  the array of enum fields
    */
     function &getEnums() 
    {
        
        return $GLOBALS['_CRM_CORE_DAO_MAPPING']['enums'];
    }
    /**
    * returns a ts()-translated enum value for display purposes
    *
    * @param string $field  the enum field in question
    * @param string $value  the enum value up for translation
    *
    * @return string  the display value of the enum
    */
     function tsEnum($field, $value) 
    {
        
        if (!$GLOBALS['_CRM_CORE_DAO_MAPPING']['translations']) {
            $GLOBALS['_CRM_CORE_DAO_MAPPING']['translations'] = array(
                'mapping_type'=>array(
                    'Export'=>ts('Export') ,
                    'Import'=>ts('Import') ,
                    'Export Contributions'=>ts('Export Contributions') ,
                    'Import Contributions'=>ts('Import Contributions') ,
                ) ,
            );
        }
        return $GLOBALS['_CRM_CORE_DAO_MAPPING']['translations'][$field][$value];
    }
    /**
    * adds $value['foo_display'] for each $value['foo'] enum from civicrm_mapping
    *
    * @param array $values (reference)  the array up for enhancing
    * @return void
    */
     function addDisplayEnums(&$values) 
    {
        $enumFields = &CRM_Core_DAO_Mapping::getEnums();
        foreach($enumFields as $enum) {
            if (isset($values[$enum])) {
                $values[$enum.'_display'] = CRM_Core_DAO_Mapping::tsEnum($enum, $values[$enum]);
            }
        }
    }
}
?>