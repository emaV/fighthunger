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

require_once 'CRM/Core/Page.php';
require_once 'CRM/Core/DAO/ActivityHistory.php';
require_once 'CRM/Core/DAO/EmailHistory.php';
require_once 'CRM/Contact/BAO/Contact.php';
/**
 * Dummy page for details of Email
 *
 */
class CRM_Contact_Page_View_Email extends CRM_Core_Page {
    /**
     * Run the page.
     *
     * This method is called after the page is created.
     *
     * @return void
     * @access public
     *
     */
    function run()
    {
        // get the callback, module and activity id
        $action = CRM_Utils_Request::retrieve('action', 'String',
                                              $this, false, 'browse');
        $id     = CRM_Utils_Request::retrieve('id', 'Positive',
                                              $this);
        
        $dao =& new CRM_Core_DAO_ActivityHistory();
        $dao->activity_id = $id;
        $dao->activity_type = ts( 'Email Sent' );
        if ( $dao->find(true) ) {
            $cid = $dao->entity_id;
        }

        $dao =& new CRM_Core_DAO_EmailHistory();
        $dao->id = $id;
        
        if ( $dao->find(true) ) {
            // get the display name and email for the contact
            list($toContactName, $toContactEmail, $toDoNotEmail) = CRM_Contact_BAO_Contact::getContactDetails($cid);
            
            if ( ! trim($toContactName) ) {
                $toContactName = $toContactEmail;
            }
            
            if ( trim($toContactEmail) ) {
                $toContactName = "\"$toContactName\" <$toContactEmail>"; 
            }
            
            $this->assign('toName', $toContactName);
            
            // get the display name and email for the contact
            list($fromContactName, $fromContactEmail, $toDoNotEmail) = CRM_Contact_BAO_Contact::getContactDetails($dao->contact_id);
            
            if ( ! trim($fromContactEmail) ) {
                CRM_Utils_System::statusBounce( ts('Your user record does not have a valid email address' ));
            }
            
            if ( ! trim($fromContactName) ) {
                $fromContactName = $fromContactEmail;
            }
            
            $this->assign('fromName', "\"$fromContactName\" <$fromContactEmail>");
            
            $this->assign('sentDate', $dao->sent_date);
            $this->assign('subject', $dao->subject);
            $this->assign('message', $dao->message);

            // get the display name and images for the contact
            list( $displayName, $contactImage ) = CRM_Contact_BAO_Contact::getDisplayAndImage( $dao->contact_id );
            
            CRM_Utils_System::setTitle( $contactImage . ' ' . $displayName );
            require_once 'CRM/Core/Menu.php';
            // also add the cid params to the Menu array
            CRM_Core_Menu::addParam( 'cid',  $cid);
          
            // create menus ..
            $startWeight = CRM_Core_Menu::getMaxWeight('civicrm/contact/view');
            $startWeight++;
            require_once 'CRM/Core/BAO/CustomGroup.php';
            CRM_Core_BAO_CustomGroup::addMenuTabs(CRM_Contact_BAO_Contact::getContactType($cid), 'civicrm/contact/view/cd', $startWeight);
                                    
        }
        parent::run();
    }
}
?>
