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
class CRM_Core_DAO_GeoCoord extends CRM_Core_DAO
{
    /**
     * static instance to hold the table name
     *
     * @var string
     * @static
     */
    static $_tableName = 'civicrm_geo_coord';
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
     * Geo Coord ID
     *
     * @var int unsigned
     */
    public $id;
    /**
     * Projected or unprojected coordinates - projected coordinates (e.g. UTM) may be treated as cartesian by some modules.
     *
     * @var enum('LatLong', 'Projected')
     */
    public $coord_type;
    /**
     * If the coord_type is LATLONG, indicate the unit of angular measure: Degree|Grad|Radian. If the coord_type is Projected, indicate unit of distance measure: Foot|Meter.
     *
     * @var enum('Degree', 'Grad', 'Radian', 'Foot', 'Meter')
     */
    public $coord_units;
    /**
     * Coordinate sys description in Open GIS Consortium WKT (well known text) format - see http://www.opengeospatial.org/docs/01-009.pdf this is provided for the convenience of the user or third party modules.
     *
     * @var text
     */
    public $coord_ogc_wkt_string;
    /**
     * class constructor
     *
     * @access public
     * @return civicrm_geo_coord
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
                'coord_type' => array(
                    'name' => 'coord_type',
                    'type' => CRM_Utils_Type::T_ENUM,
                    'title' => ts('Coord Type') ,
                ) ,
                'coord_units' => array(
                    'name' => 'coord_units',
                    'type' => CRM_Utils_Type::T_ENUM,
                    'title' => ts('Coord Units') ,
                ) ,
                'coord_ogc_wkt_string' => array(
                    'name' => 'coord_ogc_wkt_string',
                    'type' => CRM_Utils_Type::T_TEXT,
                    'title' => ts('Coord Ogc Wkt String') ,
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
                        self::$_import['geo_coord'] = &$fields[$name];
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
                        self::$_export['geo_coord'] = &$fields[$name];
                    } else {
                        self::$_export[$name] = &$fields[$name];
                    }
                }
            }
        }
        return self::$_export;
    }
    /**
     * returns an array containing the enum fields of the civicrm_geo_coord table
     *
     * @return array (reference)  the array of enum fields
     */
    static function &getEnums() 
    {
        static $enums = array(
            'coord_type',
            'coord_units',
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
                'coord_type' => array(
                    'LatLong' => ts('LatLong') ,
                    'Projected' => ts('Projected') ,
                ) ,
                'coord_units' => array(
                    'Degree' => ts('Degree') ,
                    'Grad' => ts('Grad') ,
                    'Radian' => ts('Radian') ,
                    'Foot' => ts('Foot') ,
                    'Meter' => ts('Meter') ,
                ) ,
            );
        }
        return $translations[$field][$value];
    }
    /**
     * adds $value['foo_display'] for each $value['foo'] enum from civicrm_geo_coord
     *
     * @param array $values (reference)  the array up for enhancing
     * @return void
     */
    static function addDisplayEnums(&$values) 
    {
        $enumFields = &CRM_Core_DAO_GeoCoord::getEnums();
        foreach($enumFields as $enum) {
            if (isset($values[$enum])) {
                $values[$enum.'_display'] = CRM_Core_DAO_GeoCoord::tsEnum($enum, $values[$enum]);
            }
        }
    }
}
?>