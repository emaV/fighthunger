<?php
/*
 +--------------------------------------------------------------------+
 | CiviCRM version 1.8                                                |
 +--------------------------------------------------------------------+
 | Copyright CiviCRM LLC (c) 2004-2007                                  |
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
 | Foundation at info[AT]civicrm[DOT]org.  If you have questions       |
 | about the Affero General Public License or the licensing  of       |
 | of CiviCRM, see the Social Source Foundation CiviCRM license FAQ   |
 | http://www.civicrm.org/licensing/                                  |
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
 * Create a page for displaying Price Fields.
 *
 * Heart of this class is the run method which checks
 * for action type and then displays the appropriate
 * page.
 *
 */
class CRM_Price_Page_Field extends CRM_Core_Page {
    
    /**
     * The group id of the field
     *
     * @var int
     * @access protected
     */
    protected $_gid;

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
            $disableExtra = ts('Are you sure you want to disable this price field?');
            $deleteExtra = ts('Are you sure you want to delete this price field?');
            self::$_actionLinks = array(
                                        CRM_Core_Action::UPDATE  => array(
                                                                          'name'  => ts('Edit Price Field'),
                                                                          'url'   => 'civicrm/admin/price/field',
                                                                          'qs'    => 'action=update&reset=1&gid=%%gid%%&id=%%id%%',
                                                                          'title' => ts('Edit Price') 
                                                                          ),
                                        CRM_Core_Action::PREVIEW => array(
                                                                          'name'  => ts('Preview Field'),
                                                                          'url'   => 'civicrm/admin/price/field',
                                                                          'qs'    => 'action=preview&reset=1&gid=%%gid%%&id=%%id%%',
                                                                          'title' => ts('Preview Price'),
                                                                          ),
                                        CRM_Core_Action::DISABLE => array(
                                                                          'name'  => ts('Disable'),
                                                                          'url'   => 'civicrm/admin/price/field',
                                                                          'qs'    => 'action=disable&reset=1&gid=%%gid%%&id=%%id%%',
                                                                          'title' => ts('Disable Price'),
                                                                          'extra' => 'onclick = "return confirm(\'' . $disableExtra . '\');"',
                                                                          ),
                                        CRM_Core_Action::ENABLE  => array(
                                                                          'name'  => ts('Enable'),
                                                                          'url'   => 'civicrm/admin/price/field',
                                                                          'qs'    => 'action=enable&reset=1&gid=%%gid%%&id=%%id%%',
                                                                          'title' => ts('Enable Price'),
                                                                          ),
                                        CRM_Core_Action::DELETE  => array(
                                                                          'name'  => ts('Delete'),
                                                                          'url'   => 'civicrm/admin/price/field',
                                                                          'qs'    => 'action=delete&reset=1&gid=%%gid%%&id=%%id%%',
                                                                          'title' => ts('Delete Price'),
                                                                          'extra' => 'onclick = "return confirm(\'' . $deleteExtra . '\');"',
                                                                          ),
                        
                                        );
        }
        return self::$_actionLinks;
    }

    /**
     * Browse all price set fields.
     * 
     * @param null
     * 
     * @return void
     * @access public
     */
    function browse()
    {
        require_once 'CRM/Core/BAO/PriceField.php';
        $priceField = array();
        $priceFieldBAO =& new CRM_Core_BAO_PriceField();
        
        // fkey is gid
        $priceFieldBAO->price_set_id = $this->_gid;
        $priceFieldBAO->orderBy('weight, label');
        $priceFieldBAO->find();
       
        while ($priceFieldBAO->fetch()) {
            $priceField[$priceFieldBAO->id] = array();
            CRM_Core_DAO::storeValues( $priceFieldBAO, $priceField[$priceFieldBAO->id]);

            // get price if it's a text field
            if ($priceFieldBAO->html_type == 'Text') {
                require_once 'CRM/Core/BAO/CustomOption.php';
                $optionParams = array(
                    'entity_table' => 'civicrm_price_field',
                    'entity_id' => $priceFieldBAO->id
                );
                $optionValues = array();
                CRM_Core_BAO_CustomOption::retrieve( $optionParams, $optionValues );
                $priceField[$priceFieldBAO->id]['price'] = CRM_Utils_Array::value('value',$optionValues);
            }
            $action = array_sum(array_keys($this->actionLinks()));
            if ($priceFieldBAO->is_active) {
                $action -= CRM_Core_Action::ENABLE;
            } else {
                $action -= CRM_Core_Action::DISABLE;
            }

            if ($priceFieldBAO->active_on == '0000-00-00 00:00:00') {
                $priceField[$priceFieldBAO->id]['active_on'] = '';
            }
            if ($priceFieldBAO->expire_on == '0000-00-00 00:00:00') {
                $priceField[$priceFieldBAO->id]['expire_on'] = '';
            }

            $priceField[$priceFieldBAO->id]['action'] = CRM_Core_Action::formLink(self::actionLinks(), $action, 
                                                                                    array('id'  => $priceFieldBAO->id,
                                                                                          'gid' => $this->_gid ));
        }
        $this->assign('priceField', $priceField);
    }


    /**
     * edit price data.
     *
     * editing would involved modifying existing fields + adding data to new fields.
     *
     * @param string  $action    the action to be invoked

     * @return void
     * @access public
     */
    function edit($action)
    {
        // create a simple controller for editing price data
        $controller =& new CRM_Core_Controller_Simple('CRM_Price_Form_Field', ts('Price Field'), $action);

        // set the userContext stack
        $session =& CRM_Core_Session::singleton();
        $session->pushUserContext(CRM_Utils_System::url('civicrm/admin/price/field', 'reset=1&action=browse&gid=' . $this->_gid));
       
        $controller->set('gid', $this->_gid);
        $controller->setEmbedded(true);
        $controller->process();
        $controller->run();
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
        
       
        require_once 'CRM/Core/BAO/PriceSet.php';
        $this->assign( 'dojoIncludes', "dojo.require('dojo.widget.SortableTable');" );

        // get the group id
        $this->_gid = CRM_Utils_Request::retrieve('gid', 'Positive',
                                                  $this);
        $action = CRM_Utils_Request::retrieve('action', 'String',
                                              $this, false, 'browse'); // default to 'browse'
       
        if ($action & CRM_Core_Action::DELETE) {
            
            $session = & CRM_Core_Session::singleton();
            $session->pushUserContext(CRM_Utils_System::url('civicrm/admin/price/field', 'reset=1&action=browse&gid=' . $this->_gid));
            $controller =& new CRM_Core_Controller_Simple( 'CRM_Price_Form_DeleteField',"Delete Price Field",'' );
            $id = CRM_Utils_Request::retrieve('id', 'Positive',
                                              $this, false, 0);
            $controller->set('id', $id);
            $controller->setEmbedded( true );
            $controller->process( );
            $controller->run( );
        }

        if ($this->_gid) {
            $groupTitle = CRM_Core_BAO_PriceSet::getTitle($this->_gid);
            $this->assign('gid', $this->_gid);
            $this->assign('groupTitle', $groupTitle);
            CRM_Utils_System::setTitle(ts('%1 - Price Fields', array(1 => $groupTitle)));
        }

        // get the requested action
        

        // assign vars to templates
        $this->assign('action', $action);

        $id = CRM_Utils_Request::retrieve('id', 'Positive',
                                          $this, false, 0);
        
        // what action to take ?
        if ($action & (CRM_Core_Action::UPDATE | CRM_Core_Action::ADD)) {
            $this->edit($action);   // no browse for edit/update/view
        } else if ($action & CRM_Core_Action::PREVIEW) {
            $this->preview($id) ;
        } else {
            require_once 'CRM/Core/BAO/PriceField.php';
            //require_once 'CRM/Core/BAO/UFField.php';
            if ($action & CRM_Core_Action::DISABLE) {
                CRM_Core_BAO_PriceField::setIsActive($id, 0);
                //CRM_Core_BAO_UFField::setUFField($id, 0);
            } else if ($action & CRM_Core_Action::ENABLE) {
                CRM_Core_BAO_PriceField::setIsActive($id, 1);
                //CRM_Core_BAO_UFField::setUFField($id, 1);
            } 
            $this->browse();
        }

        // Call the parents run method
        parent::run();
    }

    /**
     * Preview price field
     *
     * @param int  $id    price field id
     * 
     * @return void
     * @access public
     */
    function preview($id)
    {
        $controller =& new CRM_Core_Controller_Simple('CRM_Price_Form_Preview', ts('Preview Form Field'), CRM_Core_Action::PREVIEW);
        $session =& CRM_Core_Session::singleton();
        $session->pushUserContext(CRM_Utils_System::url('civicrm/admin/price/field', 'reset=1&action=browse&gid=' . $this->_gid));
        $controller->set('fieldId', $id);
        $controller->setEmbedded(true);
        $controller->process();
        $controller->run();
    }
}
?>
