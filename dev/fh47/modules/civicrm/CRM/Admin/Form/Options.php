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

require_once 'CRM/Admin/Form.php';
require_once 'CRM/Core/BAO/OptionValue.php';
require_once 'CRM/Core/BAO/OptionGroup.php';

/**
 * This class generates form components for Options
 * 
 */
class CRM_Admin_Form_Options extends CRM_Admin_Form
{

    /**
     * The option group name
     *
     * @var array
     * @static
     */
    protected $_gName;

    /**
     * The option group name in display format (capitalized, without underscores...etc)
     *
     * @var array
     * @static
     */
    protected $_GName;

    /**
     * Function to pre-process
     *
     * @return None
     * @access public
     */
    public function preProcess( ) {
        parent::preProcess( );

        if ( ! $this->_gName ) {
            $this->_gName = CRM_Utils_Request::retrieve('group','String', $this, false, 0);
            $this->_gid   = CRM_Core_DAO::getFieldValue( 'CRM_Core_DAO_OptionGroup', $this->_gName, 'id', 'name');
        }
        if ($this->_gName) {
            $this->set( 'gName', $this->_gName );
        } else {
            $this->_gName = $this->get( 'gName' );
        }
        $this->_GName = ucwords(str_replace('_', ' ', $this->_gName));
    }
    
    /**
     * This function sets the default values for the form. 
     * the default values are retrieved from the database
     * 
     * @access public
     * @return None
     */
    function setDefaultValues( ) {
        $defaults = parent::setDefaultValues( );
        
        if (! isset($defaults['weight']) || ! $defaults['weight']) {
            $fieldValues = array('option_group_id' => $this->_gid);
            $defaults['weight'] = CRM_Utils_Weight::getDefaultWeight('CRM_Core_DAO_OptionValue', $fieldValues);
        }
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
        
        $this->applyFilter('__ALL__', 'trim');
        $this->add('text', 'label', ts('Label'), CRM_Core_DAO::getAttribute( 'CRM_Core_DAO_OptionValue', 'label' ),true );
        $this->addRule( 'label', ts('Name already exists in Database.'), 'optionExists', array( 'CRM_Core_DAO_OptionValue', $this->_id, $this->_gid ) );
        
        $this->add('text', 'description', ts('Description'), CRM_Core_DAO::getAttribute( 'CRM_Core_DAO_OptionValue', 'description' ) );
        
        $this->add('text', 'weight', ts('Weight'), CRM_Core_DAO::getAttribute('CRM_Core_DAO_OptionValue', 'weight'), true);
        $this->addRule('weight', ts(' is a numeric field') , 'numeric');
        
        $this->add('checkbox', 'is_active', ts('Enabled?'));
    }

       
    /**
     * Function to process the form
     *
     * @access public
     * @return None
     */
    public function postProcess() 
    {
        if($this->_action & CRM_Core_Action::DELETE) {
            $fieldValues = array('option_group_id' => $this->_gid);
            $wt = CRM_Utils_Weight::delWeight('CRM_Core_DAO_OptionValue', $this->_id, $fieldValues);
            
            if( CRM_Core_BAO_OptionValue::del($this->_id) ) {
                CRM_Core_Session::setStatus( ts('Selected %1 type has been deleted.', array(1 => $this->_GName)) );
            } else {
                CRM_Core_Session::setStatus( ts('Selected %1 type has not been deleted.', array(1 => $this->_GName)) );
                CRM_Utils_Weight::correctDuplicateWeights('CRM_Core_DAO_OptionValue', $fieldValues);
            }
        } else {
            $params = $ids = array( );
            $params = $this->exportValues();

            $groupParams = array( 'name' => ($this->_gName) );

            require_once 'CRM/Core/OptionValue.php';
            $optionValue = CRM_Core_OptionValue::addOptionValue($params, $groupParams, $this->_action, $this->_id);

            CRM_Core_Session::setStatus( ts('The %1 "%2" has been saved.', array(1 => $this->_GName, 2 => $optionValue->label)) );
        }
    }
}

?>
