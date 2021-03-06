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

require_once 'CRM/Core/Page/Basic.php';

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2007
 * $Id$
 *
 */
class CRM_ACL_Page_ACL extends CRM_Core_Page_Basic 
{
    /**
     * The action links that we need to display for the browse screen
     *
     * @var array
     * @static
     */
    static $_links = null;

    /**
     * Get BAO Name
     *
     * @return string Classname of BAO.
     */
    function getBAOName() 
    {
        return 'CRM_ACL_BAO_ACL';
    }

    /**
     * Get action Links
     *
     * @return array (reference) of action links
     */
    function &links()
    {
          if (!(self::$_links)) {
              $disableExtra = ts('Are you sure you want to disable this ACL?');
            // helper variable for nicer formatting
              self::$_links = array(
                                    CRM_Core_Action::UPDATE  => array(
                                                                      'name'  => ts('Edit'),
                                                                      'url'   => 'civicrm/acl',
                                                                      'qs'    => 'reset=1&action=update&id=%%id%%',
                                                                      'title' => ts('Edit ACL') 
                                                                      ),
                                    CRM_Core_Action::DISABLE => array(
                                                                      'name'  => ts('Disable'),
                                                                      'url'   => 'civicrm/acl',
                                                                      'qs'    => 'reset=1&action=disable&id=%%id%%',
                                                                      'extra' => 'onclick = "return confirm(\'' . $disableExtra . '\');"',
                                                                      'title' => ts('Disable ACL') 
                                                                      ),
                                    CRM_Core_Action::ENABLE  => array(
                                                                      'name'  => ts('Enable'),
                                                                      'url'   => 'civicrm/acl',
                                                                      'qs'    => 'reset=1&action=enable&id=%%id%%',
                                                                      'title' => ts('Enable ACL') 
                                                                      ),
                                    CRM_Core_Action::DELETE  => array(
                                                                      'name'  => ts('Delete'),
                                                                      'url'   => 'civicrm/acl',
                                                                      'qs'    => 'reset=1&action=delete&id=%%id%%',
                                                                      'title' => ts('Delete ACL') 
                                                                      ),

                                 );
        }
        return self::$_links;
    }

    /**
     * Run the page.
     *
     * This method is called after the page is created. It checks for the  
     * type of action and executes that action.
     * Finally it calls the parent's run method.
     *
     * @return void
     * @access public
     *
     */
    function run()
    {
        // get the requested action
        $action = CRM_Utils_Request::retrieve('action', 'String',
                                              $this, false, 'browse'); // default to 'browse'

        // assign vars to templates
        $this->assign('action', $action);
        $id = CRM_Utils_Request::retrieve('id', 'Positive',
                                          $this, false, 0);
        
        // set breadcrumb to append to admin/access
        $breadCrumbPath = CRM_Utils_System::url( 'civicrm/admin/access', 'reset=1' );
        CRM_Utils_System::appendBreadCrumb( ts('Access Control'), $breadCrumbPath );

        // what action to take ?
        if ($action & (CRM_Core_Action::UPDATE | CRM_Core_Action::ADD | CRM_Core_Action::DELETE)) {
            $this->edit($action, $id) ;
        } 
        // finally browse the acl's
         $this->browse();
        
        // parent run 
        parent::run();
    }

    /**
     * Browse all acls
     * 
     * @return void
     * @access public
     * @static
     */
    function browse()
    {
        require_once 'CRM/ACL/DAO/ACL.php';

        // get all acl's sorted by weight
        $acl =  array( );
        $dao =& new CRM_ACL_DAO_ACL( );

        // set the domain_id parameter
        $config =& CRM_Core_Config::singleton( );
        $dao->domain_id = $config->domainID( );
        $dao->orderBy( 'entity_id' );
        $dao->find( );

        require_once 'CRM/Core/OptionGroup.php';
        $roles  = CRM_Core_OptionGroup::values( 'acl_role' );

        $group       = array( '-1' => ts( '-select-' ), '0'  => ts( 'All Groups' ) )        + CRM_Core_PseudoConstant::group( )      ;
        $customGroup = array( '-1' => ts( '-select-' ), '0'  => ts( 'All Custom Groups' ) ) + CRM_Core_PseudoConstant::customGroup( );
        $ufGroup     = array( '-1' => ts( '-select-' ), '0'  => ts( 'All Profiles' ) )      + CRM_Core_PseudoConstant::ufGroup( )    ;

        while ( $dao->fetch( ) ) {
            $acl[$dao->id] = array();
            CRM_Core_DAO::storeValues( $dao, $acl[$dao->id]);

            if ( $acl[$dao->id]['entity_id'] ) {
                $acl[$dao->id]['entity'] = $roles [$acl[$dao->id]['entity_id']];
            } else {
                $acl[$dao->id]['entity'] = ts( 'Any Role' );
            }

            switch ( $acl[$dao->id]['object_table'] ) {
            case 'civicrm_saved_search':
                $acl[$dao->id]['object'     ] = $group[$acl[$dao->id]['object_id']];
                $acl[$dao->id]['object_name'] = ts( 'Group' );
                break;

            case 'civicrm_uf_group':
                $acl[$dao->id]['object'     ] = $ufGroup[$acl[$dao->id]['object_id']];
                $acl[$dao->id]['object_name'] = ts( 'Profile' );
                break;

            case 'civicrm_custom_group':
                $acl[$dao->id]['object'     ] = $customGroup[$acl[$dao->id]['object_id']];
                $acl[$dao->id]['object_name'] = ts( 'Custom Group' );
                break;
            }

            // form all action links
            $action = array_sum(array_keys($this->links()));

            if ($dao->is_active) {
                $action -= CRM_Core_Action::ENABLE;
            } else {
                $action -= CRM_Core_Action::DISABLE;
            }
            
            $acl[$dao->id]['action'] = CRM_Core_Action::formLink(self::links(), $action, 
                                                                 array('id' => $dao->id));
        }
        $this->assign('rows', $acl);
    }

    /**
     * Get name of edit form
     *
     * @return string Classname of edit form.
     */
    function editForm() 
    {
        return 'CRM_ACL_Form_ACL';
    }
    
    /**
     * Get edit form name
     *
     * @return string name of this page.
     */
    function editName() 
    {
        return 'ACL';
    }
    
    /**
     * Get user context.
     *
     * @return string user context.
     */
    function userContext($mode = null) 
    {
        return 'civicrm/acl';
    }
}

?>
