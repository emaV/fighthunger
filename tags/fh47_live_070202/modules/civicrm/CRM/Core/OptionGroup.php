<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 1.5                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2006                                  |
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

class CRM_Core_OptionGroup {
    static $_values = array( );

    static function &values( $name, $flip = false, $grouping = false ) {
        self::$_values[$name] = array( );
        $domainID = CRM_Core_Config::domainID( );
        $query = "
SELECT  v.label as label ,v.value as value, v.grouping as grouping
FROM   civicrm_option_value v,
       civicrm_option_group g
WHERE  v.option_group_id = g.id
  AND  g.domain_id       = $domainID
  AND  g.name            = %1
  AND  v.is_active       = 1 
  AND  g.is_active       = 1 
  ORDER BY v.weight; 
";
        $p = array( 1 => array( $name, 'String' ) );
        $dao =& CRM_Core_DAO::executeQuery( $query, $p );
           
        while ( $dao->fetch( ) ) {
            if ( $flip ) {
                if ( $grouping ) {
                    self::$_values[$name][$dao->value] = $dao->grouping;
                } else {
                    self::$_values[$name][$dao->label] = $dao->value;
                }
            } else {
                if ( $grouping ) {
                    self::$_values[$name][$dao->label] = $dao->grouping;
                } else {
                    self::$_values[$name][$dao->value] = $dao->label;
                }
            }
        }
        return self::$_values[$name];
    }
    
/**
 * Function to lookup titles OR ids for a set of option_value populated fields. The retrieved value
 * is assigned a new fieldname by id or id's by title  
 * (each within a specificied option_group)
 *
 * @param  array   $params   Reference array of values submitted by the form. Based on
 *                           $flip, creates new elements in $params for each field in
 *                           the $names array.
 *                           If $flip = false, adds     root field name     => title
 *                           If $flip = true, adds      actual field name   => id                                                                     
 * 
 * @param  array   $names    Reference array of fieldnames we want transformed.
 *                           Array key = 'postName' (field name submitted by form in $params).
 *                           Array value = array('newName' => $newName, 'groupName' => $groupName).
 *                           
 *
 * @param  boolean $flip
 *
 * @return void     
 * 
 * @access public
 * @static
 */

    static function lookupValues( &$params, &$names, $flip = false ) {
        $domainID = CRM_Core_Config::domainID( );
        foreach ($names as $postName => $value) {
            // See if $params field is in $names array (i.e. is a value that we need to lookup)
            if ( CRM_Utils_Array::value( $postName, $params ) ) {
                // params[$postName] may be a Ctrl+A separated value list
                if ( strpos( $params[$postName], CRM_Core_BAO_CustomOption::VALUE_SEPERATOR ) ) {
                    // eliminate the ^A frm the beginning and end if present
                    if ( substr( $params[$postName], 0, 1 ) == CRM_Core_BAO_CustomOption::VALUE_SEPERATOR ) {
                        $params[$postName] = substr( $params[$postName], 1, -1 );
                    }
                }
                $postValues = explode(CRM_Core_BAO_CustomOption::VALUE_SEPERATOR, $params[$postName]);
                $newValue = array( );
                foreach ($postValues as $postValue) {
                    if ( ! $postValue ) {
                        continue;
                    }

                    if ( $flip ) {
                        $p = array( 1 => array( $postValue, 'String' ) );
                        $lookupBy = 'v.label= %1';
                        $select   = "v.value";
                    } else {
                        $p = array( 1 => array( $postValue, 'Integer' ) );
                        $lookupBy = 'v.value = %1';
                        $select   = "v.label";
                    }
                    
                    $p[2] = array( $value['groupName'], 'String' );
                    $query = "
                        SELECT $select
                        FROM   civicrm_option_value v,
                               civicrm_option_group g
                        WHERE  v.option_group_id = g.id
                        AND    g.domain_id       = $domainID
                        AND    g.name            = %2
                        AND    $lookupBy";

                    $newValue[] = CRM_Core_DAO::singleValueQuery( $query, $p );
                }
                $params[$value['newName']] = implode(', ', $newValue);
            }
        }
    }

    static function getLabel( $groupName, $value ) {
        $domainID = CRM_Core_Config::domainID( );
        $query = "
SELECT  v.label as label ,v.value as value
FROM   civicrm_option_value v, 
       civicrm_option_group g 
WHERE  v.option_group_id = g.id 
  AND  g.domain_id       = $domainID 
  AND  g.name            = %1 
  AND  v.is_active       = 1  
  AND  g.is_active       = 1  
  AND  v.value           = %2
";

        $p = array( 1 => array( $groupName , 'String' ),
                    2 => array( $value, 'Integer' ) );
        $dao =& CRM_Core_DAO::executeQuery( $query, $p );
        if ( $dao->fetch( ) ) {
            return $dao->label;
        }
        return null;
    }

    static function getValue( $groupName, $label ) {
        $domainID = CRM_Core_Config::domainID( );
        $query = "
SELECT  v.label as label ,v.value as value
FROM   civicrm_option_value v, 
       civicrm_option_group g 
WHERE  v.option_group_id = g.id 
  AND  g.domain_id       = $domainID 
  AND  g.name            = %1 
  AND  v.is_active       = 1  
  AND  g.is_active       = 1  
  AND  v.label           = %2
";

        $p = array( 1 => array( $groupName , 'String' ),
                    2 => array( $label     , 'String' ) );
        $dao =& CRM_Core_DAO::executeQuery( $query, $p );
        if ( $dao->fetch( ) ) {
            return $dao->value;
        }
        return null;
    }

}

?>