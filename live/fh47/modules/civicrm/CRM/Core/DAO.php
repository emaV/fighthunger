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
 * Our base DAO class. All DAO classes should inherit from this class.
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2007
 * $Id$
 *
 */

require_once 'PEAR.php';
require_once 'DB/DataObject.php';

require_once 'CRM/Utils/Date.php';
require_once 'CRM/Core/I18n.php';
require_once 'CRM/Core/PseudoConstant.php';

class CRM_Core_DAO extends DB_DataObject {

    /**
     * a null object so we can pass it as reference if / when needed
     */
    static $_nullObject = null;
    static $_nullArray  = array( );

    static $_transactionCount    = 0;
    static $_transactionRollback = false;

    const
        NOT_NULL        =   1,
        IS_NULL         =   2,

        DB_DAO_NOTNULL  = 128;

    /**
     * the factory class for this application
     * @var object
     */
    static $_factory = null;

    /**
     * We use this object to encapsulate transactions
     *
     * @var object
     * @static
     */
    static private $_singleton = null;

    /**
     * Class constructor
     *
     * @return object
     * @access public
     */
    function __construct() {
        $this->initialize( );
        $this->__table = $this->getTableName();
    }

    /**
     * empty definition for virtual function
     */
	function getTableName( ) {
        return null;
    }

    /**
     * initialize the DAO object
     *
     * @param string $dsn   the database connection string
     * @param int    $debug the debug level for DB_DataObject
     *
     * @return void
     * @access private
     */
    function init( $dsn, $debug = 0 ) {
        $options =& PEAR::getStaticProperty('DB_DataObject', 'options');
        $options['database'] = $dsn;
        if ( $debug ) {
            self::DebugLevel($debug);
        }
    }
	
    /**
     * reset the DAO object. DAO is kinda crappy in that there is an unwritten
     * rule of one query per DAO. We attempt to get around this crappy restricrion
     * by resetting some of DAO's internal fields. Use this with caution
     *
     * @return void
     * @access public
     *
     */
    function reset() {
        
        foreach( array_keys( $this->table() ) as $field ) {
            unset($this->$field);
        }

        /**
         * reset the various DB_DAO structures manually
         */
        $this->_query = array( );
        $this->whereAdd ( );
        $this->selectAdd( );
        $this->joinAdd  ( );
    }
	
    /**
     * Static function to set the factory instance for this class.
     *
     * @param object $factory  the factory application object
     *
     * @return void
     * @access public
     */
    function setFactory(&$factory) {
        self::$_factory =& $factory;
    }
	
    /**
     * Factory method to instantiate a new object from a table name.
     *
     * @return void 
     * @access public
     */
    function factory($table) {
        if ( ! isset( self::$_factory ) ) {
            return parent::factory($table);
        }
		
        return self::$_factory->create($table);
    }
	
    /**
     * Initialization for all DAO objects. Since we access DB_DO programatically
     * we need to set the links manually.
     *
     * @return void
     * @access protected
     */
    function initialize() {
        $links = $this->links();
        if ( empty( $links ) ) {
            return;
        }

        $this->_connect();

        if ( !isset($GLOBALS['_DB_DATAOBJECT']['LINKS'][$this->_database]) ) {
            $GLOBALS['_DB_DATAOBJECT']['LINKS'][$this->_database] = array();
        }
	    
        if ( ! array_key_exists( $this->__table, $GLOBALS['_DB_DATAOBJECT']['LINKS'][$this->_database] ) ) {
            $GLOBALS['_DB_DATAOBJECT']['LINKS'][$this->_database][$this->__table] = $links;
        }
    }
	
    /**
     * Defines the default key as 'id'.
     *
     * @access protected
     * @return array
     */
    function keys() {
        static $keys;
        if ( !isset ($keys) ) {
            $keys = array('id');
        }
        return $keys;
    }
    
    /**
     * Tells DB_DataObject which keys use autoincrement.
     * 'id' is autoincrementing by default.
     * 
     * @access protected
     * @return array
     */
    function sequenceKey() {
        static $sequenceKeys;
        if ( !isset ($sequenceKeys) ) {
            $sequenceKeys = array('id', true);
        }
        return $sequenceKeys;
    }

    /**
     * returns list of FK relationships
     *
     * @access public
     * @return array
     */
    function links( ) {
        return null;
    }


    /**
     * returns all the column names of this table
     *
     * @access public
     * @return array
     */
    function &fields( ) {
        return null;
    }

    function table() {
        $fields =& $this->fields();

        $table = array();
        if ($fields) {
            foreach ( $fields as $name => $value ) {
                $table[$value['name']] = $value['type'];
                if ( CRM_Utils_Array::value( 'required', $value ) ) {
                    $table[$value['name']] += self::DB_DAO_NOTNULL;
                }
            }
        }

        // set the links
        $this->links();

        return $table;
    }

    function save( ) {
        if ($this->id) {
            $this->update();
        } else {
            $this->insert();
        }
        $this->free( );
        return $this;
    }

    function log( $created = false ) {
        static $cid = null;

        if ( ! $this->getLog( ) ) {
            return;
        }

        if ( ! $cid ) {
            $session =& CRM_Core_Session::singleton( );
            $cid = $session->get( 'userID' );
        }
        
        // return is we dont have handle to FK
        if ( ! $cid ) {
            return;
        }

        require_once 'CRM/Core/DAO/Log.php';
        $dao =& new CRM_Core_DAO_Log( );
        $dao->entity_table  = $this->getTableName( );
        $dao->entity_id     = $this->id;
        $dao->modified_id   = $cid;
        $dao->modified_date = date( "YmdHis" );
        $dao->insert( );
   }

    /**
     * Given an associative array of name/value pairs, extract all the values
     * that belong to this object and initialize the object with said values
     *
     * @param array $params (reference ) associative array of name/value pairs
     *
     * @return boolean      did we copy all null values into the object
     * @access public
     */
    function copyValues( &$params ) {
        $fields =& $this->fields( );
        $allNull = true;
        foreach ( $fields as $name => $value ) {
            $dbName = $value['name'];
            if ( array_key_exists( $dbName, $params ) ) {
                // if there is no value then make the variable NULL
                if ( $params[$dbName] === '' ) {
                    $this->$dbName = 'null';
                } else {
                    $this->$dbName = $params[$dbName];
                    $allNull = false;
                }
            }
        }
        return $allNull;
    }

    /**
     * Store all the values from this object in an associative array
     * this is a destructive store, calling function is responsible
     * for keeping sanity of id's. Note that this function is rewritten
     * to sidestep a wierd PHP4 bug
     *
     * @param object $object the object that we are extracting data from
     * @param array  $values (reference ) associative array of name/value pairs
     *
     * @return void
     * @access public
     */
    function storeValues( &$object, &$values ) {
        $fields =& $object->fields( );
        foreach ( $fields as $name => $value ) {
            $dbName = $value['name'];
            if ( isset( $object->$dbName ) ) {
                $values[$dbName] = $object->$dbName;
                if ( $name != $dbName ) {
                   $values[$name] = $object->$dbName;
                }
            }
        }
    }

    /**
     * create an attribute for this specific field. We only do this for strings and text
     *
     * @param array $field the field under task
     *
     * @return array|null the attributes for the object
     * @access public
     * @static
     */
    static function makeAttribute( $field ) {
        if ( $field ) {
            if ( CRM_Utils_Array::value('type',$field) == CRM_Utils_Type::T_STRING ) {
                $maxLength  = CRM_Utils_Array::value( 'maxlength', $field );
                $size       = CRM_Utils_Array::value( 'size'     , $field );
                if ( $maxLength || $size ) {
                    $attributes = array( );
                    if ( $maxLength ) {
                        $attributes['maxlength'] = $maxLength;
                    }
                    if ( $size ) {
                        $attributes['size'] = $size;
                    }
                    return $attributes;
                }
            } else if ( CRM_Utils_Array::value('type',$field) == CRM_Utils_Type::T_TEXT ) {
                $rows = CRM_Utils_Array::value( 'rows', $field );
                if ( ! isset( $rows ) ) {
                    $rows = 2;
                }
                $cols = CRM_Utils_Array::value( 'cols', $field );
                if ( ! isset( $cols ) ) {
                    $cols = 80;
                }

                $attributes = array( );
                $attributes['rows'] = $rows;
                $attributes['cols'] = $cols;
                return $attributes;
            } else if ( CRM_Utils_Array::value('type',$field) == CRM_Utils_Type::T_INT || CRM_Utils_Array::value('type',$field) == CRM_Utils_Type::T_FLOAT || CRM_Utils_Array::value('type',$field) == CRM_Utils_Type::T_MONEY ) {
                $attributes['size']      = 4;
                $attributes['maxlength'] = 8; 
                return $attributes;
            }
        }
        return null;
    }

    /**
     * Get the size and maxLength attributes for this text field
     * (or for all text fields) in the DAO object.
     *
     * @param string $class     name of DAO class
     * @param string $fieldName field that i'm interested in or null if 
     *                          you want the attributes for all DAO text fields
     *
     * @return array assoc array of name => attribute pairs
     * @access public
     * @static
     */
    function getAttribute( $class, $fieldName = null) {
        require_once(str_replace('_', DIRECTORY_SEPARATOR, $class) . '.php');
        eval('$fields =& ' . $class . '::fields( );');
        if ( $fieldName != null ) {
            $field = CRM_Utils_Array::value( $fieldName, $fields );
            return self::makeAttribute( $field );
        } else {
            $attributes = array( );
            foreach ($fields as $name => $field) {
                $attribute = self::makeAttribute( $field );
                if ( $attribute ) {
                    $attributes[$name] = $attribute;
                }
            }
            if ( !empty($attributes)) {
                return $attributes;
            }
        }
        return null;
    }

    /**
     * Function to begin/commit/rollback a transaction
     *
     * @param string $type an enum which is either BEGIN|COMMIT|ROLLBACK
     *                     or null if you just want transaction status
     * 
     * @return boolean true if success| false if rollback or transaction will be rolled back
     * @access public
     */
    static function transaction( $type ) {
        if ( empty( $type ) ) {
            // return status if no type sent
            return ! self::$_transactionRollback;
        }

        if ( self::$_singleton == null ) {
            self::$_singleton =& new CRM_Core_DAO( );
        }

        $result = true;
        switch ( $type ) {
        case 'BEGIN':
            if ( self::$_transactionCount == 0 ) {
                self::$_singleton->query( 'BEGIN' );
            }
            self::$_transactionCount++;
            break;

        case 'ROLLBACK':
            self::$_transactionCount--;
            if ( self::$_transactionCount == 0 ) {
                self::$_singleton->query( 'ROLLBACK' );
                self::$_transactionRollback = false;
            } else {
                self::$_transactionRollback = true;
            }
            $result = false;
            break;

        case 'COMMIT':
            self::$_transactionCount--;
            if ( self::$_transactionRollback ) {
                $result = false;
            }
            if ( self::$_transactionCount == 0 ) {
                if ( self::$_transactionRollback ) {
                    self::$_singleton->query( 'ROLLBACK' );
                } else {
                    self::$_singleton->query( 'COMMIT' );
                }
                self::$_transactionRollback = false;
            }
            break;

        default:
            CRM_Core_Error::fatal( ts( 'Unhandled transaction type: %1',
                                       array( 1 => $type ) ) );
            break;
        }
        return $result;
    }

    /**
     * Check if there is a record with the same name in the db
     *
     * @param string $value     the value of the field we are checking
     * @param string $daoName   the dao object name
     * @param string $daoID     the id of the object being updated. u can change your name
     *                          as long as there is no conflict
     * @param string $fieldName the name of the field in the DAO
     *
     * @return boolean     true if object exists
     * @access public
     * @static
     */
    static function objectExists( $value, $daoName, $daoID, $fieldName = 'name' ) {
        require_once(str_replace('_', DIRECTORY_SEPARATOR, $daoName) . ".php");
        eval( '$object =& new ' . $daoName . '( );' );
        $object->$fieldName = $value;

        $config  =& CRM_Core_Config::singleton( );

        if ( $object->find( true ) ) {
            return ( $daoID && $object->id == $daoID ) ? true : false;
        } else {
            return true;
        }
    }

    /**
     * Given a DAO name and its id, get the value of the field requested
     *
     * @param string $daoName   the name of the DAO
     * @param int    $id        the id of the relevant object 
     * @param string $fieldName the name of the field whose value is needed
     *
     * @return string|null      the value of the field
     * @static
     * @access public
     */
    static function getFieldValue( $daoName, $id, $fieldName = 'name', $idName = 'id' ) {
        if ( empty( $id ) ) {
            // adding this year since developers forget to check for an id
            // and hence we get the first value in the db
            CRM_Core_Error::fatal( 'Please file an issue with the backtrace' );
            return null;
        }

        require_once(str_replace('_', DIRECTORY_SEPARATOR, $daoName) . ".php");
        eval( '$object   =& new ' . $daoName . '( );' );
        $object->$idName =  $id;
        $object->selectAdd( );
        $object->selectAdd( 'id, ' . $fieldName );
        $result = null;
        if ( $object->find( true ) ) {
            $result = $object->$fieldName;
        }
        $object->free( );
        return $result;
     }

    /**
     * Given a DAO name and its id, set the value of the field requested
     *
     * @param string $daoName   the name of the DAO
     * @param int    $id        the id of the relevant object
     * @param string $fieldName the name of the field whose value is needed
     * @param string $value     the value of the field
     *
     * @return boolean          true if we found and updated the object, else false
     * @static
     * @access public
     */
    static function setFieldValue( $daoName, $id, $fieldName, $value ) {
        require_once(str_replace('_', DIRECTORY_SEPARATOR, $daoName) . ".php");
        eval( '$object =& new ' . $daoName . '( );' );
        $object->selectAdd( );
        $object->selectAdd( 'id, ' . $fieldName );
        $object->id    = $id;
        $result = false;
        if ( $object->find( true ) ) {
            $object->$fieldName = $value;
            if ( $object->save( ) ) {
                $result = true;
            }
        }
        $object->free( );
        return $result;
    }


    /**
     * Get sort string
     *
     * @param array|object $sort either array or CRM_Utils_Sort
     * @param string $default - default sort value
     *
     * @return string - sortString
     * @access public
     * @static
     */
    static function getSortString($sort, $default = null)
    {
        // check if sort is of type CRM_Utils_Sort
        if ( is_a( $sort, 'CRM_Utils_Sort' ) ) {
            return $sort->orderBy();
        }

        // is it an array specified as $field => $sortDirection ?
        if ( $sort ) {
            foreach ( $sort as $k => $v ) {
                $sortString .= "$k $v,";
            }
            return rtrim( $sortString, ',' );
        }
        return $default;
    }

    /**
     * Takes a bunch of params that are needed to match certain criteria and
     * retrieves the relevant objects. Typically the valid params are only
     * contact_id. We'll tweak this function to be more full featured over a period
     * of time. This is the inverse function of create. It also stores all the retrieved
     * values in the default array
     *
     * @param string $daoName  name of the dao object
     * @param array  $params   (reference ) an assoc array of name/value pairs
     * @param array  $defaults (reference ) an assoc array to hold the flattened values
     *
     * @return object an object of type referenced by daoName
     * @access public
     * @static
     */
    static function commonRetrieve($daoName, &$params, &$defaults)
    {
        require_once(str_replace('_', DIRECTORY_SEPARATOR, $daoName) . ".php");
        eval( '$object =& new ' . $daoName . '( );' );
        $object->copyValues($params);
        if ( $object->find( true ) ) {
            self::storeValues( $object, $defaults);
            return $object;
        }
        return null;
    }

    /**
     * Delete the object records that are associated with this contact
     *
     * @param string $daoName  name of the dao object
     * @param  int  $contactId id of the contact to delete
     *
     * @return void
     * @access public
     * @static
     */
    static function deleteEntityContact( $daoName, $contactId ) {
        require_once(str_replace('_', DIRECTORY_SEPARATOR, $daoName) . ".php");
        eval( '$object =& new ' . $daoName . '( );' );

        $object->entity_table = 'civicrm_contact';
        $object->entity_id   = $contactId;
        $object->delete( );
    }
 
    /**
     * execute a query
     *
     * @param string $query query to be executed
     *
     * @return Object CRM_Core_DAO object that holds the results of the query
     * @static
     * @access public
     */
    static function &executeQuery( $query, &$params, $abort = true ) {
        $dao =& new CRM_Core_DAO( );
        $queryStr = self::composeQuery( $query, $params, $abort, $dao );
        $dao->query( $queryStr );
        return $dao;
    }

    /**
     * execute a query and get the singleton result
     *
     * @param string $query query to be executed 
     * 
     * @return string the result of the query
     * @static 
     * @access public 
     */ 
    static function singleValueQuery( $query, &$params, $abort = true ) {
        $dao =& new CRM_Core_DAO( ); 
        $queryStr = self::composeQuery( $query, $params, $abort, $dao );
        $dao->query( $queryStr ); 
        
        $result = $dao->getDatabaseResult();
        if ( $result ) {
            $row = $result->fetchRow();
            if ( $row ) {
                return $row[0];
            }
        }
        return null;
    }

    static function composeQuery( $query, &$params, $abort, &$dao ) {
        require_once 'CRM/Utils/Type.php';

        $tr = array( );
        foreach ( $params as $key => $item ) {
            if ( is_numeric( $key ) ) {
                if ( CRM_Utils_Type::validate( $item[0], $item[1] ) !== null ) {
                    $item[0] = $dao->escape( $item[0] );
                    if ( $item[1] == 'String' ) {
                        $item[0] = "'{$item[0]}'";
                    }
                    $tr['%' . $key] = $item[0];
                } else if ( $abort ) {
                    CRM_Core_Error::fatal( "{$item[0]} is not of type {$item[1]}" );
                }
            }
        }
        return strtr( $query, $tr );
    }

    static function freeResult( $ids = null ) {
        global $_DB_DATAOBJECT;

        /***
        $q = array( );
        foreach ( array_keys( $_DB_DATAOBJECT['RESULTS'] ) as $id ) {
            $q[] = $_DB_DATAOBJECT['RESULTS'][$id]->query;
        }
        CRM_Core_Error::debug( 'k', $q );
        return;
        ***/

        if ( ! $ids ) {
            $ids = array_keys( $_DB_DATAOBJECT['RESULTS'] );
        }

        foreach ( $ids as $id ) {
            if ( isset( $_DB_DATAOBJECT['RESULTS'][$id] ) ) {
                if ( is_resource( $_DB_DATAOBJECT['RESULTS'][$id]->result ) ) {
                    mysql_free_result( $_DB_DATAOBJECT['RESULTS'][$id]->result );
                }
                unset( $_DB_DATAOBJECT['RESULTS'][$id] );
            }
            
            if ( isset( $_DB_DATAOBJECT['RESULTFIELDS'][$id] ) ) {
                unset( $_DB_DATAOBJECT['RESULTFIELDS'][$id] );
            }
        }
    }

    /**
     * This function is to make a shallow copy of an object
     * and all the fields in the object
     *
     * @param int $id the dao id to copy
     *
     * @return (reference ) the newly created copy of the object
     * @access public
     */
    static function &copy( $daoName, $id, $fieldsToPrefix = null ) {
        require_once(str_replace('_', DIRECTORY_SEPARATOR, $daoName) . ".php");
        eval( '$object   =& new ' . $daoName . '( );' );
        $object->id =  $id;
        if ( ! $object->find( true ) ) {
            CRM_Core_Error::fatal( ts( "Could not retrieve object name: %1, id: %2",
                                       array( 1 => $daoName, 2 => $id ) ) );
        }

        eval( '$newObject   =& new ' . $daoName . '( );' );

        $fields =& $object->fields( );
        if ( ! is_array( $fieldsToPrefix ) ) {
            $fieldsToPrefix = array( );
        }

        foreach ( $fields as $name => $value ) {
            if ( $name == 'id' ) {
                // copy everything but the id!
                continue;
            }
            $dbName = $value['name'];
            if ( isset( $fieldsToPrefix[$dbName] ) ) {
                $newObject->$dbName = $fieldsToPrefix[$dbName] . $object->$dbName;
            } else {
                $newObject->$dbName = $object->$dbName;
            }
            if( substr($name , -5) == '_date') {
                $newObject->$dbName = CRM_Utils_Date::isoToMysql($object->$dbName);
            }
        }
        $newObject->save( );
        return $newObject;
    }
    
    /**
     * This function is to make a shallow copy of an object
     * and all the fields in the object
     * 
     * @param string $daoName                 name of the dao
     * @param array  $criteria                array of all the fields & values 
     *                                        on which basis to copy
     * @param array  $newData                 array of all the fields & values 
     *                                        to be copied besides the other fields
     * @param string $fieldsToPrefix          fields that you want to prefix
     * @param string $blockCopyOfDependencies fields that you want to block from
     *                                        getting copied
     * 
     *
     * @return (reference )                   the newly created copy of the object
     * @access public
     */
    static function &copyGeneric( $daoName, $criteria , $newData = null, $fieldsToPrefix = null, $blockCopyOfDependencies = null ) 
        { 
        require_once(str_replace('_', DIRECTORY_SEPARATOR, $daoName) . ".php");
        eval( '$object   =& new ' . $daoName . '( );' );
        if ( ! $newData ) {
            $object->id =  $criteria['id'];     
        } else {
            foreach( $criteria as $key => $value ) {
                $object->$key = $value;
            }
        } 

        $object->find( );
        while ( $object->fetch( ) ) {

            // all the objects except with $blockCopyOfDependencies set
            // be copied - addresses #CRM-1962

            if ( $blockCopyOfDependencies && $object->$blockCopyOfDependencies ) {
                break;
            }
            
            eval( '$newObject   =& new ' . $daoName . '( );' );
            
            $fields =& $object->fields( );
            if ( ! is_array( $fieldsToPrefix ) ) {
                $fieldsToPrefix = array( );
            }
            
            foreach ( $fields as $name => $value ) {
                if ( $name == 'id' ) {
                    // copy everything but the id!
                    continue;
                }
                
                
                $dbName = $value['name'];
                if ( isset( $fieldsToPrefix[$dbName] ) ) {
                    $newObject->$dbName = $fieldsToPrefix[$dbName] . $object->$dbName;
                } else {
                    $newObject->$dbName = $object->$dbName;
                }
                
                if( substr($name , -5) == '_date') {
                    $newObject->$dbName = CRM_Utils_Date::isoToMysql($object->$dbName);
                }
                
                if ( $newData ) {
                    foreach( $newData as $k => $v ) {
                        $newObject->$k = $v;
                    }
                } 
            }
            $newObject->save( );        
        }
        return $newObject;
    }

    /**
     * Given the component id, compute the contact id
     * since its used for things like send email
     */
    public function &getContactIDsFromComponent( &$componentIDs, $tableName ) {
        $contactIDs = array( );

        if ( empty( $componentIDs ) ) {
            return $contactIDs;
        }

        $IDs = implode( ',', $componentIDs );
        $query = "
SELECT contact_id
  FROM $tableName
 WHERE id IN ( $IDs )
";

        $dao =& CRM_Core_DAO::executeQuery( $query, CRM_Core_DAO::$_nullArray );
        while ( $dao->fetch( ) ) {
            $contactIDs[] = $dao->contact_id;
        }
        return $contactIDs;
    }

    
}

?>
