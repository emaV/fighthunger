<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 1.3                                                |
 +--------------------------------------------------------------------+
 | Copyright (c) 2005 Donald A. Lobo                                  |
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
 * @copyright Donald A. Lobo (c) 2005
 * $Id$
 *
 */

$GLOBALS['_CRM_CONTRIBUTE_PAGE_CONTRIBUTIONTYPE']['_links'] =  null;

require_once 'CRM/Core/Page/Basic.php';

/**
 * Page for displaying list of contribution types
 */
class CRM_Contribute_Page_ContributionType extends CRM_Core_Page_Basic 
{
    /**
     * The action links that we need to display for the browse screen
     *
     * @var array
     * @static
     */
    

    /**
     * Get BAO Name
     *
     * @return string Classname of BAO.
     */
    function getBAOName() 
    {
        return 'CRM_Contribute_BAO_ContributionType';
    }

    /**
     * Get action Links
     *
     * @return array (reference) of action links
     */
    function &links()
    {
        if (!($GLOBALS['_CRM_CONTRIBUTE_PAGE_CONTRIBUTIONTYPE']['_links'])) {
            // helper variable for nicer formatting
            $disableExtra = ts('Are you sure you want to disable this contribution type?');

            $GLOBALS['_CRM_CONTRIBUTE_PAGE_CONTRIBUTIONTYPE']['_links'] = array(
                                  CRM_CORE_ACTION_UPDATE  => array(
                                                                    'name'  => ts('Edit'),
                                                                    'url'   => 'civicrm/admin/contribute/contributionType',
                                                                    'qs'    => 'action=update&id=%%id%%&reset=1',
                                                                    'title' => ts('Edit Contribution Type') 
                                                                   ),
                                  CRM_CORE_ACTION_DISABLE => array(
                                                                    'name'  => ts('Disable'),
                                                                    'url'   => 'civicrm/admin/contribute/contributionType',
                                                                    'qs'    => 'action=disable&id=%%id%%',
                                                                    'extra' => 'onclick = "return confirm(\'' . $disableExtra . '\');"',
                                                                    'title' => ts('Disable Contribution Type') 
                                                                   ),
                                  CRM_CORE_ACTION_ENABLE  => array(
                                                                    'name'  => ts('Enable'),
                                                                    'url'   => 'civicrm/admin/contribute/contributionType',
                                                                    'qs'    => 'action=enable&id=%%id%%',
                                                                    'title' => ts('Enable Contribution Type') 
                                                                    ),
                                  CRM_CORE_ACTION_DELETE  => array(
                                                                    'name'  => ts('Delete'),
                                                                    'url'   => 'civicrm/admin/contribute/contributionType',
                                                                    'qs'    => 'action=delete&id=%%id%%',
                                                                    'title' => ts('Delete Contribution Type') 
                                                                   )
                                 );
        }
        return $GLOBALS['_CRM_CONTRIBUTE_PAGE_CONTRIBUTIONTYPE']['_links'];
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
        $action = CRM_Utils_Request::retrieve('action', $this, false, 'browse'); // default to 'browse'

        // assign vars to templates
        $this->assign('action', $action);
        $id = CRM_Utils_Request::retrieve('id', $this, false, 0);
        
        // what action to take ?
        if ($action & (CRM_CORE_ACTION_UPDATE | CRM_CORE_ACTION_ADD)) {
            $this->edit($action, $id) ;
        } 
        // finally browse the custom groups
        $this->browse();
        
        // parent run 
        parent::run();
    }

    /**
     * Browse all custom data groups.
     *  
     * 
     * @return void
     * @access public
     * @static
     */
    function browse()
    {
        // get all custom groups sorted by weight
        $contributionType = array();
        require_once 'CRM/Contribute/DAO/ContributionType.php';
        $dao =& new CRM_Contribute_DAO_ContributionType();

        // set the domain_id parameter
        $config =& CRM_Core_Config::singleton( );
        $dao->domain_id = $config->domainID( );

        $dao->orderBy('name');
        $dao->find();

        while ($dao->fetch()) {
            $contributionType[$dao->id] = array();
            CRM_Core_DAO::storeValues( $dao, $contributionType[$dao->id]);
            // form all action links
            $action = array_sum(array_keys($this->links()));

            // update enable/disable links depending on if it is is_reserved or is_active
            if ($dao->is_reserved) {
                continue;
            } else {
                if ($dao->is_active) {
                    $action -= CRM_CORE_ACTION_ENABLE;
                } else {
                    $action -= CRM_CORE_ACTION_DISABLE;
                }
            }

            $contributionType[$dao->id]['action'] = CRM_Core_Action::formLink(CRM_Contribute_Page_ContributionType::links(), $action, 
                                                                                    array('id' => $dao->id));
        }
        $this->assign('rows', $contributionType);
    }

    /**
     * Get name of edit form
     *
     * @return string Classname of edit form.
     */
    function editForm() 
    {
        return 'CRM_Contribute_Form_ContributionType';
    }
    
    /**
     * Get edit form name
     *
     * @return string name of this page.
     */
    function editName() 
    {
        return 'Contribution Types';
    }
    
    /**
     * Get user context.
     *
     * @return string user context.
     */
    function userContext($mode = null) 
    {
        return 'civicrm/admin/contribute/contributionType';
    }
}

?>
