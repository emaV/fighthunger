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
 *
 * @package CRM
 * @author Donald A. Lobo <lobo@yahoo.com>
 * @copyright CiviCRM LLC (c) 2004-2006
 * $Id$
 *
 */

require_once 'CRM/Admin/Form.php';

/**
 * This class generates form components for Option Value
 * 
 */
class CRM_Admin_Form_OptionValue extends CRM_Admin_Form
{
    static $_gid = null;
    
    /**
     * Function to for pre-processing
     *
     * @return None
     * @access public
     */
    public function preProcess( ) 
    {
        parent::preProcess( );
        require_once 'CRM/Utils/Request.php';
        $this->_gid = CRM_Utils_Request::retrieve('gid', 'Positive',
                                                  $this, false, 0);
        $session =& CRM_Core_Session::singleton();
        $url = CRM_Utils_System::url('civicrm/admin/optionValue', 'reset=1&action=browse&gid='.$this->_gid); 
        $session->pushUserContext( $url );
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

        $this->applyFilter('__ALL__', 'trim');
        $this->add('text', 'label', ts('Title'), CRM_Core_DAO::getAttribute( 'CRM_Core_DAO_OptionValue', 'label' ) );
        $this->addRule( 'label', ts('Please enter a valid Option Group title.'), 'required' );
        

        $this->add('text', 'name', ts('Name'), CRM_Core_DAO::getAttribute( 'CRM_Core_DAO_OptionValue', 'name' ) );
        $this->addRule( 'name', ts('Please enter a valid Option Group name.'), 'required' );
        
        
        $this->add('text', 'description', ts('Description'), CRM_Core_DAO::getAttribute( 'CRM_Core_DAO_OptionValue', 'description' ) );

        $this->add('text', 'grouping' ,  ts('Option Grouping Name'),CRM_Core_DAO::getAttribute( 'CRM_Core_DAO_OptionValue', 'grouping' ) );
        $this->add('text', 'weight', ts('Weight'), CRM_Core_DAO::getAttribute( 'CRM_Core_DAO_OptionValue', 'wieght' ) );
        $this->addRule( 'weight', ts('Please enter a value for weight'), 'required' );

        $this->add('checkbox', 'is_active', ts('Enabled?'));
        
        $this->add('checkbox', 'is_default', ts('Default?'));
        
        $this->add('checkbox', 'is_optgroup',ts('Opt Group?'));
        
        if ($this->_action == CRM_Core_Action::UPDATE && CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_OptionValue', $this->_id, 'is_reserved' )) { 
            $this->freeze(array('name', 'description', 'is_active' ));
        }
        
    }

       
    /**
     * Function to process the form
     *
     * @access public
     * @return None
     */
    public function postProcess() 
    {
        $params = $this->exportValues();
        require_once 'CRM/Core/BAO/OptionValue.php';
        if($this->_action & CRM_Core_Action::DELETE) {
            CRM_Core_BAO_OptionValue::del($this->_id);
            CRM_Core_Session::setStatus( ts('Selected option value has been deleted.') );
        } else { 

            $params = $ids = array( );
            // store the submitted values in an array
            $params = $this->exportValues();
            $params['option_group_id'] = $this->_gid;

            if ($this->_action & CRM_Core_Action::UPDATE ) {
                $ids['optionValue'] = $this->_id;
            }
            
            $optionValue = CRM_Core_BAO_OptionValue::add($params, $ids);
            CRM_Core_Session::setStatus( ts('The Option Value "%1" has been saved.', array( 1 => $optionValue->label )) );
        }
        
    }
}

?>
