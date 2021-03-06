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

require_once 'CRM/Contact/Form/Search.php';
require_once "CRM/Core/BAO/Mapping.php";

/**
 * This class if for search builder processing
 */
class CRM_Contact_Form_Search_Builder extends CRM_Contact_Form_Search
{
    
    /**
     * number of columns in where
     *
     * @var int
     * @access protected
     */
    protected $_columnCount;

    /**
     * number of blocks to be shown
     *
     * @var int
     * @access protected
     */
    protected $_blockCount;
    
    /**
     * Function to actually build the form
     *
     * @return None
     * @access public
     */
    public function preProcess() {
        parent::preProcess( );
        //get the block count
        $this->_blockCount = $this->get('blockCount');
        if ( !$this->_blockCount ) {
            $this->_blockCount = 3;
        }

        //get the column count
        $this->_columnCount = array();
        $this->_columnCount = $this->get('columnCount');
        
        for ( $i = 1; $i < $this->_blockCount; $i++ ){
            if ( !$this->_columnCount[$i] ) {
                $this->_columnCount[$i] = 1;
            }
        }

        $this->_loadedMappingId =  $this->get('savedMapping');
    }
    
    public function buildQuickForm( ) {
        //get the saved search mapping id
        $mappingId = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_SavedSearch', $this->_ssID, 'mapping_id' );
            
        CRM_Core_BAO_Mapping::buildMappingForm($this, 'Search Builder', $mappingId, $this->_columnCount, $this->_blockCount);
        
        $this->buildQuickFormCommon();
    }
    

    /**
     * Add local and global form rules
     *
     * @access protected
     * @return void
     */
    function addRules( ) {
        $this->addFormRule( array( 'CRM_Contact_Form_Search_Builder', 'formRule' ) );
    }
    
    /**
     * global validation rules for the form
     *
     * @param array $fields posted values of the form
     *
     * @return array list of errors to be posted back to the form
     * @static
     * @access public
     */
    static function formRule( &$values ) {
        //CRM_Core_Error::debug('s', $values);
        if ( $values['addMore'] || $values['addBlock']) {
            return true;
        }
        require_once 'CRM/Contact/BAO/Contact.php';
        $fields = array ();
        $fields = CRM_Contact_BAO_Contact::exportableFields( 'All', false, true );
        
        require_once 'CRM/Core/Component.php';
        $compomentFields =& CRM_Core_Component::getQueryFields( );
        
        $fields = array_merge( $fields, $compomentFields );

        $fld = array ();
        $fld = CRM_Core_BAO_Mapping::formattedFields($values, true);

        require_once 'CRM/Utils/Type.php';
        $errorMsg = array ();
        foreach ($fld as $k => $v) {
            if ( $v[0] == 'group' ) {
                $grpId = array_keys($v[2]);

                if ( $v[1] == '=') {

                    $error = CRM_Utils_Type::validate( $grpId[0], 'Integer', false );
                    if ( $error != $grpId[0] ) {
                        $errorMsg["value[$v[3]][$v[4]]"] = "Please enter valid group id.";
                    }

                } else if ( $v[1] == 'IN') {
                    foreach ($grpId as $val) {
                        $error = CRM_Utils_Type::validate( $val, 'Integer', false );
                        if ( $error != $val  ) { 
                            $errorMsg["value[$v[3]][$v[4]]"] = "Please enter valid value.";
                            break;
                        }
                    }
                    
                }
            } else {
                if ( substr($v[0], 0, 7) == 'custom_' ) {
                    $type = $fields[$v[0]]['data_type'];
                } else{
                    $fldType = $fields[$v[0]]['type'];
                    $type  = CRM_Utils_Type::typeToString( $fldType );
                }
                
                if ( trim($v[2]) && $type ) {
                    $error = CRM_Utils_Type::validate( $v[2], $type, false );
                    if ( $error != $v[2]  ) {
                        $errorMsg["value[$v[3]][$v[4]]"] = "Please enter valid value.";;
                    }
                }
            }
        }

        if ( !empty($errorMsg) ) {
            return $errorMsg;
        }
        
        return true;

    }    
    
    public function normalizeFormValues( ) {
    }

    public function &convertFormValues( &$formValues ) {
        return CRM_Core_BAO_Mapping::formattedFields( $formValues );
    }

    public function &returnProperties( ) {
        return CRM_Core_BAO_Mapping::returnProperties( $this->_formValues );
    }

    /**
     * Process the uploaded file
     *
     * @return void
     * @access public
     */
    public function postProcess( ) {
        $session =& CRM_Core_Session::singleton();
        $session->set('isAdvanced', '2');
        $session->set('isSearchBuilder', '1');

        $params = $this->controller->exportValues( $this->_name );
        
        if (!empty($params)) {
            if ( $params['addBlock'] )  { 
                $this->_blockCount = $this->_blockCount + 1;
                $this->set( 'blockCount', $this->_blockCount );
                return;
            }
            
            for ($x = 1; $x <= $this->_blockCount; $x++ ) {
                if ( $params['addMore'][$x] )  {
                    $this->_columnCount[$x] = $this->_columnCount[$x] + 1;
                    $this->set( 'columnCount', $this->_columnCount );
                    return;
                }
            }
            
            foreach ($params['mapper'] as $key => $value) {
                foreach ($value as $k => $v) {
                    if ($v[0]) {
                        $checkEmpty++;
                    }
                }
            }
            
            if (!$checkEmpty ) {
                require_once 'CRM/Utils/System.php';            
                CRM_Utils_System::redirect( CRM_Utils_System::url( 'civicrm/contact/search/builder', '_qf_Builder_display=true' ) );
            }
            
        }
        
        // get user submitted values
        // get it from controller only if form has been submitted, else preProcess has set this
        if ( ! empty( $_POST ) ) {
            $this->_formValues = $this->controller->exportValues($this->_name);

            // set the group if group is submitted
            if ($this->_formValues['uf_group_id']) {
                $this->set( 'id', $this->_formValues['uf_group_id'] ); 
            } else {
                $this->set( 'id', '' ); 
            }
            
            // also reset the sort by character 
            $this->_sortByCharacter = null; 
            $this->set( 'sortByCharacter', null ); 
        }

        $this->_params =& $this->convertFormValues( $this->_formValues );
        $this->_returnProperties =& $this->returnProperties( );
        $this->postProcessCommon( );
    }
    
}

?>
