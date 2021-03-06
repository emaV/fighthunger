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

require_once 'CRM/Contribute/Form/ContributionPage.php';

/**
 * form to process actions on the group aspect of Custom Data
 */
class CRM_Contribute_Form_ContributionPage_Amount extends CRM_Contribute_Form_ContributionPage {
    /** 
     * Constants for number of options for data types of multiple option. 
     */ 
    const NUM_OPTION = 11;
        
    /**
     * Function to actually build the form
     *
     * @return void
     * @access public
     */
    public function buildQuickForm()
    {
        // do u want to allow a free form text field for amount 
        $this->addElement('checkbox', 'is_allow_other_amount', ts('Allow other amounts' ), null, array( 'onclick' => "minMax(this);" ) );  
        $this->add('text', 'min_amount', ts('Minimum Amount'), array( 'size' => 8, 'maxlength' => 8 ) ); 
        $this->addRule( 'min_amount', ts( 'Please enter a valid money value (e.g. 9.99).' ), 'money' );

        $this->add('text', 'max_amount', ts('Maximum Amount'), array( 'size' => 8, 'maxlength' => 8 ) ); 
        $this->addRule( 'max_amount', ts( 'Please enter a valid money value (e.g. 99.99).' ), 'money' );

        $default = array( );
        for ( $i = 1; $i <= self::NUM_OPTION; $i++ ) {
            // label 
            $this->add('text', "label[$i]", ts('Label'), CRM_Core_DAO::getAttribute('CRM_Core_DAO_CustomOption', 'label')); 
 
            // value 
            $this->add('text', "value[$i]", ts('Value'), CRM_Core_DAO::getAttribute('CRM_Core_DAO_CustomOption', 'value')); 
            $this->addRule("value[$i]", ts('Please enter a valid money value for this field (e.g. 99.99).'), 'money'); 

            // default
            $default[] = $this->createElement('radio', null, null, null, $i); 
        }

        $this->addGroup( $default, 'default' );
        
        $this->addElement('checkbox', 'amount_block_is_active', ts('Contribution Amounts section enabled'), null, array( 'onclick' => "amountBlock(this);" ) );

        $this->addElement('checkbox', 'is_monetary', ts('Execute real-time monetary transactions') );


        //check if selected payment processor supports recurring payment
        
        require_once "CRM/Contribute/BAO/ContributionPage.php";
        
        if ( CRM_Contribute_BAO_ContributionPage::checkRecurPaymentProcessor( $this->_id ) ) {
            $this->addElement('checkbox', 'is_recur', ts('Enable recurring payments') );
        }
        
        $this->addFormRule( array( 'CRM_Contribute_Form_ContributionPage_Amount', 'formRule' ) );
        
        parent::buildQuickForm( );
    }

    /** 
     * This function sets the default values for the form. Note that in edit/view mode 
     * the default values are retrieved from the database 
     * 
     * @access public 
     * @return void 
     */ 
    function setDefaultValues() 
    {
        $defaults = parent::setDefaultValues( );
        
        $title = CRM_Core_DAO::getFieldValue( 'CRM_Contribute_DAO_ContributionPage', $this->_id, 'title' );
        CRM_Utils_System::setTitle(ts('Contribution Amounts (%1)', array(1 => $title)));
       
        require_once 'CRM/Core/BAO/CustomOption.php'; 
        CRM_Core_BAO_CustomOption::getAssoc( 'civicrm_contribution_page', $this->_id, $defaults );
        
        if ( CRM_Utils_Array::value( 'value', $defaults ) ) {
            foreach ( $defaults['value'] as $i => $v ) {
                if ( $defaults['amount_id'][$i] == $defaults['default_amount_id'] ) {
                    $defaults['default'] = $i;
                    break;
                }
            }
        }
        
        return $defaults;
    }
    
    
    /**  
     * global form rule  
     *  
     * @param array $fields  the input form values  
     * @param array $files   the uploaded files if any  
     * @param array $options additional user data  
     *  
     * @return true if no errors, else array of errors  
     * @access public  
     * @static  
     */  
    static function formRule( &$fields, &$files, $options ) {  
        $errors = array( );  

        $minAmount = CRM_Utils_Array::value( 'min_amount', $fields );
        $maxAmount = CRM_Utils_Array::value( 'max_amount', $fields );
        if ( ! empty( $minAmount) && ! empty( $maxAmount ) ) {
            $minAmount = CRM_Utils_Rule::cleanMoney( $minAmount );
            $maxAmount = CRM_Utils_Rule::cleanMoney( $maxAmount );
            if ( (float ) $minAmount > (float ) $maxAmount ) {
                $errors['min_amount'] = ts( 'Minimum Amount should be less than Maximum Amount' );
            }
        }

        return $errors;
    }
 
    /**
     * Process the form
     *
     * @return void
     * @access public
     */
    public function postProcess()
    {
        // get the submitted form values.
        $params = $this->controller->exportValues( $this->_name );

        $params['id']                    = $this->_id;
        $params['domain_id']             = CRM_Core_Config::domainID( );
        $params['is_allow_other_amount'] = CRM_Utils_Array::value('is_allow_other_amount', $params, false);
        
        $params['min_amount'] = CRM_Utils_Rule::cleanMoney( $params['min_amount'] );
        $params['max_amount'] = CRM_Utils_Rule::cleanMoney( $params['max_amount'] );
        
        require_once 'CRM/Core/DAO/CustomOption.php';
            
        // delete all the prior label values in the custom options table
        $dao =& new CRM_Core_DAO_CustomOption( );
        $dao->entity_table = 'civicrm_contribution_page'; 
        $dao->entity_id    = $this->_id; 
        $dao->delete( );
        
        // if there are label / values, create custom options for them
        $labels  = CRM_Utils_Array::value( 'label'  , $params );
        $values  = CRM_Utils_Array::value( 'value'  , $params );

        $default = CRM_Utils_Array::value( 'default', $params ); 
        $params['amount_block_is_active']  = CRM_Utils_Array::value( 'amount_block_is_active', $params ,false);
        $params['is_monetary']  = CRM_Utils_Array::value( 'is_monetary', $params ,false);
        $params['is_recur']  = CRM_Utils_Array::value( 'is_recur', $params ,false);
        if ( ! CRM_Utils_System::isNull( $values ) ) {
            for ( $i = 1; $i < self::NUM_OPTION; $i++ ) {
                if ( isset( $values[$i] ) &&
                     ( strlen( trim( $values[$i] ) ) > 0 ) ) {
                    $dao =& new CRM_Core_DAO_CustomOption( );
                    $dao->label        = trim( CRM_Utils_Array::value( $i, $labels ) );
                    $dao->value        = CRM_Utils_Rule::cleanMoney( trim( $values[$i] ) );
                    $dao->entity_table = 'civicrm_contribution_page';
                    $dao->entity_id    = $this->_id;
                    $dao->weight       = $i;
                    $dao->is_active    = 1;
                    $dao->save( );
                    if ( $default == $i ) {
                        $params['default_amount_id'] = $dao->id;
                    }
                }
            }
        }
        
        require_once 'CRM/Contribute/BAO/ContributionPage.php';
        $dao = CRM_Contribute_BAO_ContributionPage::create( $params );
    }
    
    /** 
     * Return a descriptive name for the page, used in wizard header 
     * 
     * @return string 
     * @access public 
     */ 
    public function getTitle( ) {
        return ts( 'Amounts' );
    }
    
}
?>
