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

require_once 'CRM/Import/Parser.php';

require_once 'api/crm.php';

/**
 * class to parse contact csv files
 */
class CRM_Import_Parser_Contact extends CRM_Import_Parser 
{
    protected $_mapperKeys;
    protected $_mapperLocType;
    protected $_mapperPhoneType;
    protected $_mapperRelated;
    protected $_mapperRelatedContactType;
    protected $_mapperRelatedContactDetails;
    protected $_mapperRelatedContactEmailType;

    protected $_emailIndex;
    protected $_firstNameIndex;
    protected $_lastNameIndex;

    protected $_householdNameIndex;
    protected $_organizationNameIndex;

    protected $_allEmails;

    protected $_phoneIndex;
    protected $_updateWithId;
    protected $_retCode;

    protected $_externalIdentifierIndex;
    protected $_allExternalIdentifiers;

    /**
     * Array of succesfully imported contact id's
     *
     * @array
     */
    protected $_newContacts;

    /**
     * Array of succesfully imported related contact id's
     *
     * @array
     */
    protected $_newRelatedContacts;

    /**
     * class constructor
     */
    function __construct( &$mapperKeys, $mapperLocType = null, 
                          $mapperPhoneType = null, $mapperRelated = null, $mapperRelatedContactType=null,
                          $mapperRelatedContactDetails = null, $mapperRelatedContactLocType = null, 
                          $mapperRelatedContactPhoneType = null) 
    {
        parent::__construct();
        $this->_mapperKeys =& $mapperKeys;
        $this->_mapperLocType =& $mapperLocType;
        $this->_mapperPhoneType =& $mapperPhoneType;
        $this->_mapperRelated =& $mapperRelated;
        $this->_mapperRelatedContactType =& $mapperRelatedContactType;
        $this->_mapperRelatedContactDetails =& $mapperRelatedContactDetails;
        $this->_mapperRelatedContactLocType =& $mapperRelatedContactLocType;
        $this->_mapperRelatedContactPhoneType =& $mapperRelatedContactPhoneType;

    }

    /**
     * the initializer code, called before the processing
     *
     * @return void
     * @access public
     */
    function init( ) 
    {
        require_once 'CRM/Contact/BAO/Contact.php';
        $fields =& CRM_Contact_BAO_Contact::importableFields( $this->_contactType );

        //Relationship importables
        $relations = CRM_Contact_BAO_Relationship::getContactRelationshipType( null, null, null, $this->_contactType );
        
        foreach ($relations as $key => $var) {
            list( $type ) = explode( '_', $key );
            $relationshipType[$key]['title'] = $var;
            $relationshipType[$key]['headerPattern'] = '/' . preg_quote( $var, '/' ) . '/';
            $relationshipType[$key]['import'] = true;
            $relationshipType[$key]['relationship_type_id'] = $type;
            $relationshipType[$key]['related'] = true;
        }

        if ( !empty($relationshipType) ) {
            $fields = array_merge($fields, array( 'related' => array('title' => '- related contact info -')) + $relationshipType);
        }

        foreach ($fields as $name => $field) {
            $this->addField( $name, $field['title'], $field['type'], $field['headerPattern'], $field['dataPattern'], $field['hasLocationType'] );
        }

        $this->_newContacts = array();

        $this->setActiveFields( $this->_mapperKeys );
        $this->setActiveFieldLocationTypes( $this->_mapperLocType );
        $this->setActiveFieldPhoneTypes( $this->_mapperPhoneType );

        //related info
        $this->setActiveFieldRelated( $this->_mapperRelated );
        $this->setActiveFieldRelatedContactType( $this->_mapperRelatedContactType );
        $this->setActiveFieldRelatedContactDetails( $this->_mapperRelatedContactDetails );
        $this->setActiveFieldRelatedContactLocType( $this->_mapperRelatedContactLocType );
        $this->setActiveFieldRelatedContactPhoneType( $this->_mapperRelatedContactPhoneType );
        
        $this->_phoneIndex = -1;
        $this->_emailIndex = -1;
        $this->_firstNameIndex = -1;
        $this->_lastNameIndex = -1;
        $this->_householdNameIndex = -1;
        $this->_organizationNameIndex = -1;
        $this->_externalIdentifierIndex = -1;
        
        $index = 0 ;
        foreach ( $this->_mapperKeys as $key ) {
            if ( $key == 'email' ) {
                $this->_emailIndex = $index;
                $this->_allEmails  = array( );
            }
            if ( $key == 'phone' ) {
                $this->_phoneIndex = $index;
            }
            if ( $key == 'first_name' ) {
                $this->_firstNameIndex = $index;
            }
            if ( $key == 'last_name' ) { 
                $this->_lastNameIndex = $index;
            }
            if ( $key == 'household_name' ) { 
                $this->_householdNameIndex = $index;
            }
            if ( $key == 'organization_name' ) { 
                $this->_organizationNameIndex = $index;
            }
            
            if ( $key == 'external_identifier' ) {
                $this->_externalIdentifierIndex = $index;
                $this->_allExternalIdentifiers  = array( );
            }
            $index++;
        }

        $this->_updateWithId = false;
        if (in_array('id',$this->_mapperKeys)) {
            $this->_updateWithId = true;
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
    function mapField( &$values ) 
    {
        return CRM_Import_Parser::VALID;
    }


    /**
     * handle the values in preview mode
     *
     * @param array $values the array of values belonging to this line
     *
     * @return boolean      the result of this processing
     * @access public
     */
    function preview( &$values ) 
    {
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
    function summary( &$values ) 
    {
        $response = $this->setActiveFieldValues( $values );

        $errorRequired = false;
        switch ($this->_contactType) { 
        case 'Individual' :
            if ( $this->_firstNameIndex < 0 || $this->_lastNameIndex < 0) {
                $errorRequired = true;
            } else {
                $errorRequired = ! CRM_Utils_Array::value($this->_firstNameIndex, $values) &&
                    ! CRM_Utils_Array::value($this->_lastNameIndex, $values);
            }
            break;
        case 'Household' :
            if ( $this->_householdNameIndex < 0 ) {
                $errorRequired = true;
            } else {
                $errorRequired = ! CRM_Utils_Array::value($this->_householdNameIndex, $values);
            }
            break;
        case 'Organization' :
            if ( $this->_organizationNameIndex < 0 ) {
                $errorRequired = true;
            } else {
                $errorRequired = ! CRM_Utils_Array::value($this->_organizationNameIndex, $values);
            }
            break;
        }

        if ( $this->_emailIndex >= 0 ) {
            /* If we don't have the required fields, bail */
            if ($this->_contactType == 'Individual' &&! $this->_updateWithId ) {
                if ($errorRequired && ! CRM_Utils_Array::value($this->_emailIndex, $values)) {
                    array_unshift($values, ts('Missing required fields'));
                    return CRM_Import_Parser::ERROR;
                }
            }
            
            $email = CRM_Utils_Array::value( $this->_emailIndex, $values );
            if ( $email ) {
                /* If the email address isn't valid, bail */
                if (! CRM_Utils_Rule::email($email)) {
                    array_unshift($values, ts('Invalid Email address'));
                    return CRM_Import_Parser::ERROR;
                }

                /* otherwise, count it and move on */
                $this->_allEmails[$email] = $this->_lineCount;
            }
        } else if ($errorRequired && ! $this->_updateWithId) {
            array_unshift($values, ts('Missing required fields'));
            return CRM_Import_Parser::ERROR;
        }
        
        //check for duplicate external Identifier
        $externalID = CRM_Utils_Array::value( $this->_externalIdentifierIndex, $values );
        if ( $externalID ) {
            /* If it's a dupe,external Identifier  */
            if ( $externalDupe = CRM_Utils_Array::value( $externalID, 
                                                         $this->_allExternalIdentifiers ) ) {
                array_unshift($values, ts('External Identifier conflicts with record %1', 
                                          array(1 => $externalDupe)));
                return CRM_Import_Parser::ERROR;
            }
            //otherwise, count it and move on
            $this->_allExternalIdentifiers[$externalID] = $this->_lineCount;
        }

        //Checking error in custom data
        $params =& $this->getActiveFieldParams( );
        $params['contact_type'] =  $this->_contactType;
        //date-format part ends

        $errorMessage = null;
        
        //checking error in custom data
     
        $this->isErrorInCustomData($params, $errorMessage);

        //checking error in core data
        $this->isErrorInCoreData($params, $errorMessage);
        if ( $errorMessage ) {
            $tempMsg = "Invalid value for field(s) : $errorMessage";
            array_unshift($values, $tempMsg);
            $errorMessage = null;
            return CRM_Import_Parser::ERROR;
        }
        
        return CRM_Import_Parser::VALID;
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
    function import( $onDuplicate, &$values, $doGeocodeAddress = false ) 
    {
        require_once 'api/Contact.php';

        // first make sure this is a valid line
        //$this->_updateWithId = false;
        $response = $this->summary( $values );
        if ( $response != CRM_Import_Parser::VALID ) {
            return $response;
        }
        $params =& $this->getActiveFieldParams( );
        $formatted = array('contact_type' => $this->_contactType);
        
        //for date-Formats
        $session =& CRM_Core_Session::singleton();
        $dateType = $session->get("dateType");
        $customFields = CRM_Core_BAO_CustomField::getFields( $params['contact_type'] );
        foreach ( $params  as $key => $val ) {
            if ($customFieldID = CRM_Core_BAO_CustomField::getKeyID($key)) {
                if ($customFields[$customFieldID][2] == 'Date') {
                    CRM_Utils_Date::convertToDefaultDate( $params, $dateType, $key);
                }
            }
            if ( $key == 'birth_date' ) {
                if( $val ) {
                    CRM_Utils_Date::convertToDefaultDate( $params, $dateType, $key );
                }
            }
            if ( $key == 'deceased_date' ) {
                if( $val ) {
                    CRM_Utils_Date::convertToDefaultDate( $params, $dateType, $key );
                }
            }
        }
        //date-Format part ends
        
        static $indieFields = null;
        if ($indieFields == null) {
            require_once(str_replace('_', DIRECTORY_SEPARATOR, "CRM_Contact_DAO_" . $this->_contactType) . ".php");
            eval('$tempIndieFields =& CRM_Contact_DAO_'.$this->_contactType.'::import();'); //modified for PHP4 issue
            $indieFields = $tempIndieFields;
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
                        _crm_add_formatted_param($value, $formatted);
                    }
                }
                continue;
            }
            
            $value = array($key => $field);
            if (array_key_exists($key, $indieFields)) {
                $value['contact_type'] = $this->_contactType;
            }

            _crm_add_formatted_param($value, $formatted);
        }

        //check if external identifier exists in database
        if ( isset( $params['external_identifier'] ) ) {
            require_once "CRM/Contact/BAO/Contact.php";
            if ( CRM_Contact_BAO_Contact::checkExternalIdentifierExists( $params['external_identifier'] )  ) {
                array_unshift($values, ts('External Identifier already exists in database.'));
                return CRM_Import_Parser::ERROR;
            }
        }

        $relationship = false;
        // Support Match and Update Via Contact ID
        if ( $this->_updateWithId ) {
            $error = _crm_duplicate_formatted_contact($formatted);
            if ( self::isDuplicate($error) ) {
                $matchedIDs= explode(',',$error->_errors[0]['params'][0]);
                if ( count( $matchedIDs) >= 1 ) {
                        $updateflag = true;
                        foreach ($matchedIDs  as $contactId) {
                            if ($params['id'] == $contactId) {
                                $paramsValues = array('contact_id'=>$contactId);
                                $contactExists =& CRM_Contact_BAO_Contact::check_contact_exists($params['id']);
                                if ($formatted['contact_type'] == $contactExists->contact_type) {
                                    $newContact = crm_update_contact_formatted($contactId, $formatted, true);
                                    $updateflag = false; 
                                    $this->_retCode = CRM_Import_Parser::VALID;
                                } else {
                                    $message = "Mismatched contact Types :";
                                    array_unshift($values, $message);
                                    $updateflag = false;
                                    $this->_retCode = CRM_Import_Parser::NO_MATCH;
                                }
                            } 
                        }
                        if ( $updateflag ) {
                            $message = "Mismatched contact IDs OR Mismatched contact Types :" ;
                            array_unshift($values, $message);
                            $this->_retCode = CRM_Import_Parser::NO_MATCH;
                        }
                }
                 
            } else {
                $paramsValues = array('contact_id'=>$params['id']);
                $contact =& CRM_Contact_BAO_Contact::check_contact_exists($params['id']);
                if (is_a( $contact, 'CRM_Contact_DAO_Contact' )) {
                if ($formatted['contact_type'] == $contact->contact_type) {
                        $newContact = crm_update_contact_formatted($contact->id, $formatted, true);
                        $this->_retCode = CRM_Import_Parser::VALID;
                } else {
                        $message = "Mismatched contact Types :";
                        array_unshift($values, $message);
                        $this->_retCode = CRM_Import_Parser::NO_MATCH;
                    }
                } else {
                    $message ="No contact found for this contact ID:".$params['id'] ;
                    array_unshift($values, $message);
                    $this->_retCode = CRM_Import_Parser::NO_MATCH;  
                }
            }
            if (is_a( $newContact, 'CRM_Contact_BAO_Contact' )) {
                $relationship = true;
            } else if (is_a( $error, 'CRM_Core_Error' )) {
                $newContact = $error;
                $relationship = true;
            }
        } else {
            $newContact = crm_create_contact_formatted( $formatted, $onDuplicate, $doGeocodeAddress );
            $relationship = true;
        }
       
        // $newContact is a crm_core_error object, due to some wierd behavior
        // of php this variable gets overwritten when we check for duplicate
        // contact while creating related contact which also returns
        // crm_core_error object.
        if ( $newContact || is_a( $newContact,CRM_Contact_BAO_Contact ) ||
             self::isDuplicate( $newContact ) ) {
            $newContact = clone( $newContact );
        } 
        
        if ( $relationship ) {
            $primaryContactId = null;
            if ( self::isDuplicate($newContact) ) {
                if ( CRM_Utils_Rule::integer( $newContact->_errors[0]['params'][0] ) ) {
                    
                    $primaryContactId = $newContact->_errors[0]['params'][0];
                }
            } else {
                $primaryContactId = $newContact->id;
            }
            
            if ( ( self::isDuplicate($newContact)  || is_a( $newContact,CRM_Contact_BAO_Contact ) ) 
                 && $primaryContactId ) {

                //relationship contact insert
                foreach ($params as $key => $field) {
                    list($id, $first, $second) = explode('_', $key);
                    if ( !($first == 'a' && $second == 'b') && !($first == 'b' && $second == 'a') ) {
                        continue;
                    }
                    
                    $relationType = new CRM_Contact_DAO_RelationshipType();
                    $relationType->id = $id;
                    $relationType->find(true);
                    $name_a_b = $relationType->name_a_b;
                    
                    if ( $params[$key]['contact_type'] ) {
                        $formatting = array('contact_type' => $params[$key]['contact_type']);
                    } else {
                        $fld = array_keys($params[$key]);
                        foreach ( CRM_Core_SelectValues::contactType() as $cType => $val) {
                            if ( $cType ) {
                                $contactFields =& CRM_Contact_BAO_Contact::importableFields($cType);
                                
                                if ( array_key_exists( $fld[0], $contactFields) ) {
                                    $formatting['contact_type'  ] = $cType;
                                    $params[$key]['contact_type'] = $cType;
                                    $field['contact_type']        = $cType;
                                    break;
                                }
                            }
                        }
                    }

                    $contactFields = null;
                    if ($contactFields == null) {
                        require_once(str_replace('_', DIRECTORY_SEPARATOR, "CRM_Contact_DAO_" . $params[$key]['contact_type']) . ".php");
                        eval('$contactFields =& CRM_Contact_DAO_'.$params[$key]['contact_type'].'::import();');
                    }

                    foreach ($field as $k => $v) {
                        if ($v == null || $v === '') {
                            continue;
                        }
                        
                        if (is_array($v)) {
                            foreach ($v as $value) {
                                $break = false;
                                foreach ($value as $testForEmpty) {
                                    if ($testForEmpty === '' || $testForEmpty == null) {
                                        $break = true;
                                        break;
                                    }                        
                                }
                                if (! $break) {
                                    _crm_add_formatted_param($value, $formatting);
                                }
                            }
                            continue;
                        }
                       
                        $value = array($k => $v);
                        if (array_key_exists($k, $contactFields)) {
                            $value['contact_type'] = $params[$key]['contact_type'];
                        }
                        _crm_add_formatted_param($value, $formatting);
                    }

                    //fix for CRM-1315
                    if ( $params[$key]['id']) {
                        $contactId = array('contact_id' => $params[$key]['id']);
                        $relatedNewContact = crm_get_contact($contactId);
                    } else {
                        $relatedNewContact = crm_create_contact_formatted( $formatting, $onDuplicate );
                    }

                    $relatedNewContact = clone($relatedNewContact);                      
                   
                    if ( self::isDuplicate($relatedNewContact) ) {
                        foreach ($relatedNewContact->_errors[0]['params'] as $cid) {
                            $relContactId = $cid;
                        }
                        
                    } else {
                        $relContactId = $relatedNewContact->id;
                        $this->_newRelatedContacts[] = $relContactId;
                    }
                    
                    if ( self::isDuplicate($relatedNewContact) || is_a( $relatedNewContact ,CRM_Contact_BAO_Contact )) {
                        // now create the relationship record
                        $relationParams = array();
                        $relationParams = array('relationship_type_id' => $key, 
                                                'contact_check' => array( $relContactId => 1)
                                                );
                        
                        //fix for CRM-1993.Checks for duplicate related contacts
                        $matchedIDs= explode(',',$relatedNewContact->_errors[0]['params'][0]);
                        if (count($matchedIDs) == 1) {
                            // we only handle related contact success, we ignore failures for now
                            // at some point wold be nice to have related counts as separate
                            $relationIds = array('contact' => $primaryContactId);
                            
                            CRM_Contact_BAO_Relationship::create( $relationParams, $relationIds );
                            
                            CRM_Contact_BAO_Relationship::relatedMemberships( $primaryContactId, 
                                                                              $relationParams,
                                                                              $relationIds );
                            
                            //check if the two contacts are related and of type individual
                            if ( $params[$key]['contact_type'] == 'Individual' && $this->_contactType  == 'Individual') {
                                if( $name_a_b == 'Spouse of' || $name_a_b == 'Child of' || $name_a_b == 'Sibling of') {
                                    $householdName = "The ".$formatting['last_name']." household";
                                    $householdFormatting = array( 'contact_type' => 'Household', 'household_name' => $householdName );
                                    $householdContact = crm_create_contact_formatted( $householdFormatting, $onDuplicate );
                                    $householdContact = clone($householdContact);
                                    if ( self::isDuplicate($householdContact) ) {
                                        foreach ($householdContact->_errors[0]['params'] as $cid) {
                                            $householdId = $cid;
                                        }
                                    } else {
                                        $householdId = $householdContact->id;
                                        $this->_newRelatedContacts[] = $householdId;
                                    }
                                    
                                    //Household contact is created 
                                    //for two related individual contacts waiting confirmation whether 
                                    //to add it in a group
                                    //$this->_newRelatedContacts[] = $householdId;
                                
                                    $relationParams = array();
                                    // adding household relationship
                                    $relType = '7_'.$second.'_'.$first;
                                    
                                    $relationParams = array('relationship_type_id' => $relType,
                                                            'contact_check'        => array( $relContactId => 1,
                                                                                             $primaryContactId => 1)
                                                            );
                                    $relationIds = array('contact' => $householdId);
                                    CRM_Contact_BAO_Relationship::create( $relationParams, $relationIds );
                                }
                            }
                        }
                    }
                }
            }
        }
        if( $this->_updateWithId ) {
            return $this->_retCode;
        }

        //dupe checking      
        if ( is_a( $newContact, CRM_Core_Error ) ) {
            $code = $newContact->_errors[0]['code'];
            if ($code == CRM_Core_Error::DUPLICATE_CONTACT) {
                $urls = array( );
                // need to fix at some stage and decide if the error will return an 
                // array or string, crude hack for now
                if ( is_array( $newContact->_errors[0]['params'] ) ) {
                    $cids = $newContact->_errors[0]['params'];
                } else {
                    $cids = explode( ',', $newContact->_errors[0]['params'] );
                }
                foreach ($cids as $cid) {
                    $urls[] = CRM_Utils_System::url('civicrm/contact/view',
                                                    'reset=1&cid=' . $cid, true);
                }
                
                $url_string = implode("\n", $urls);
                array_unshift($values, $url_string); 
                
                // If we duplicate more than one record, skip no matter what 
                if (count($cids) > 1) {
                    array_unshift($values, ts('Record duplicates multiple contacts'));
                    return CRM_Import_Parser::ERROR;
                }
           
                // Params only had one id, so shift it out 
                $contactId = array_shift($newContact->_errors[0]['params']);
                
                if ($onDuplicate == CRM_Import_Parser::DUPLICATE_REPLACE) {
                    $newContact = crm_replace_contact_formatted($contactId, $formatted);
                } else if ($onDuplicate == CRM_Import_Parser::DUPLICATE_UPDATE) {
                    $newContact = crm_update_contact_formatted($contactId, $formatted, true);
                } else if ($onDuplicate == CRM_Import_Parser::DUPLICATE_FILL) {
                    $newContact = crm_update_contact_formatted($contactId, $formatted, false);
                } // else skip does nothing and just returns an error code.
            
                if ($newContact && ! is_a($newContact, CRM_Core_Error)) {
                    $this->_newContacts[] = $newContact->id;
                }
                //CRM-262 No Duplicate Checking  
                if ($onDuplicate == CRM_Import_Parser::DUPLICATE_SKIP) {
                    return CRM_Import_Parser::DUPLICATE; 
                }
                return CRM_Import_Parser::VALID;
            } else { 
                // Not a dupe, so we had an error 
                array_unshift($values, $newContact->_errors[0]['message']);
                return CRM_Import_Parser::ERROR;
            }
        }

        if ( $newContact && ! is_a( $newContact, 'CRM_Core_Error' ) ) {
            $this->_newContacts[] = $newContact->id;
        }
        return CRM_Import_Parser::VALID;
        
    }

    /**
     * Get the array of succesfully imported contact id's
     *
     * @return array
     * @access public
     */
    function &getImportedContacts() 
    {
        return $this->_newContacts;
    }
   
    /**
     * Get the array of succesfully imported related contact id's
     *
     * @return array
     * @access public
     */
    function &getRelatedImportedContacts() 
    {
        return $this->_newRelatedContacts;
    }

    /**
     * the initializer code, called before the processing
     *
     * @return void
     * @access public
     */
    function fini( ) 
    {
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

    function isDuplicate($error) 
    {
        if ( is_a( $error, 'CRM_Core_Error' ) ) {
            $code = $error->_errors[0]['code'];
            if ($code == CRM_Core_Error::DUPLICATE_CONTACT ) {
                return true ;
            }
        }
        return false;
    }

    /**
     *  function to check if an error in custom data
     *  
     *  @param String   $errorMessage   A string containing all the error-fields.
     *  
     *  @access public 
     */

    function isErrorInCustomData($params, &$errorMessage) 
    {
        $session =& CRM_Core_Session::singleton();
        $dateType = $session->get("dateType");
        $customFields = CRM_Core_BAO_CustomField::getFields( $params['contact_type'] );
        foreach ($params as $key => $value) {
            if ($customFieldID = CRM_Core_BAO_CustomField::getKeyID($key)) {
                /* check if it's a valid custom field id */
                if ( !array_key_exists($customFieldID, $customFields)) {
                    self::addToErrorMsg('field ID', $errorMessage);
                }
                /* validate the data against the CF type */
     
                if ( $value ) {
                    if ($customFields[$customFieldID][2] == 'Date') {
                        if( CRM_Utils_Date::convertToDefaultDate( $params, $dateType, $key )) {
                            $value = $params[$key];
                        } else {
                            self::addToErrorMsg($customFields[$customFieldID][0], $errorMessage);
                        }
                    }
                    // need not check for label filed import
                    $htmlType = array('CheckBox','Multi-Select','Select','Radio');
                    if ( ! in_array( $customFields[$customFieldID][3], $htmlType ) || $customFields[$customFieldID][2] =='Boolean' ) {

                        $valid = CRM_Core_BAO_CustomValue::typecheck(
                                                                     $customFields[$customFieldID][2], $value);
                        if (! $valid) {
                            self::addToErrorMsg($customFields[$customFieldID][0], $errorMessage);
                        }
                    }
                    
                    // check for values for custom fields for checkboxes and multiselect
                    if ( $customFields[$customFieldID][3] == 'CheckBox' || $customFields[$customFieldID][3] =='Multi-Select' ) {
                        $value = str_replace("|",",",$value);
                        $mulValues = explode( ',' , $value );
                        $customOption = CRM_Core_BAO_CustomOption::getCustomOption( $customFieldID, true );
                        foreach( $mulValues as $v1 ) {
                            $flag = false; 
                            foreach( $customOption as $v2 ) {
                                if (( strtolower(trim($v2['label'])) == strtolower(trim($v1)))||( strtolower(trim($v2['value'])) == strtolower(trim($v1)))) {
                                    $flag = true; 
                                }
                            }
                            if (! $flag ) {
                                self::addToErrorMsg($customFields[$customFieldID][0], $errorMessage);
                            }
                        }
                    } else if ( $customFields[$customFieldID][3] == 'Select' || 
                               ( $customFields[$customFieldID][3] =='Radio' && $customFields[$customFieldID][2] !='Boolean' ) ) {
                        $customOption = CRM_Core_BAO_CustomOption::getCustomOption( $customFieldID, true );
                        $flag = false;
                        foreach( $customOption as $v2 ) {
                            if (( strtolower(trim($v2['label'])) == strtolower(trim($value)) )||( strtolower(trim($v2['value'])) == strtolower(trim($value)) )) {
                                $flag = true; 
                            }
                        }
                        if (! $flag ) {
                            self::addToErrorMsg($customFields[$customFieldID][0], $errorMessage);
                        }
                    }
                }
            } else if (is_array($params[$key]) && $params[$key]["contact_type"]) {
                self::isErrorInCustomData( $params[$key] ,$errorMessage );
            }
        }
    }
    
    /**
     * function to check if an error in Core( non-custom fields ) field
     *
     * @param String   $errorMessage   A string containing all the error-fields.
     *
     * @access public
     */
    function isErrorInCoreData($params, &$errorMessage) 
    {
        foreach ($params as $key => $value) {
            if ( $value ) {
                $session =& CRM_Core_Session::singleton();
                $dateType = $session->get("dateType");
                
                switch( $key ) {
                case 'birth_date': 
                    if( CRM_Utils_Date::convertToDefaultDate( $params, $dateType, $key  )) {
                        if (! CRM_Utils_Rule::date($params[$key])) {
                            self::addToErrorMsg('Birth Date', $errorMessage);
                        } 
                    } else {
                        self::addToErrorMsg('Birth-Date', $errorMessage); 
                    }
                    
                    break;
                case 'deceased_date': 
                    if( CRM_Utils_Date::convertToDefaultDate( $params, $dateType, $key  )) {
                        if (! CRM_Utils_Rule::date($value)) {
                            self::addToErrorMsg('Deceased Date', $errorMessage);
                        }
                    } else {
                        self::addToErrorMsg('Deceased Date', $errorMessage); 
                    }
                    break;
                case 'gender':    
                    if (!self::in_value($value,CRM_Core_PseudoConstant::gender())) {
                        self::addToErrorMsg('Gender', $errorMessage);
                    }
                    break;
                    
                case 'preferred_communication_method':    
                    $preffComm = array( );
                    $preffComm = explode(',' , $value);
                    foreach ($preffComm as $v) {
                        if (!self::in_value($v, CRM_Core_PseudoConstant::pcm())) {
                            self::addToErrorMsg('Preferred Communication Method', $errorMessage);
                        }
                    }
                    break;
                    
                case 'preferred_mail_format':
                    if(!array_key_exists(strtolower($value),array_change_key_case(CRM_Core_SelectValues::pmf(), CASE_LOWER))) {
                        self::addToErrorMsg('Preferred Mail Format', $errorMessage);
                    }
                    break;
                case 'individual_prefix':
                    if (! self::in_value($value,CRM_Core_PseudoConstant::individualPrefix())) {
                        self::addToErrorMsg('Individual Prefix', $errorMessage);
                    }
                    break;
                case 'individual_suffix':
                    if (!self::in_value($value,CRM_Core_PseudoConstant::individualSuffix())) {
                        self::addToErrorMsg('Individual Suffix', $errorMessage);
                    }   
                    break;
                case 'state_province':
                    if ( ! empty( $value )) {
                        foreach($value as $stateValue ) {
                            if ( $stateValue['state_province']) {
                                if( self::in_value($stateValue['state_province'],CRM_Core_PseudoConstant::stateProvinceAbbreviation()) 
                                    || self::in_value($stateValue['state_province'], CRM_Core_PseudoConstant::stateProvince())) {
                                    continue;
                                } else {
                                    self::addToErrorMsg('State Province', $errorMessage);
                                }
                            }
                        }
                    }
                    
                    break;
                case 'country':
                    if (!empty( $value ) ) {
                        foreach($value as $stateValue ) {
                            if ( $stateValue['country'] ) {
                                if(self::in_value($stateValue['country'], CRM_Core_PseudoConstant::countryIsoCode())
                                   || self::in_value($stateValue['country'], CRM_Core_PseudoConstant::country())) {
                                    continue;
                                } else {
                                    self::addToErrorMsg('Country', $errorMessage);
                                }
                            }
                        }
                    }
                    break;
                case 'geo_code_1' :   
                    if (!empty( $value ) ) {
                        foreach($value as $codeValue ) {
                            if ( $codeValue['geo_code_1'] ) {
                                if ( CRM_Utils_Rule::numeric($codeValue['geo_code_1'])) {
                                    continue;
                                } else {
                                    self::addToErrorMsg('geo_code_1', $errorMessage);
                                }
                            }
                        }
                    }
                    break;
                case 'geo_code_2' :
                    if (!empty( $value ) ) {
                        foreach($value as $codeValue ) {
                            if ( $codeValue['geo_code_2'] ) {
                                if ( CRM_Utils_Rule::numeric($codeValue['geo_code_2'])) {
                                    continue;
                                } else {
                                    self::addToErrorMsg('geo_code_2', $errorMessage);
                                }
                            }
                        }
                    }
                default : 
                    if (is_array($params[$key]) && $params[$key]["contact_type"]) {
                        //check for any relationship data ,FIX ME
                        self::isErrorInCoreData($params[$key],$errorMessage);
                    }
                }
            }
        }
    }

    /**
     * function to ckeck a value present or not in a array
     *
     * @return ture if value present in array or retun false 
     * 
     * @access public
     */
    function in_value($value , $valueArray) 
    {
        foreach( $valueArray  as $key => $v ) {
            //fix for CRM-1514
            if ( strtolower( trim($v, "." ) ) == strtolower( trim( $value ) ) ) {
                return true;
            }
        }
        return false;
    }

    /**
     * function to build error-message containing error-fields  
     *
     * @param String   $errorName      A string containing error-field name.
     * @param String   $errorMessage   A string containing all the error-fields, where the new errorName is concatenated. 
     * 
     * @static
     * @access public
     */
    static function addToErrorMsg($errorName, &$errorMessage) 
    {
        if ($errorMessage) {
            $errorMessage .= "; $errorName";
        } else {
            $errorMessage = $errorName;
        }
    }
    

}

?>
