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

require_once 'CRM/Contribute/Import/Parser.php';

require_once 'api/crm.php';

/**
 * class to parse contribution csv files
 */
class CRM_Contribute_Import_Parser_Contribution extends CRM_Contribute_Import_Parser {

    protected $_mapperKeys;

    private $_contactIdIndex;
    private $_totalAmountIndex;
    private $_contributionTypeIndex;

    //protected $_mapperLocType;
    //protected $_mapperPhoneType;
    /**
     * Array of succesfully imported contribution id's
     *
     * @array
     */
    protected $_newContributions;

    /**
     * class constructor
     */
    function __construct( &$mapperKeys,$mapperLocType = null, $mapperPhoneType = null) {
        parent::__construct();
        $this->_mapperKeys =& $mapperKeys;
        //$this->_mapperLocType =& $mapperLocType;
        //$this->_mapperPhoneType =& $mapperPhoneType;
    }

    /**
     * the initializer code, called before the processing
     *
     * @return void
     * @access public
     */
    function init( ) {
        require_once 'CRM/Contribute/BAO/Contribution.php';
        $fields =& CRM_Contribute_BAO_Contribution::importableFields( $this->_contactType , false );

        foreach ($fields as $name => $field) {
            $this->addField( $name, $field['title'], $field['type'], $field['headerPattern'], $field['dataPattern']);
        }

        $this->_newContributions = array();

        $this->setActiveFields( $this->_mapperKeys );
        //$this->setActiveFieldLocationTypes( $this->_mapperLocType );
        //$this->setActiveFieldPhoneTypes( $this->_mapperPhoneType );

        // FIXME: we should do this in one place together with Form/MapField.php
        $this->_contactIdIndex        = -1;
        $this->_totalAmountIndex      = -1;
        $this->_contributionTypeIndex = -1;
        
        $index = 0;
        foreach ( $this->_mapperKeys as $key ) {
            switch ($key) {
            case 'contribution_contact_id':
                $this->_contactIdIndex        = $index;
                break;
            case 'total_amount':
                $this->_totalAmountIndex      = $index;
                break;
            case 'contribution_type':
                $this->_contributionTypeIndex = $index;
                break;
            }
            $index++;
        }
    }

    /**
     * handle the values in mapField mode
     *
     * @param array $values the array of values belonging to this line
     *
     * @return boolean
     * @access public
     */
    function mapField( &$values ) {
        return CRM_Contribute_Import_Parser::VALID;
    }


    /**
     * handle the values in preview mode
     *
     * @param array $values the array of values belonging to this line
     *
     * @return boolean      the result of this processing
     * @access public
     */
    function preview( &$values ) {
        return $this->summary($values);
    }

    /**
     * handle the values in summary mode
     *
     * @param array $values the array of values belonging to this line
     *
     * @return boolean      the result of this processing
     * @access public
     */
    function summary( &$values ) {
        $erroneousField = null;
        $response = $this->setActiveFieldValues( $values, $erroneousField );
        /*if ($response != CRM_Contribute_Import_Parser::VALID) {
            array_unshift($values, ts('Invalid field value: %1', array(1 => $this->_activeFields[$erroneousField]->_title)));
            return CRM_Contribute_Import_Parser::ERROR;
        }*/
        $errorRequired = false;
        if ($this->_totalAmountIndex      < 0 or
            $this->_contributionTypeIndex < 0) {
            $errorRequired = true;
        } else {
            $errorRequired = ! CRM_Utils_Array::value($this->_totalAmountIndex, $values) ||
                ! CRM_Utils_Array::value($this->_contributionTypeIndex, $values);
        }
        
        
        if ($errorRequired) {
            array_unshift($values, ts('Missing required fields'));
            return CRM_Contribute_Import_Parser::ERROR;
        }

        $params =& $this->getActiveFieldParams( );
        require_once 'CRM/Import/Parser/Contact.php';
        $errorMessage = null;
        
        //for date-Formats
        $session =& CRM_Core_Session::singleton();
        $dateType = $session->get("dateTypes");
        foreach ($params as $key => $val) {
            if( $val ) {
                switch( $key ) {
                case  'receive_date': 
                    if( CRM_Utils_Date::convertToDefaultDate( $params, $dateType, $key )) {
                        if (! CRM_Utils_Rule::date($params[$key])) {
                            //return _crm_error('Invalid value for field  : Receive Date');
                            CRM_Import_Parser_Contact::addToErrorMsg('Receive Date', $errorMessage);
                        }
                    } else {
                        CRM_Import_Parser_Contact::addToErrorMsg('Receive Date', $errorMessage);
                    }
                    break;
                case  'cancel_date': 
                    if( CRM_Utils_Date::convertToDefaultDate( $params, $dateType, $key )) {
                        if (! CRM_Utils_Rule::date($params[$key])) {
                            //return _crm_error('Invalid value for field  : Cancel Date');
                            CRM_Import_Parser_Contact::addToErrorMsg('Cancel Date', $errorMessage);
                        }
                    } else {
                        CRM_Import_Parser_Contact::addToErrorMsg('Cancel Date', $errorMessage);
                    }
                    break;
                case  'receipt_date': 
                    if( CRM_Utils_Date::convertToDefaultDate( $params, $dateType, $key )) {
                        if (! CRM_Utils_Rule::date($params[$key])) {
                            //return _crm_error('Invalid value for field  : Activity Date');
                            CRM_Import_Parser_Contact::addToErrorMsg('Receipt date', $errorMessage);
                        }
                    } else {
                        CRM_Import_Parser_Contact::addToErrorMsg('Receipt date', $errorMessage);
                    }
                    break;
                case  'thankyou_date': 
                    if( CRM_Utils_Date::convertToDefaultDate( $params, $dateType, $key )) {
                        if (! CRM_Utils_Rule::date($params[$key])) {
                            //return _crm_error('Invalid value for field  : Thankyou Date');
                            CRM_Import_Parser_Contact::addToErrorMsg('Thankyou Date', $errorMessage);
                        }
                    } else {
                        CRM_Import_Parser_Contact::addToErrorMsg('Thankyou Date', $errorMessage);
                    }
                    break;
                    
                }
            }
        }
        //date-Format part ends

        //$params['contact_type'] =  $this->_contactType;
        $params['contact_type'] =  'Contribution';
        
        //checking error in custom data
        CRM_Import_Parser_Contact::isErrorInCustomData($params, $errorMessage);

        if ( $errorMessage ) {
            $tempMsg = "Invalid value for field(s) : $errorMessage";
            array_unshift($values, $tempMsg);
            $errorMessage = null;
            return CRM_Import_Parser::ERROR;
        }

        return CRM_Contribute_Import_Parser::VALID;
    }

    /**
     * handle the values in import mode
     *
     * @param int $onDuplicate the code for what action to take on duplicates
     * @param array $values the array of values belonging to this line
     *
     * @return boolean      the result of this processing
     * @access public
     */
    function import( $onDuplicate, &$values) {
        // first make sure this is a valid line
        $response = $this->summary( $values );
        if ( $response != CRM_Contribute_Import_Parser::VALID ) {
            return $response;
        }
        
        $params =& $this->getActiveFieldParams( );

        //for date-Formats
        $session =& CRM_Core_Session::singleton();
        $dateType = $session->get("dateTypes");
        
        foreach ($params as $key => $val) {
            if( $val ) {
                switch( $key ) {
                case  'receive_date': 
                    CRM_Utils_Date::convertToDefaultDate( $params, $dateType, $key );
                    break;
                case  'cancel_date': 
                    CRM_Utils_Date::convertToDefaultDate( $params, $dateType, $key );
                    break;
                case  'receipt_date': 
                    CRM_Utils_Date::convertToDefaultDate( $params, $dateType, $key );
                    break;
                case  'thankyou_date': 
                    CRM_Utils_Date::convertToDefaultDate( $params, $dateType, $key );
                    break;
                }
            }
        }
        //date-Format part ends

        $formatted = array();
        static $indieFields = null;
        if ($indieFields == null) {
            require_once('CRM/Contribute/DAO/Contribution.php');
            $tempIndieFields =& CRM_Contribute_DAO_Contribution::import();
            $indieFields = $tempIndieFields;
        }
        
        $values = array();
        
        foreach ($params as $key => $field) {
            if ($field == null || $field === '') {
                continue;
            }
            $values[$key] = $field;
        }
        
        $formatError = _crm_format_contrib_params($values, $formatted, true);
        
        if ( $formatError ) {
            array_unshift($values, $formatError->_errors[0]['message']);
            return CRM_Contribute_Import_Parser::ERROR;
       }
        
        if ( $this->_contactIdIndex < 0 ) {
            static $cIndieFields = null;
            if ($cIndieFields == null) {
                require_once 'CRM/Contact/BAO/Contact.php';
                //$cTempIndieFields = CRM_Contact_BAO_Contact::importableFields('Individual', null );
                $cTempIndieFields = CRM_Contact_BAO_Contact::importableFields( $this->_contactType );
                $cIndieFields = $cTempIndieFields;
            }

            foreach ($params as $key => $field) {
                if ($field == null || $field === '') {
                    continue;
                }
                if (is_array($field)) {
                    foreach ($field as $value) {
                        $break = false;
                        if ( is_array($value) ) {
                            foreach ($value as $name => $testForEmpty) {
                                if ($name !== 'phone_type' &&
                                    ($testForEmpty === '' || $testForEmpty == null)) {
                                    $break = true;
                                    break;
                                }
                            }
                        } else {
                            $break = true;
                        }
                        if (! $break) {    
                            _crm_add_formatted_param($value, $contactFormatted);
                            
                        }
                    }
                    continue;
                }
                
                $value = array($key => $field);
                if (array_key_exists($key, $cIndieFields)) {
                    //$value['contact_type'] = 'Individual';
                    $value['contact_type'] = $this->_contactType;
                }
                _crm_add_formatted_param($value, $contactFormatted);
            }
            //$contactFormatted['contact_type'] = 'Individual';
            $contactFormatted['contact_type'] = $this->_contactType;
            $error = _crm_duplicate_formatted_contact($contactFormatted);
            $matchedIDs = explode(',',$error->_errors[0]['params'][0]);
            if ( self::isDuplicate($error) ) {
                if (count( $matchedIDs) >1) {
                    array_unshift($values,"Multiple matching contact records detected for this row. The contribution was not imported");
                    return CRM_Contribute_Import_Parser::ERROR;
                } else {
                    $cid = $matchedIDs[0];
                    $formatted['contact_id'] = $cid;
                    $newContribution = crm_create_contribution_formatted( $formatted, $onDuplicate );
                    if ( is_a( $newContribution, CRM_Core_Error ) ) {
                        array_unshift($values, $newContribution->_errors[0]['message']);
                        return CRM_Contribute_Import_Parser::ERROR;
                    }
                    
                    $this->_newContributions[] = $newContribution->id;
                    return CRM_Contribute_Import_Parser::VALID;
                }
                
            } else {
                if ($this->_contactType == 'Individual') {
                    require_once 'CRM/Core/DAO/DupeMatch.php';
                    $dao = & new CRM_Core_DAO_DupeMatch();;
                    $dao->find(true);
                    $fieldsArray = explode('AND',$dao->rule);
                } elseif ($this->_contactType == 'Household') {
                    $fieldsArray = array('household_name', 'email');
                } elseif ($this->_contactType == 'Organization') {
                    $fieldsArray = array('organization_name', 'email');
                }
                foreach ( $fieldsArray as $value) {
                    if(array_key_exists(trim($value),$params)) {
                        $paramValue = $params[trim($value)];
                        if (is_array($paramValue)) {
                            $disp .= $params[trim($value)][0][trim($value)]." ";  
                        } else {
                            $disp .= $params[trim($value)]." ";
                        }
                    }
                } 

                array_unshift($values,"No matching Contact found for (".$disp.")");
                return CRM_Contribute_Import_Parser::ERROR;
            }
          
        } else {
            if ( $values['external_identifier'] ) {
                $checkCid = new CRM_Contact_DAO_Contact();
                $checkCid->external_identifier = $values['external_identifier'];
                $checkCid->find(true);
                if ($checkCid->id != $formatted['contact_id']) {
                    array_unshift($values, "Mismatch of External identifier :" . $values['external_identifier'] . " and Contact Id:" . $formatted['contact_id']);
                    return CRM_Contribute_Import_Parser::ERROR;
                }
            }
            $newContribution = crm_create_contribution_formatted( $formatted, $onDuplicate );
            if ( is_a( $newContribution, CRM_Core_Error ) ) {
                array_unshift($values, $newContribution->_errors[0]['message']);
                return CRM_Contribute_Import_Parser::ERROR;
            }
            
            $this->_newContributions[] = $newContribution->id;
            return CRM_Contribute_Import_Parser::VALID;
        }
    }
   
    /**
     * Get the array of succesfully imported contribution id's
     *
     * @return array
     * @access public
     */
    function &getImportedContributions() {
        return $this->_newContributions;
    }
   
    /**
     * the initializer code, called before the processing
     *
     * @return void
     * @access public
     */
    function fini( ) {
    }

    /**
     *  function to check if an error is actually a duplicate contact error
     *  
     *  @param Object $error Avalid Error object
     *  
     *  @return ture if error is duplicate contact error 
     *  
     *  @access public 
     */

    function isDuplicate($error) {
        
        if( is_a( $error, CRM_Core_Error ) ) {
            $code = $error->_errors[0]['code'];
            if($code == CRM_Core_Error::DUPLICATE_CONTACT ) {
                return true ;
            }
        }
        return false;
    }


}

?>
