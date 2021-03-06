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
 *
 * @package CRM
 * @author Donald A. Lobo <lobo@yahoo.com>
 * @copyright Donald A. Lobo (c) 2005
 * $Id$
 *
 */


require_once 'CRM/Admin/Form.php';

/**
 * This class generates form components for Gender
 * 
 */
class CRM_Admin_Form_Gender extends CRM_Admin_Form
{
    /**
     * Function to build the form
     *
     * @return None
     * @access public
     */
     function buildQuickForm( ) 
    {
        parent::buildQuickForm( );
        
        if ($this->_action & CRM_CORE_ACTION_DELETE ) { 
            
            return;
        }
        
        $this->applyFilter('__ALL__', 'trim');
        $this->add('text', 'name', ts('Name'), CRM_Core_DAO::getAttribute( 'CRM_Core_DAO_Gender', 'name' ) );
        $this->addRule( 'name', ts('Please enter a valid gender name.'), 'required' );
        $this->addRule( 'name', ts('Name already exists in Database.'), 'objectExists', array( 'CRM_Core_DAO_Gender', $this->_id ) );
        
        $this->add('text', 'weight', ts('Weight'), CRM_Core_DAO::getAttribute('CRM_Core_DAO_Gender', 'weight'), true);
        $this->addRule('weight', ts(' is a numeric field') , 'numeric');
        
        $this->add('checkbox', 'is_active', ts('Enabled?'));

        parent::buildQuickForm( );
    }

       
    /**
     * Function to process the form
     *
     * @access public
     * @return None
     */
     function postProcess() 
    {
        require_once 'CRM/Core/BAO/Gender.php';
        
        if($this->_action & CRM_CORE_ACTION_DELETE) {
            if(CRM_Core_BAO_Gender::del($this->_id)) {
                CRM_Core_Session::setStatus( ts('Selected Gender type has been deleted.') );
            } else {
                CRM_Core_Session::setStatus( ts('Selected Gender type has not been deleted.') );
            }
        } else {
            $params = $ids = array( );
            // store the submitted values in an array
            $params = $this->exportValues();
            
            if ($this->_action & CRM_CORE_ACTION_UPDATE ) {
                $ids['gender'] = $this->_id;
            }

            $gender = CRM_Core_BAO_Gender::add($params, $ids);
            
            CRM_Core_Session::setStatus( ts('The Gender "%1" has been saved.', array( 1 => $gender->name )) );
        }
    }
}

?>
