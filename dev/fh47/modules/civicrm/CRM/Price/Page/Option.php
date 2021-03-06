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

/**
 * Create a page for displaying Custom Options.
 *
 * Heart of this class is the run method which checks
 * for action type and then displays the appropriate
 * page.
 *
 */
class CRM_Price_Page_Option extends CRM_Core_Page {
     
    /**
     * The Group id of the option
     *
     * @var int
     * @access protected
     */
    protected $_gid;
    
    /**
     * The field id of the option
     *
     * @var int
     * @access protected
     */
    protected $_fid;

    /**
     * The action links that we need to display for the browse screen
     *
     * @var array
     * @access private
     */
    private static $_actionLinks;


    /**
     * Get the action links for this page.
     * 
     * @param null
     * 
     * @return array  array of action links that we need to display for the browse screen
     * @access public
     */
    function &actionLinks()
    {
       
        if (!isset(self::$_actionLinks)) {
            // helper variable for nicer formatting
            $disableExtra = ts('Are you sure you want to disable this price option?');
            self::$_actionLinks = array(
                                        CRM_Core_Action::UPDATE  => array(
                                                                          'name'  => ts('Edit Option'),
                                                                          'url'   => 'civicrm/admin/price/field/option',
                                                                          'qs'    => 'reset=1&action=update&id=%%id%%&fid=%%fid%%&gid=%%gid%%',
                                                                          'title' => ts('Edit Price Option') 
                                                                          ),
                                        CRM_Core_Action::VIEW    => array(
                                                                          'name'  => ts('View'),
                                                                          'url'   => 'civicrm/admin/price/field/option',
                                                                          'qs'    => 'action=view&id=%%id%%',
                                                                          'title' => ts('View Price Option'),
                                                                          ),
                                        CRM_Core_Action::ENABLE  => array(
                                                                          'name'  => ts('Enable'),
                                                                          'url'   => 'civicrm/admin/price/field/option',
                                                                          'qs'    => 'action=enable&id=%%id%%',
                                                                          'title' => ts('Enable Price Option') 
                                                                          ),
                                        CRM_Core_Action::DISABLE  => array(
                                                                           'name'  => ts('Disable'),
                                                                           'url'   => 'civicrm/admin/price/field/option',
                                                                           'qs'    => 'action=disable&id=%%id%%',
                                                                           'title' => ts('Disable Price Option'),
                                                                           'extra' => 'onclick = "return confirm(\'' . $disableExtra . '\');"'
                                                                           ),
                                        CRM_Core_Action::DELETE  => array(
                                                                           'name'  => ts('Delete'),
                                                                           'url'   => 'civicrm/admin/price/field/option',
                                                                           'qs'    => 'action=delete&id=%%id%%',
                                                                           'title' => ts('Disable Price Option'),
                                                                           
                                                                           ),
                                        );
        }
        return self::$_actionLinks;
    }

    /**
     * Browse all price fields.
     * 
     * @param null
     * 
     * @return void
     * @access public
     */
    function browse()
    {
        $customOption = array();
        $customOptionBAO =& new CRM_Core_BAO_CustomOption();
        
        // fkey is fid
        //$customOptionBAO->custom_field_id = $this->_fid;
        $customOptionBAO->entity_id    = $this->_fid;
        $customOptionBAO->entity_table = 'civicrm_price_field';

        $customOptionBAO->orderBy('weight, label');
        $customOptionBAO->find();
        
        $priceFieldBAO =& new CRM_Core_BAO_PriceField();
        $priceFieldBAO->id = $this->_fid;
        $priceFieldBAO->find();
        while($priceFieldBAO->fetch()) {
            $fieldHtmlType = $priceFieldBAO->html_type; 
        }

        while ($customOptionBAO->fetch()) {
            $customOption[$customOptionBAO->id] = array();
            CRM_Core_DAO::storeValues( $customOptionBAO, $customOption[$customOptionBAO->id]);

            $action = array_sum(array_keys($this->actionLinks()));
	    
	    // update enable/disable links depending on price_field properties.
            if ( $customOptionBAO->is_active ) {
                $action -= CRM_Core_Action::ENABLE;
            } else {
                $action -= CRM_Core_Action::DISABLE;
            }
            
            $customOption[$customOptionBAO->id]['action'] = CRM_Core_Action::formLink(self::actionLinks(), $action, 
                                                                                    array('id'  => $customOptionBAO->id,
                                                                                          'fid' => $this->_fid,
                                                                                          'gid' => $this->_gid));
        }
        $this->assign('customOption', $customOption);
    }


    /**
     * edit custom Option.
     *
     * editing would involved modifying existing fields + adding data to new fields.
     *
     * @param string  $action   the action to be invoked
     * 
     * @return void
     * @access public
     */
    function edit($action)
    {
        // create a simple controller for editing custom data
        require_once('CRM/Core/BAO/PriceField.php');
        $controller =& new CRM_Core_Controller_Simple('CRM_Price_Form_Option', ts('Price Field Option'), $action);

        // set the userContext stack
        $session =& CRM_Core_Session::singleton();
        $session->pushUserContext(CRM_Utils_System::url('civicrm/admin/price/field/option', 'reset=1&action=browse&fid=' . $this->_fid));
       
        $controller->set('gid', $this->_gid);
        $controller->set('fid', $this->_fid);
        $controller->setEmbedded(true);
        $controller->process();
        $controller->run();
        $this->browse();
    }


    /**
     * Run the page.
     *
     * This method is called after the page is created. It checks for the  
     * type of action and executes that action. 
     * 
     * @param null
     * 
     * @return void
     * @access public
     */
    function run()
    {
        require_once 'CRM/Core/BAO/PriceField.php';
        $this->assign( 'dojoIncludes', "dojo.require('dojo.widget.SortableTable');" );

        // get the field id
        $this->_fid = CRM_Utils_Request::retrieve('fid', 'Positive',
                                                  $this, false, 0);
        $this->_gid = CRM_Utils_Request::retrieve('gid', 'Positive',
                                                  $this, false, 0);

        if ($this->_fid) {
            $fieldTitle = CRM_Core_BAO_PriceField::getTitle($this->_fid);
            $this->assign('fid', $this->_fid);
            $this->assign('fieldTitle', $fieldTitle);
            CRM_Utils_System::setTitle(ts('%1 - Price Options', array(1 => $fieldTitle)));
        }

        // get the requested action
        $action = CRM_Utils_Request::retrieve('action', 'String',
                                              $this, false, 'browse'); // default to 'browse'

        // assign vars to templates
        $this->assign('action', $action);

        $id = CRM_Utils_Request::retrieve('id', 'Positive',
                                          $this, false, 0);
        
        // what action to take ?
        if ($action & (CRM_Core_Action::UPDATE | CRM_Core_Action::ADD | CRM_Core_Action::VIEW | CRM_Core_Action::DELETE)) {
            $this->edit($action);   // no browse for edit/update/view
        } else {
            if ($action & CRM_Core_Action::DISABLE) {
                CRM_Core_BAO_CustomOption::setIsActive($id, 0);
            } else if ($action & CRM_Core_Action::ENABLE) {
                CRM_Core_BAO_CustomOption::setIsActive($id, 1);
            }
           $this->browse();
        }
        // Call the parents run method
        parent::run();
    }
}
?>
