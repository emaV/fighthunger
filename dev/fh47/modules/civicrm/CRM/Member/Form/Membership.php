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

require_once 'CRM/Member/Form.php';
require_once 'CRM/Member/PseudoConstant.php';

/**
 * This class generates form components for Membership Type
 * 
 */
class CRM_Member_Form_Membership extends CRM_Member_Form
{

    public function preProcess()  
    {  
        // check for edit permission
        if ( ! CRM_Core_Permission::check( 'edit memberships' ) ) {
            CRM_Core_Error::fatal( ts( 'You do not have permission to access this page' ) );
        }

        // action
        $this->_action    = CRM_Utils_Request::retrieve( 'action', 'String',
                                                         $this, false, 'add' );
        $this->_id        = CRM_Utils_Request::retrieve( 'id', 'Positive',
                                                         $this );
        $this->_contactID = CRM_Utils_Request::retrieve( 'cid', 'Positive',
                                                         $this );
        $this->_memType   = CRM_Utils_Request::retrieve( 'subType', 'Positive',
                                                         $this );

        if ( ! $this->_memType ) {
            if ( $this->_id ) {
                $this->_memType = CRM_Core_DAO::getFieldValue("CRM_Member_DAO_Membership",$this->_id,"membership_type_id");
            } else {
                $this->_memType = "Membership";
            }
        }     
    
        //check whether membership status present or not
        if ( $this->_action & CRM_Core_Action::ADD ) {
            CRM_Member_BAO_Membership::statusAvilability($this->_contactID);
        }

        //get the group Tree
        $this->_groupTree =& CRM_Core_BAO_CustomGroup::getTree( 'Membership', $this->_id, false,$this->_memType);
 
        parent::preProcess( );
    }

    /**
     * This function sets the default values for the form. MobileProvider that in edit/view mode
     * the default values are retrieved from the database
     * 
     * @access public
     * @return None
     */
    public function setDefaultValues( ) {
        $defaults = array( );
        $defaults =& parent::setDefaultValues( );
        
        //setting default join date
        if ($this->_action == CRM_Core_Action::ADD) {
            $joinDate = getDate();
            $defaults['join_date']['M'] = $joinDate['mon'];
            $defaults['join_date']['d'] = $joinDate['mday'];
            $defaults['join_date']['Y'] = $joinDate['year'];
        }
        if( isset($this->_groupTree) ) {
            CRM_Core_BAO_CustomGroup::setDefaults( $this->_groupTree, $defaults, false, false );
        }

        if (is_numeric($this->_memType)) {
            $defaults["membership_type_id"] = array();
            $defaults["membership_type_id"][0] =  
                CRM_Core_DAO::getFieldValue( 'CRM_Member_DAO_MembershipType', 
                                             $this->_memType, 
                                             'member_of_contact_id', 
                                             'id' );
            $defaults["membership_type_id"][1] = $this->_memType;
        } else {
            $defaults["membership_type_id"]    =  $this->_memType;
        }

        $this->assign( "member_is_test", CRM_Utils_Array::value('member_is_test',$defaults) );
        return $defaults;
    }

    /**
     * Function to build the form
     *
     * @return None
     * @access public
     */
    public function buildQuickForm( ) 
    {
        parent::buildQuickForm( );
        if ($this->_action & CRM_Core_Action::DELETE ) { 
            return;
        }

        $selOrgMemType[0][0] = $selMemTypeOrg[0] = ts('-- select --');

        $dao =& new CRM_Member_DAO_MembershipType();
        $dao->find();
        while ($dao->fetch()) {
            if ($dao->is_active) {
                if ( !CRM_Utils_Array::value($dao->member_of_contact_id,$selMemTypeOrg) ) {
                    $selMemTypeOrg[$dao->member_of_contact_id] = 
                        CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_Contact', 
                                                     $dao->member_of_contact_id, 
                                                     'display_name', 
                                                     'id' );
                    $selOrgMemType[$dao->member_of_contact_id][0] = ts('-- select --');
                }                
                if ( !CRM_Utils_Array::value($dao->id,$selOrgMemType[$dao->member_of_contact_id]) ) {
                    $selOrgMemType[$dao->member_of_contact_id][$dao->id] = $dao->name;
                }
            }
        }
        
        // show organization by default, if only one organization in
        // the list 
        if ( count($selMemTypeOrg) == 2 ) {
            unset($selMemTypeOrg[0], $selOrgMemType[0][0]);
        }

        $sel =& $this->addElement('hierselect', 
                                  'membership_type_id', 
                                  ts('Membership Organization and Type'), 
                                  array('onChange' => "if (this.value) reload(true); else return false") );  
        $sel->setOptions(array($selMemTypeOrg,  $selOrgMemType));

        $urlParams = "reset=1&cid={$this->_contactID}&context=membership";
        if ( $this->_id ) {
            $urlParams .= "&action=update&id={$this->_id}";
        } else {
            $urlParams .= "&action=add";
        }

        $url = CRM_Utils_System::url('civicrm/contact/view/membership',
                                     $urlParams, true, null, false ); 
        $this->assign("refreshURL",$url);

        $this->applyFilter('__ALL__', 'trim');

        $this->add('date', 'join_date', ts('Join Date'), CRM_Core_SelectValues::date('manual', 20, 1), false );         
        $this->addRule('join_date', ts('Select a valid date.'), 'qfDate');
        $this->add('date', 'start_date', ts('Start Date'), CRM_Core_SelectValues::date('manual', 20, 1), false );         
        $this->addRule('start_date', ts('Select a valid date.'), 'qfDate');
        $this->add('date', 'end_date', ts('End Date'), CRM_Core_SelectValues::date('manual', 20, 5), false );         
        $this->addRule('end_date', ts('Select a valid date.'), 'qfDate');
        
        $this->add('text', 'source', ts('Source'), 
                   CRM_Core_DAO::getAttribute( 'CRM_Member_DAO_Membership', 'source' ) );
        $this->add('select', 'status_id', ts( 'Status' ), 
                   array(''=>ts( '-select-' )) + CRM_Member_PseudoConstant::membershipStatus( ) );

        $this->addElement('checkbox', 'is_override', ts('Status Hold?'), null, array( 'onClick' => 'showHideMemberStatus()'));

        $this->addFormRule(array('CRM_Member_Form_Membership', 'formRule'));

        //build custom data
        CRM_Core_BAO_CustomGroup::buildQuickForm( $this, $this->_groupTree, 'showBlocks1', 'hideBlocks1' );
    }

    /**
     * Function for validation
     *
     * @param array $params (ref.) an assoc array of name/value pairs
     *
     * @return mixed true or array of errors
     * @access public
     * @static
     */
    public function formRule( &$params ) {
        $errors = array( );
        if (!$params['membership_type_id'][1]) {
            $errors['membership_type_id'] = "Please select a Membership Type.";
        }
        if ( !($params['join_date']['M'] && $params['join_date']['d'] && $params['join_date']['Y']) ) {
            $errors['join_date'] = "Please enter the Join Date.";
        }
        if ( isset( $params['is_override'] ) &&
             $params['is_override']          &&
             ! $params['status_id'] ) {
            $errors['status_id'] = "Please enter the status.";
        }
              
        return empty($errors) ? true : $errors;
    }
       
    /**
     * Function to process the form
     *
     * @access public
     * @return None
     */
    public function postProcess() 
    {
        require_once 'CRM/Member/BAO/Membership.php';
        require_once 'CRM/Member/BAO/MembershipType.php';
        require_once 'CRM/Member/BAO/MembershipStatus.php';

        if ( $this->_action & CRM_Core_Action::DELETE ) {
            CRM_Member_BAO_Membership::deleteRelatedMemberships( $this->_id );
            CRM_Member_BAO_Membership::deleteMembership( $this->_id );
            return;
        }
        
        // get the submitted form values.  
        $formValues = $this->controller->exportValues( $this->_name );

        $params = array( );
        $ids    = array( );

        $params['contact_id'] = $this->_contactID;
        
        $fields = array( 
                        'status_id',
                        'source',
                        'is_override'
                        );
        
        foreach ( $fields as $f ) {
            $params[$f] = CRM_Utils_Array::value( $f, $formValues );
        }
        
        $params['membership_type_id'] = $formValues['membership_type_id'][1];
       
        $joinDate  = CRM_Utils_Date::mysqlToIso(CRM_Utils_Date::format( $formValues['join_date'] ));
        $startDate = CRM_Utils_Date::mysqlToIso(CRM_Utils_Date::format( $formValues['start_date'] ));
        $endDate   = CRM_Utils_Date::mysqlToIso(CRM_Utils_Date::format( $formValues['end_date'] ));

        $calcDates = CRM_Member_BAO_MembershipType::getDatesForMembershipType($params['membership_type_id'],
                                                                              $joinDate, $startDate, $endDate);

        $dates = array( 'join_date',
                        'start_date',
                        'end_date',
                        'reminder_date'
                        );
        $currentTime = getDate();        
        foreach ( $dates as $d ) {
            if ( isset( $formValues[$d] ) &&
                 ! CRM_Utils_System::isNull( $formValues[$d] ) ) {
                $params[$d] = CRM_Utils_Date::format( $formValues[$d] );
            } else if ( isset( $calcDates[$d] ) ) {
                $params[$d] = CRM_Utils_Date::isoToMysql($calcDates[$d]);
            }
        }

        // change reminder date if end-date present
        if ( ! CRM_Utils_System::isNull( $formValues['end_date'] ) ) {
            $membershipTypeDetails = CRM_Member_BAO_MembershipType::getMembershipTypeDetails( $formValues['membership_type_id'] );
            if ( isset( $membershipTypeDetails["renewal_reminder_day"] ) &&
                 $membershipTypeDetails["renewal_reminder_day"] ) {
                $year  = $formValues['end_date']['Y'];
                $month = $formValues['end_date']['M'];
                $day   = $formValues['end_date']['d'];
                $day = $day - $membershipTypeDetails["renewal_reminder_day"];
                $params['reminder_date'] = str_replace('-', "", date('Y-m-d',mktime($hour, $minute, $second, $month, $day-1, $year)));
            }
        }
        
        $ids['membership'] = $params['id'] = $this->_id;
        
        $session = CRM_Core_Session::singleton();
        $ids['userId'] = $session->get('userID');
        
        //format custom data
        // get mime type of the uploaded file
        if ( !empty($_FILES) ) {
            foreach ( $_FILES as $key => $value) {
                $files = array( );
                if ( $formValues[$key] ) {
                    $files['name'] = $formValues[$key];
                }
                if ( $value['type'] ) {
                    $files['type'] = $value['type']; 
                }
                $formValues[$key] = $files;
            }
        }

        $customData = array( );
        foreach ( $formValues as $key => $value ) {
            if ( $customFieldId = CRM_Core_BAO_CustomField::getKeyID($key) ) {
                CRM_Core_BAO_CustomField::formatCustomField( $customFieldId, $customData, $value, 'Membership', null, $this->_id);
            }
        }
        
        if (! empty($customData) ) {
            $params['custom'] = $customData;
        }
        
        //special case to handle if all checkboxes are unchecked
        $customFields = CRM_Core_BAO_CustomField::getFields( 'Membership' );

        if ( !empty($customFields) ) {
            foreach ( $customFields as $k => $val ) {
                if ( in_array ( $val[3], array ('CheckBox','Multi-Select') ) &&
                     ! CRM_Utils_Array::value( $k, $params['custom'] ) ) {
                    CRM_Core_BAO_CustomField::formatCustomField( $k, $params['custom'],
                                                                 '', 'Membership', null, $this->_id);
                }
            }
        }

        $membership =& CRM_Member_BAO_Membership::create( $params, $ids );
        
        $relatedContacts = array( );
        if ( ! is_a( $membership, 'CRM_Core_Error') ) {
            $relatedContacts = CRM_Member_BAO_Membership::checkMembershipRelationship( 
                                                                                      $membership->id,
                                                                                      $membership->contact_id,
                                                                                      $this->_action
                                                                                      );
        }
        
        if ( ! empty($relatedContacts) ) {
            // delete all the related membership records before creating
            CRM_Member_BAO_Membership::deleteRelatedMemberships( $membership->id );
            
            // Edit the params array
            unset( $params['id'] );
            // Reminder should be sent only to the direct membership
            unset( $params['reminder_date'] );
            // unset the custom value ids
            if ( is_array( $params['custom'] ) ) {
                foreach ( $params['custom'] as $k => $v ) {
                    unset( $params['custom'][$k]['id'] );
                }
            }
                        
            foreach ( $relatedContacts as $contactId => $relationshipStatus ) {
                $params['contact_id'         ] = $contactId;
                $params['owner_membership_id'] = $membership->id;
                // set status_id as it might have been changed for
                // past relationship
                $params['status_id'          ] = $membership->status_id;
                
                if ( ( $this->_action & CRM_Core_Action::UPDATE ) && 
                     ( $relationshipStatus == CRM_Contact_BAO_Relationship::PAST ) ) {
                    // FIXME : While updating/ renewing the
                    // membership, if the relationship is PAST then
                    // the membership of the related contact must be
                    // expired. 
                    // For that, getting Membership Status for which
                    // is_current_member is 0. It works for the
                    // generated data as there is only one membership
                    // status having is_current_member = 0.
                    // But this wont work exactly if there will be
                    // more than one status having is_current_member = 0.
                    $params['status_id'] = CRM_Core_DAO::getFieldValue('CRM_Member_DAO_MembershipStatus', '0', 'id', 'is_current_member' );
                }
                
                CRM_Member_BAO_Membership::create( $params, CRM_Core_DAO::$_nullArray );
            }
        }
        
        CRM_Core_Session::setStatus( ts('The membership information has been saved.') );
    }
}
?>