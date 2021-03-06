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
            if ( (! isset ($this->_columnCount[$i] ) ) || (! $this->_columnCount[$i]) )  {
                $this->_columnCount[$i] = 1;
            }
        }
        
        $this->_loadedMappingId =  $this->get('savedMapping');
    }
    
    public function buildQuickForm( ) {
        //get the saved search mapping id
        $mappingId = null;
            if ( $this->_ssID ) {
                $mappingId = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_SavedSearch', $this->_ssID, 'mapping_id' );
            }

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
        if ( CRM_Utils_Array::value('addMore',$values) || CRM_Utils_Array::value('addBlock',$values) ) {
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
            if ( !$v[1] ) {
                $errorMsg["operator[$v[3]][$v[4]]"] = "Please enter the operator.";  
            } else {
                if ( $v[0] == 'group' || $v[0] == 'tag' ) {
                    $grpId = array_keys($v[2]);
                    if( ! key($v[2]) ) {
                        $errorMsg["value[$v[3]][$v[4]]"] = "Please enter the value.";  
                    }
                  
                    if ( count($grpId) > 1) { 
                        if ( $v[1] !='IN' && $v[1] != 'NOT IN' ) {
                            $errorMsg["value[$v[3]][$v[4]]"] = "Please enter the valid value.";  
                        }
                        foreach ($grpId as $val) {
                            $error = CRM_Utils_Type::validate( $val, 'Integer', false );
                            if ( $error != $val  ) { 
                                $errorMsg["value[$v[3]][$v[4]]"] = "Please enter valid value.";
                                break;
                            }
                        }
                    } else {
                        $error = CRM_Utils_Type::validate( $grpId[0], 'Integer', false );
                        if ( $error != $grpId[0] ) {
                            $errorMsg["value[$v[3]][$v[4]]"] = "Please enter valid $v[0] id.";
                        }
                    }
                } else if ( substr($v[0], 0, 7) === 'do_not_' or substr($v[0], 0, 3) === 'is_' ) { 
                    $v2 = array($v[2]);
                    if( !isset($v[2]) ) {
                        $errorMsg["value[$v[3]][$v[4]]"] = "Please enter the value.";  
                    }

                    $error = CRM_Utils_Type::validate($v2[0] , 'Integer', false );
                    if ( $error != $v2[0] ) {
                        $errorMsg["value[$v[3]][$v[4]]"] = "Please enter valid value.";  
                    }
                } else {
                    if ( substr($v[0], 0, 7) == 'custom_' ) {
                        $type = $fields[$v[0]]['data_type'];
                    } else {
                        $fldName = $v[0];
                        if ( substr( $v[0], 0, 13 ) == 'contribution_' ) {
                            $fldName = substr($v[0], 13 );
                        }
                        
                        $fldType = CRM_Utils_Array::value('type',$fields[$fldName]);
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
            if ( CRM_Utils_Array::value('addBlock',$params) )  { 
                $this->_blockCount = $this->_blockCount + 1;
                $this->set( 'blockCount', $this->_blockCount );
                return;
            }
            
            for ($x = 1; $x <= $this->_blockCount; $x++ ) {
                if ( CRM_Utils_Array::value($x,$params['addMore']) ) {
                    $this->_columnCount[$x] = $this->_columnCount[$x] + 1;
                    $this->set( 'columnCount', $this->_columnCount );
                    return;
                }
            }
            
            $checkEmpty = null;
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
            if ( CRM_Utils_Array::value('uf_group_id',$this->_formValues) ) {
                $this->set( 'id', $this->_formValues['uf_group_id'] ); 
            } else {
                $this->set( 'id', '' ); 
            }
            
            // also reset the sort by character 
            $this->_sortByCharacter = null; 
            $this->set( 'sortByCharacter', null ); 
        }

        // we dont want to store the sortByCharacter in the formValue, it is more like 
        // a filter on the result set
        // this filter is reset if we click on the search button
        if ( $this->_sortByCharacter && empty( $_POST ) ) {
            if ( $this->_sortByCharacter == 1 ) {
                $this->_formValues['sortByCharacter'] = null;
            } else {
                $this->_formValues['sortByCharacter'] = $this->_sortByCharacter;
            }
        }

        $this->_params =& $this->convertFormValues( $this->_formValues );
        $this->_returnProperties =& $this->returnProperties( );
        $this->postProcessCommon( );
    }
    
}

?>
