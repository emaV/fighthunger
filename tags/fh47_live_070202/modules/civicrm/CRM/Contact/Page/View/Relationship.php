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
* This class contains functions for managing Relationship(s) of a Contact. 
 *
 * @package CRM
 * @author Donald A. Lobo <lobo@yahoo.com>
 * @copyright CiviCRM LLC (c) 2004-2006
 * $Id$
 *
 */

require_once 'CRM/Contact/Page/View.php';

class CRM_Contact_Page_View_Relationship extends CRM_Contact_Page_View {
    /**
     * The action links that we need to display for the browse screen
     *
     * @var array
     * @static
     */
    static $_links = null;
    
    /**
     * View details of a relationship
     *
     * @return void
     *
     * @access public
     */
    function view( )
    {
        require_once 'CRM/Core/DAO.php';
        $viewRelationship = CRM_Contact_BAO_Relationship::getRelationship( $this->_contactId, null, null, null, $this->_id );
        $this->assign( 'viewRelationship', $viewRelationship );
        $viewNote = CRM_Core_BAO_Note::getNote($this->_id);
        $this->assign( 'viewNote', $viewNote );
        $this->_groupTree =& CRM_Core_BAO_CustomGroup::getTree('Relationship',$this->_id,0);
        CRM_Core_BAO_CustomGroup::buildViewHTML( $this, $this->_groupTree );
    }

   /**
     * This function is called when action is browse
     * 
     * return null
     * @access public
     */
    function browse( ) {
        $links =& self::links( );
        $mask  = CRM_Core_Action::mask( $this->_permission );

        $currentRelationships = CRM_Contact_BAO_Relationship::getRelationship($this->_contactId,
                                                                              CRM_Contact_BAO_Relationship::CURRENT  ,
                                                                              0, 0, 0,
                                                                              $links, $mask );
        $pastRelationships    = CRM_Contact_BAO_Relationship::getRelationship( $this->_contactId,
                                                                               CRM_Contact_BAO_Relationship::PAST     ,
                                                                               0, 0, 0,
                                                                               $links, $mask );
        $disableRelationships = CRM_Contact_BAO_Relationship::getRelationship( $this->_contactId,
                                                                               CRM_Contact_BAO_Relationship::DISABLED ,
                                                                               0, 0, 0,
                                                                               $links, $mask );
        
        $this->assign( 'currentRelationships', $currentRelationships );
        $this->assign( 'pastRelationships'   , $pastRelationships );
        $this->assign( 'disableRelationships', $disableRelationships );
        
    }    
    
    /**
     * This function is called when action is update or new
     * 
     * return null
     * @access public
     */
    function edit( ) {
        $controller =& new CRM_Core_Controller_Simple( 'CRM_Contact_Form_Relationship', ts('Contact Relationships'), $this->_action );
        $controller->setEmbedded( true );

        // set the userContext stack
        $session =& CRM_Core_Session::singleton();

        $url = CRM_Utils_System::url('civicrm/contact/view/rel', 'action=browse' );
        $session->pushUserContext( $url );

        if (CRM_Utils_Request::retrieve('confirmed', 'Boolean',
                                        CRM_Core_DAO::$_nullObject ) ) {
            CRM_Contact_BAO_Relationship::del( $this->_id);
            CRM_Utils_System::redirect($url);
        }
        
        $controller->set( 'contactId', $this->_contactId );
        $controller->set( 'id'       , $this->_id );
        $controller->process( );
        $controller->run( );
    }

   /**
     * This function is the main function that is called when the page loads,
     * it decides the which action has to be taken for the page.
     * 
     * return null
     * @access public
     */
    function run( ) {
        $this->preProcess( );

        if ( $this->_action & CRM_Core_Action::VIEW ) {
            $this->view( );
        } else if ( $this->_action & ( CRM_Core_Action::UPDATE | CRM_Core_Action::ADD | CRM_Core_Action::DELETE ) ) {
            $this->edit( );
        } else if ( $this->_action & CRM_Core_Action::DISABLE ) {
            CRM_Contact_BAO_Relationship::setIsActive( $this->_id, 0 ) ;
        } else if ( $this->_action & CRM_Core_Action::ENABLE ) {
            CRM_Contact_BAO_Relationship::setIsActive( $this->_id, 1 ) ;
        } 

        $this->browse( );

        return parent::run( );
    }
    
   /**
     * This function is called to delete the relationship of a contact
     * 
     * return null
     * @access public
     */
    function delete( ) {
        // calls a function to delete relationship
        CRM_Contact_BAO_Relationship::del($this->_id);
    }

    /**
     * Get action links
     *
     * @return array (reference) of action links
     * @static
     */
    static function &links()
    {
        if (!(self::$_links)) {
            $deleteExtra = ts('Are you sure you want to delete this relationship?');
            $disableExtra = ts('Are you sure you want to disable this relationship?');
            $enableExtra = ts('Are you sure you want to re-enable this relationship?');

            self::$_links = array(
                                  CRM_Core_Action::VIEW    => array(
                                                                    'name'  => ts('View'),
                                                                    'url'   => 'civicrm/contact/view/rel',
                                                                    'qs'    => 'action=view&reset=1&cid=%%cid%%&id=%%id%%&rtype=%%rtype%%',
                                                                    'title' => ts('View Relationship')
                                                                    ),
                                  CRM_Core_Action::UPDATE  => array(
                                                                    'name'  => ts('Edit'),
                                                                    'url'   => 'civicrm/contact/view/rel',
                                                                    'qs'    => 'action=update&reset=1&cid=%%cid%%&id=%%id%%&rtype=%%rtype%%',
                                                                    'title' => ts('Edit Relationship')
                                                                    ),
                                  CRM_Core_Action::ENABLE  => array(
                                                                    'name'  => ts('Enable'),
                                                                    'url'   => 'civicrm/contact/view/rel',
                                                                    'qs'    => 'action=enable&reset=1&cid=%%cid%%&id=%%id%%&rtype=%%rtype%%',
                                                                    'extra' => 'onclick = "return confirm(\'' . $enableExtra . '\');"',
                                                                    'title' => ts('Enable Relationship')
                                                                    ),
                                  CRM_Core_Action::DISABLE => array(
                                                                    'name'  => ts('Disable'),
                                                                    'url'   => 'civicrm/contact/view/rel',
                                                                    'qs'    => 'action=disable&reset=1&cid=%%cid%%&id=%%id%%&rtype=%%rtype%%',
                                                                    'extra' => 'onclick = "return confirm(\'' . $disableExtra . '\');"',
                                                                    'title' => ts('Disable Relationship')
                                                                    ),
                                  CRM_Core_Action::DELETE  => array(
                                                                    'name'  => ts('Delete'),
                                                                    'url'   => 'civicrm/contact/view/rel',
                                                                    'qs'    => 'action=delete&reset=1&cid=%%cid%%&id=%%id%%&rtype=%%rtype%%',
                                                                    'extra' => 'onclick = "if (confirm(\'' . $deleteExtra . '\') ) this.href+=\'&amp;confirmed=1\'; else return false;"',
                                                                    'title' => ts('Delete Relationship')
                                                                    ),
                                  );
        }
        return self::$_links;
    }
                                  
}

?>
