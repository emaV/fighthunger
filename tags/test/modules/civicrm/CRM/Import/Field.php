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


require_once 'CRM/Utils/Type.php';

class CRM_Import_Field {
  
    /**#@+
     * @access protected
     * @var string
     */

    /**
     * name of the field
     */
    public $_name;

    /**
     * title of the field to be used in display
     */
    public $_title;

    /**
     * type of field
     * @var enum
     */
    public $_type;

    /**
     * is this field required
     * @var boolean
     */
    public $_required;

    /**
     * data to be carried for use by a derived class
     * @var object
     */
    public $_payload;

    /**
     * regexp to match the CSV header of this column/field
     * @var string
     */
     public $_headerPattern;

    /**
     * regexp to match the pattern of data from various column/fields
     * @var string
     */
     public $_dataPattern;

    /**
     * location type
     * @var int
     */
    public $_hasLocationType;

    /**
     * does this field have a phone type
     * @var string
     */
    public $_phoneType;

    /**
     * value of this field
     * @var object
     */
    public $_value;

    /**
     * does this field have a relationship info
     * @var string
     */
    public $_related;

    /**
     * does this field have a relationship Contact Type
     * @var string
     */
    public $_relatedContactType;

    /**
     * does this field have a relationship Contact Details
     * @var string
     */
    public $_relatedContactDetails;

    /**
     * does this field have a related Contact info of Location Type
     * @var int
     */
    public $_relatedContactLocType;

    /**
     * does this field have a related Contact info of Phone Type
     * @var string
     */
    public $_relatedContactPhoneType;



    function __construct( $name, $title, $type = CRM_Utils_Type::T_INT, $headerPattern = '//', $dataPattern = '//', $hasLocationType = null, $phoneType = null, $related=null, $relatedContactType=null, $relatedContactDetails=null, $relatedContactLocType=null, $relatedContactPhoneType=null) {
        $this->_name      = $name;
        $this->_title     = $title;
        $this->_type      = $type;
        $this->_headerPattern = $headerPattern;
        $this->_dataPattern = $dataPattern;
        $this->_hasLocationType = $hasLocationType;
        $this->_phoneType = $phoneType;
        $this->_related = $related;
        $this->_relatedContactType = $relatedContactType;
        $this->_relatedContactDetails = $relatedContactDetails;
        $this->_relatedContactLocType = $relatedContactLocType;    
        $this->_relatedContactPhoneType = $relatedContactPhoneType;    
    
        $this->_value     = null;
    }

    function resetValue( ) {
        $this->_value     = null;
    }

    /**
     * the value is in string format. convert the value to the type of this field
     * and set the field value with the appropriate type
     */
    function setValue( $value ) {
        $this->_value = $value;
    }

    function validate( ) {
        //  echo $this->_value."===========<br>";
        $message = '';

        if ( $this->_value === null ) {
            return true;
        }

//     Commented due to bug CRM-150, internationalization/wew.    
//         if ( $this->_name == 'phone' ) {
//            return CRM_Utils_Rule::phone( $this->_value );
//         }
        
        if ( $this->_name == 'email' ) {
            return CRM_Utils_Rule::email( $this->_value );
        }
    }

}

?>
