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
 * new version of civicrm apis. See blog post at
 * http://civicrm.org/node/131
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2007
 * $Id: Contribute.php 10014 2007-06-17 21:54:26Z lobo $
 *
 */

require_once 'api/v2/utils.php';

/**
 * Add or update a contribution
 *
 * @param  array   $params           (reference ) input parameters
 *
 * @return array (reference )        contribution_id of created or updated record
 * @static void
 * @access public
 */
function &civicrm_contribution_add( &$params ) {
    _civicrm_initialize( );

    if ( empty( $params ) ) {
        return civicrm_create_error( ts( 'No input parameters present' ) );
    }

    if ( ! is_array( $params ) ) {
        return civicrm_create_error( ts( 'Input parameters is not an array' ) );
    }

    $error = _civicrm_contribute_check_params( $params );
    if ( civicrm_error( $error ) ) {
        return $error;
    }

    $values  = array( );
   
    require_once 'CRM/Contribute/BAO/Contribution.php';
    $error = _civicrm_contribute_format_params( $params, $values );
    if ( civicrm_error( $error ) ) {
        return $error;
    }

    $values["contact_id"] = $params["contact_id"];
    $values["source"]     = $params["source"];
    
    $ids     = array( );
    $contribution = CRM_Contribute_BAO_Contribution::create( $values, $ids );
    if ( is_a( $contribution, 'CRM_Core_Error' ) ) {
        return civicrm_create_error( ts( $contribution->_errors[0]['message'] ) );
    }

    _civicrm_object_to_array($contribution, $contributeArray);
    
    return $contributeArray;
}

/**
 * Retrieve a specific contribution, given a set of input params
 * If more than one contribution exists, return an error, unless
 * the client has requested to return the first found contact
 *
 * @param  array   $params           (reference ) input parameters
 *
 * @return array (reference )        array of properties, if error an array with an error id and error message
 * @static void
 * @access public
 */
function &civicrm_contribution_get( &$params ) {
    _civicrm_initialize( );

    $values = array( );
    if ( empty( $params ) ) {
        return civicrm_create_error( ts( 'No input parameters present' ) );
    }
    
    if ( ! is_array( $params ) ) {
        return civicrm_create_error( ts( 'Input parameters is not an array' ) );
    }

    $contributions =& civicrm_contribution_search( $params );
    if ( civicrm_error( $contributions ) ) {
        return $contributions;
    }

    if ( count( $contributions ) != 1 &&
         ! $params['returnFirst'] ) {
        return civicrm_create_error( ts( '%1 contributions matching input params', array( 1 => count( $contributions ) ) ) );
    }

    $contributions = array_values( $contributions );
    return $contributions[0];
}

/**
 * Delete a contribution
 *
 * @param  array   $params           (reference ) input parameters
 *
 * @return boolean        true if success, else false
 * @static void
 * @access public
 */
function civicrm_contribution_delete( &$params ) {

    $contributionID = CRM_Utils_Array::value( 'contribution_id', $params );
    if ( ! $contributionID ) {
        return civicrm_create_error( ts( 'Could not find contribution_id in input parameters' ) );
    }

    if ( CRM_Contribute_BAO_Contribution::deleteContribution( $contributionID ) ) {
        return civicrm_create_success( );
    } else {
        return civicrm_create_error( ts( 'Could not delete contribution' ) );
    }
}

/**
 * Retrieve a set of contributions, given a set of input params
 *
 * @param  array   $params           (reference ) input parameters
 * @param array    $returnProperties Which properties should be included in the
 *                                   returned Contribution object. If NULL, the default
 *                                   set of properties will be included.
 *
 * @return array (reference )        array of contributions, if error an array with an error id and error message
 * @static void
 * @access public
 */
function &civicrm_contribution_search( &$params ) {
    _civicrm_initialize( );

    $inputParams      = array( );
    $returnProperties = array( );
    $otherVars = array( 'sort', 'offset', 'rowCount' );
    
    $sort     = null;
    $offset   = 0;
    $rowCount = 25;
    foreach ( $params as $n => $v ) {
        if ( substr( $n, 0, 7 ) == 'return.' ) {
            $returnProperties[ substr( $n, 7 ) ] = $v;
        } elseif ( array_key_exists( $n, $otherVars ) ) {
            $$n = $v;
        } else {
            $inputParams[$n] = $v;
        }
    }
    
    require_once 'CRM/Contribute/BAO/Query.php';
    if ( empty( $returnProperties ) ) {
        $returnProperties = CRM_Contribute_BAO_Query::defaultReturnProperties( CRM_Contact_BAO_Query::MODE_CONTRIBUTE );
    }
    
    require_once 'CRM/Contact/BAO/Query.php';
    $newParams =& CRM_Contact_BAO_Query::convertFormValues( $inputParams );
    
    list( $contacts, $options ) = CRM_Contact_BAO_Query::apiQuery( $newParams,
                                                                   $returnProperties,
                                                                   null,
 	                                                               $sort,
                                                                   $offset,
                                                                   $rowCount );
    
    return $contacts;
}

/**
 * This function ensures that we have the right input contribution parameters
 *
 * We also need to make sure we run all the form rules on the params list
 * to ensure that the params are valid
 *
 * @param array  $params       Associative array of property name/value
 *                             pairs to insert in new contribution.
 *
 * @return bool|CRM_Utils_Error
 * @access public
 */
function _civicrm_contribute_check_params( &$params ) {
    static $required = array( 'contact_id', 'total_amount', 'contribution_type_id' );
    
    // cannot create a contribution with empty params
    if ( empty( $params ) ) {
        return civicrm_create_error( 'Input Parameters empty' );
    }

    $valid = true;
    $error = '';
    foreach ( $required as $field ) {
        if ( ! CRM_Utils_Array::value( $field, $params ) ) {
            $valid = false;
            $error .= $field;
            break;
        }
    }
    
    if ( ! $valid ) {
        return civicrm_create_error( "Required fields not found for contribution $error" );
    }
    
    return array();
}

/**
 * take the input parameter list as specified in the data model and 
 * convert it into the same format that we use in QF and BAO object
 *
 * @param array  $params       Associative array of property name/value
 *                             pairs to insert in new contact.
 * @param array  $values       The reformatted properties that we can use internally
 *                            '
 * @return array|CRM_Error
 * @access public
 */
function _civicrm_contribute_format_params( &$params, &$values, $create=false ) {
    // copy all the contribution fields as is
   
    $fields =& CRM_Contribute_DAO_Contribution::fields( );

    static $domainID = null;
    if (!$domainID) {
        $config =& CRM_Core_Config::singleton();
        $domainID = $config->domainID();
    }
    
    _civicrm_store_values( $fields, $params, $values );

    foreach ($params as $key => $value) {
        // ignore empty values or empty arrays etc
        if ( CRM_Utils_System::isNull( $value ) ) {
            continue;
        }

        switch ($key) {

        case 'contribution_contact_id':
            if (!CRM_Utils_Rule::integer($value)) {
                return civicrm_create_error("contact_id not valid: $value");
            }
            $dao =& new CRM_Core_DAO();
            $qParams = array();
            $svq = $dao->singleValueQuery("SELECT id FROM civicrm_contact WHERE domain_id = $domainID AND id = $value",$qParams);
            if (!$svq) {
                return civicrm_create_error("Invalid Contact ID: There is no contact record with contact_id = $value.");
            }
            
            $values['contact_id'] = $values['contribution_contact_id'];
            unset ($values['contribution_contact_id']);
            break;

        case 'receive_date':
        case 'cancel_date':
        case 'receipt_date':
        case 'thankyou_date':
            if (!CRM_Utils_Rule::date($value)) {
                return civicrm_create_error("$key not a valid date: $value");
            }
            break;

        case 'non_deductible_amount':
        case 'total_amount':
        case 'fee_amount':
        case 'net_amount':
            if (!CRM_Utils_Rule::money($value)) {
                return civicrm_create_error("$key not a valid amount: $value");
            }
            break;
        case 'currency':
            if (!CRM_Utils_Rule::currencyCode($value)) {
                return civicrm_create_error("currency not a valid code: $value");
            }
            break;
        case 'contribution_type':            
            $values['contribution_type_id'] = CRM_Utils_Array::key( ucfirst( $value ),
                                                                    CRM_Contribute_PseudoConstant::contributionType( )
                                                                    );
            break;
        case 'payment_instrument': 
            require_once 'CRM/Core/OptionGroup.php';
            $values['payment_instrument_id'] = CRM_Core_OptionGroup::getValue( 'payment_instrument', $value );
            break;
        default:
            break;
        }
    }

    if ( array_key_exists( 'note', $params ) ) {
        $values['note'] = $params['note'];
    }

    _civicrm_custom_format_params( $params, $values, 'Contribution' );
    
    if ( $create ) {
        // CRM_Contribute_BAO_Contribution::add() handles contribution_source
        // So, if $values contains contribution_source, convert it to source
        $changes = array( 'contribution_source' => 'source' );
        
        foreach ($changes as $orgVal => $changeVal) {
            if ( isset($values[$orgVal]) ) {
                $values[$changeVal] = $values[$orgVal];
                unset($values[$orgVal]);
            }
        }
    }
    
    return array();
}

?>