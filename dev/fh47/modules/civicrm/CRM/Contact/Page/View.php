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
              
require_once 'CRM/Core/Page.php';
require_once 'CRM/Core/BAO/CustomGroup.php';
require_once 'CRM/Core/BAO/CustomOption.php';

require_once 'CRM/Utils/Recent.php';

require_once 'CRM/Contact/BAO/Contact.php';
require_once 'CRM/Core/Menu.php';

/**
 * Main page for viewing contact.
 *
 */
class CRM_Contact_Page_View extends CRM_Core_Page {
    /**
     * the id of the object being viewed (note/relationship etc)
     *
     * @int
     * @access protected
     */
    protected $_id;

    /**
     * the contact id of the contact being viewed
     *
     * @int
     * @access protected
     */
    protected $_contactId;

    /**
     * The action that we are performing
     *
     * @string
     * @access protected
     */
    protected $_action;

    /**
     * The permission we have on this contact
     *
     * @string
     * @access protected
     */
    protected $_permission;

    /**
     * Heart of the viewing process. The runner gets all the meta data for
     * the contact and calls the appropriate type of page to view.
     *
     * @return void
     * @access public
     *
     */
    function preProcess( )
    {
        $this->_id = CRM_Utils_Request::retrieve( 'id', 'Positive', $this );
        $this->assign( 'id', $this->_id );
        
        // retrieve the group contact id, so that we can get contact id
        $gcid = CRM_Utils_Request::retrieve( 'gcid', 'Positive', $this );
        
        if ( !$gcid ) {
            $this->_contactId = CRM_Utils_Request::retrieve( 'cid', 'Positive', $this, true );
        } else {
            $this->_contactId = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_GroupContact', $gcid, 'contact_id' );
        }

        if ( ! $this->_contactId ) {
            CRM_Core_Error::statusBounce( ts( 'We could not find a contact id.' ) );
        }
        $this->assign( 'contactId', $this->_contactId );
        
        // also store in session for future use
        $session =& CRM_Core_Session::singleton( );
        $session->set( 'view.id', $this->_contactId );

        $this->_action = CRM_Utils_Request::retrieve('action', 'String',
                                                     $this, false, 'browse');
        $this->assign( 'action', $this->_action);

        // check for permissions
        $this->_permission = null;

        // automatically grant permissin for users on their own record. makes 
        // things easier in dashboard
        if ( $session->get( 'userID' ) == $this->_contactId ) {
            $this->assign( 'permission', 'edit' );
            $this->_permission = CRM_Core_Permission::EDIT;            
        } else if ( CRM_Contact_BAO_Contact::permissionedContact( $this->_contactId, CRM_Core_Permission::EDIT ) ) {
            $this->assign( 'permission', 'edit' );
            $this->_permission = CRM_Core_Permission::EDIT;            
        } else if ( CRM_Contact_BAO_Contact::permissionedContact( $this->_contactId, CRM_Core_Permission::VIEW ) ) {
            $this->assign( 'permission', 'view' );
            $this->_permission = CRM_Core_Permission::VIEW;
        } else {
            $session->pushUserContext( CRM_Utils_System::url('civicrm', 'reset=1' ) );
            CRM_Core_Error::statusBounce( ts('You do not have the necessary permission to view this contact.') );
        }

        $this->getContactDetails();

        $contactImage = $this->get( 'contactImage' );
        $displayName  = $this->get( 'displayName'  );
        $this->assign( 'displayName', $displayName );

        // see if other modules want to add a link activtity bar
        require_once 'CRM/Utils/Hook.php';
        $hookLinks = CRM_Utils_Hook::links( 'view.contact.activity', 'Contact', $this->_contactId );
        if ( $hookLinks ) {
            $this->assign( 'hookLinks', $hookLinks );
        }

        CRM_Utils_System::setTitle( $displayName, $contactImage . ' ' . $displayName );
        CRM_Utils_Recent::add( $displayName,
                               CRM_Utils_System::url( 'civicrm/contact/view', 'reset=1&cid=' . $this->_contactId ),
                               $contactImage,
                               $this->_contactId );
        
        // also add the cid params to the Menu array
        CRM_Core_Menu::addParam( 'cid', $this->_contactId );

        
        //------------
        // create menus ..
        // hack for now, disable if we are in tabbed mode
        $startWeight = CRM_Core_Menu::getMaxWeight('civicrm/contact/view');
        $startWeight++;
        CRM_Core_BAO_CustomGroup::addMenuTabs( CRM_Contact_BAO_Contact::getContactType($this->_contactId), 
                                               'civicrm/contact/view/cd',
                                               $startWeight );

        //display OtherActivity link 
        $otherAct = CRM_Core_PseudoConstant::activityType(false);
        $activityNum = count($otherAct);
        $this->assign('showOtherActivityLink',$activityNum);
        
        $config =& CRM_Core_Config::singleton( );
        
        require_once 'CRM/Core/BAO/UFMatch.php';
        if ( $uid = CRM_Core_BAO_UFMatch::getUFId( $this->_contactId ) ) {
            if ($config->userFramework == 'Drupal') {
                $url = CRM_Utils_System::url( 'user/' . $uid );
            } else if ( $config->userFramework == 'Joomla' ) {
                $url = $config->userFrameworkBaseURL . 'index2.php?option=com_users&task=editA&hidemainmenu=1&id=' . $uid;
            } else {
                $url = null;
            }
            $this->assign( 'url', $url );
        }
    
        if ( CRM_Core_Permission::check( 'access Contact Dashboard' ) ) {
            $dashboardURL = CRM_Utils_System::url( 'civicrm/user',
                                                   "reset=1&id={$this->_contactId}" );
            $this->assign( 'dashboardURL', $dashboardURL );
        }
    }


    /**
     * Get meta details of the contact.
     *
     * @return void
     * @access public
     */
    function getContactDetails()
    {
        $displayName = $this->get( 'displayName' );
             
        // if the display name is cached, we can skip the other processing
        if ( isset( $displayName ) ) {
            // return;
        }

        list( $displayName, $contactImage ) = CRM_Contact_BAO_Contact::getDisplayAndImage( $this->_contactId );

        $this->set( 'displayName' , $displayName );
        $this->set( 'contactImage', $contactImage );
    }

}

?>
