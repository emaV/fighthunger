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

require_once 'CRM/Contribute/Form/ContributionBase.php';

/**
 * form to process actions on the group aspect of Custom Data
 */
class CRM_Contribute_Form_Contribution_Confirm extends CRM_Contribute_Form_ContributionBase {

    /**
     * Function to set variables up before form is built
     *
     * @return void
     * @access public
     */
    public function preProcess()
    {
        $config =& CRM_Core_Config::singleton( );

        parent::preProcess( );

        if ( $this->_contributeMode == 'express' ) {
            // rfp == redirect from paypal
            $rfp = CRM_Utils_Request::retrieve( 'rfp', 'Boolean',
                                                CRM_Core_DAO::$_nullObject, false, null, 'GET' );
            if ( $rfp ) {
                require_once 'CRM/Contribute/Payment.php'; 
                $payment =& CRM_Contribute_Payment::singleton( $this->_mode );
                $this->_params = $payment->getExpressCheckoutDetails( $this->get( 'token' ) );

                // fix state and country id if present
                if ( CRM_Utils_Array::value( 'state_province', $this->_params ) ) {
                    $states =& CRM_Core_PseudoConstant::stateProvinceAbbreviation();
                    $states = array_flip( $states );
                    $this->_params['state_province_id'] = CRM_Utils_Array::value( $this->_params['state_province'], $states );
                }
                if ( CRM_Utils_Array::value( 'country', $this->_params ) ) {
                    $states =& CRM_Core_PseudoConstant::countryIsoCode();
                    $states = array_flip( $states );
                    $this->_params['country_id'] = CRM_Utils_Array::value( $this->_params['country'], $states );
                }

                // set a few other parameters for PayPal
                $this->_params['token']          = $this->get( 'token' );

                $this->_params['amount'        ] = $this->get( 'amount' );
                $this->_params['currencyID'    ] = $config->defaultCurrency;
                $this->_params['payment_action'] = 'Sale';

                // also merge all the other values from the profile fields
                $values = $this->controller->exportValues( 'Main' );
                $skipFields = array( 'amount', 'amount_other',
                                     'first_name', 'middle_name', 'last_name',
                                     'street_address', 'city', 'state_province_id', 'postal_code',
                                     'country_id' );
                foreach ( $values as $name => $value ) {
                    // skip amount field
                    if ( ! in_array( $name, $skipFields ) ) {
                        $this->_params[$name] = $value;
                    }
                }
                $this->set( 'getExpressCheckoutDetails', $this->_params );
            } else {
                $this->_params = $this->get( 'getExpressCheckoutDetails' );
            }
        } else {
            $this->_params = $this->controller->exportValues( 'Main' );

            $this->_params['state_province'] = CRM_Core_PseudoConstant::stateProvinceAbbreviation( $this->_params['state_province_id'] ); 
            $this->_params['country']        = CRM_Core_PseudoConstant::countryIsoCode( $this->_params['country_id'] ); 
            $this->_params['year'   ]        = $this->_params['credit_card_exp_date']['Y'];  
            $this->_params['month'  ]        = $this->_params['credit_card_exp_date']['M'];  
            $this->_params['ip_address']     = $_SERVER['REMOTE_ADDR']; 

            $this->_params['amount'        ] = $this->get( 'amount' );
            $this->_params['currencyID'    ] = $config->defaultCurrency;
            $this->_params['payment_action'] = 'Sale';
        }

        $this->_params['invoiceID'] = $this->get( 'invoiceID' );

        $this->set( 'params', $this->_params );
    }

    /**
     * Function to actually build the form
     *
     * @return void
     * @access public
     */
    public function buildQuickForm()
    {
        $this->assignToTemplate( );
        require_once 'CRM/Contribute/BAO/Premium.php';
        $amount = $this->get( 'amount' );
        $params = $this->_params;
        
        $amount_block_is_active = $this->get( 'amount_block_is_active');
        $this->assign('amount_block_is_active', $amount_block_is_active );
        
        if ( $params['selectProduct'] && $params['selectProduct'] != 'no_thanks') {
            $option    = $params['options_'.$params['selectProduct']];
            $productID = $params['selectProduct']; 
            CRM_Contribute_BAO_Premium::buildPremiumBlock( $this , $this->_id ,false,$productID, $option);
            $this->set('productID',$productID);
            $this->set('option',$option);
        }
        $config =& CRM_Core_Config::singleton( );
        if ( in_array("CiviMember", $config->enableComponents) ) {
            if ($params['selectMembership'] && $params['selectMembership'] != 'no_thanks') {
                CRM_Member_BAO_Membership::buildMembershipBlock( $this , $this->_id ,false , $params['selectMembership'] );
            }
        }
        $this->addButtons(array(
                                array ( 'type'      => 'next',
                                        'name'      => ts('Make Contribution'),
                                        'spacing'   => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                                        'isDefault' => true,
                                        'js'        => array( 'onclick' => "return submitOnce(this,'Confirm','" . ts('Processing') ."');" ) ),
                                array ( 'type'      => 'back',
                                        'name'      => ts('<< Go Back')),
                                )
                          );

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
        $defaults = array();
        return $defaults;
    }

    /**
     * Process the form
     *
     * @return void
     * @access public
     */
    public function postProcess()
    {
        //$contactID = $this->get( 'contactID' );
        $session =& CRM_Core_Session::singleton( );
        $contactID = $session->get( 'userID' );
        
        $premiumParams = $membershipParams = $tempParams = $params = $this->_params;
        if ( ! $contactID ) {
            // make a copy of params so we dont destroy our params
            // (since we pass this by reference)
            // so now we have a confirmed financial transaction
            // lets create or update a contact first
            require_once 'api/crm.php';
            $ids = CRM_Core_BAO_UFGroup::findContact( $params );
            $contactsIDs = explode( ',', $ids );
            
            // if we find more than one contact, use the first one
            $contact_id  = $contactsIDs[0];
            $contact = null;
            if ( $contact_id ) {
                $contact =& crm_get_contact( array( 'contact_id' => $contact_id ) );
            }

            $ids = array( );
            if ( ! $contact || ! is_a( $contact, 'CRM_Contact_BAO_Contact' ) ) {
                $contact =& CRM_Contact_BAO_Contact::createFlat( $params, $ids );
            } else {
                // need to fix and unify all contact creation
                $idParams = array( 'id' => $contact_id, 'contact_id' => $contact_id );
                $defaults = array( );
                CRM_Contact_BAO_Contact::retrieve( $idParams, $defaults, $ids );
                $contact =& CRM_Contact_BAO_Contact::createFlat( $params, $ids );
            }
            
            if ( is_a( $contact, 'CRM_Core_Error' ) ) {
                CRM_Core_Error::fatal( "Failed creating contact for contributor" );
            }

            $contactID = $contact->id;

            $this->set( 'contactID', $contactID );
        } else {
            $idParams = array( 'id' => $contactID, 'contact_id' => $contactID );
            $defaults = array( );
            CRM_Contact_BAO_Contact::retrieve( $idParams, $defaults, $ids );
            $contact =& CRM_Contact_BAO_Contact::createFlat( $params, $ids );
        }

        if ( $membershipParams['selectMembership'] &&  $membershipParams['selectMembership'] != 'no_thanks') {
            $paymemtDone = false;
            
            $this->assign('membership_assign' , true );
            $this->set('membershipID' , $membershipParams['selectMembership']);
            
            require_once 'CRM/Member/BAO/MembershipType.php';
            require_once 'CRM/Member/BAO/Membership.php';
            $membersshipID = $membershipParams['selectMembership'];
            $membersshipDetails = CRM_Member_BAO_MembershipType::getMembershipTypeDetails( $membersshipID );
            $this->assign('membership_name',$membersshipDetails['name']);

            $minimumFee = $membersshipDetails['minimum_fee'];
            $memBlockDetails    = CRM_Member_BAO_Membership::getMemershipBlock( $this->id );
            $contributionType =& new CRM_Contribute_DAO_ContributionType( );
            if ( $this->_values['amount_block_is_active']) {
                 $contributionType->id = $this->_values['contribution_type_id'];
            } else {
                $paymemtDone  = true ;
                $membershipParams['amount'] = $minimumFee;
                $contributionType->id = $membersshipDetails['contribution_type_id']; 
            }
            if ( ! $contributionType->find( true ) ) {
                CRM_Core_Error::fatal( "Could not find a system table" );
            }
            $membershipParams['contributionType_name'] = $contributionType->name;
            $membershipParams['contributionType_accounting_code'] = $contributionType->accounting_code;
            $membershipParams['contributionForm_id']              = $this->_values['id'];
            
            require_once 'CRM/Contribute/Payment.php';
            $payment =& CRM_Contribute_Payment::singleton( $this->_mode );
            
            if ( $this->_contributeMode == 'express' ) {
                $result =& $payment->doExpressCheckout( $membershipParams);
            } else {
                $result =& $payment->doDirectPayment( $membershipParams );
            }

            $errors = array();
            if ( is_a( $result, 'CRM_Core_Error' ) ) {
                $errors[1] = $result;
            } else {
                $now = date( 'YmdHis' );
                $membershipParams = array_merge($membershipParams, $result );
                $membershipParams['receive_date'] = $now;
                $this->set( 'params', $membershipParams );
                $this->assign( 'trxn_id', $result['trxn_id'] );
                $this->assign( 'receive_date',
                               CRM_Utils_Date::mysqlToIso( $membershipParams['receive_date']) );

                $config =& CRM_Core_Config::singleton( );
                if ( $contributionType->is_deductible ) {
                    $this->assign('is_deductible' , true );
                    $this->set('is_deductible' , true);
                }
                $contribution[1] =  self::processContribution( $membershipParams ,$result ,$contactID ,$contributionType ,true );
                self::postProcessPremium( $premiumParams ,$contribution[1] );
                           
            }

            if ( $memBlockDetails['is_separate_payment']  && ! $paymemtDone ) {
                $contributionType =& new CRM_Contribute_DAO_ContributionType( );
                $contributionType->id = $membersshipDetails['contribution_type_id']; 
                if ( ! $contributionType->find( true ) ) {
                    CRM_Core_Error::fatal( "Could not find a system table" );
                }
                $tempParams['amount'] = $minimumFee;
                $invoiceID = md5(uniqid(rand(), true));
                $tempParams['invoiceID'] = $invoiceID;
                if ( $this->_contributeMode == 'express' ) {
                    $result =& $payment->doExpressCheckout( $tempParams );
                } else {
                    $result =& $payment->doDirectPayment( $tempParams );
                }
                if ( is_a( $result, 'CRM_Core_Error' ) ) {
                    $errors[2] = $result;
                } else {
                    $this->set('membership_trx_id' , $result['trxn_id']);
                    $this->set('membership_amount'  , $minimumFee);
                    
                    $this->assign('membership_trx_id' , $result['trxn_id']);
                    $this->assign('membership_amount'  , $minimumFee);
                    $contribution[2] =  self::processContribution( $tempParams ,$result ,$contactID ,$contributionType ,false );
                }
            }
            if ( $memBlockDetails['is_separate_payment'] ) {
                $index = 2;
            } else {
                $index = 1;
            }
            if ( ! $errors[$index] ){
                if ( $currentMembership = CRM_Member_BAO_Membership::getContactMembership($contactID , $membersshipID) ) {
                    $this->set("renewal_mode", true );
                    if ( ! $currentMembership['is_current_member'] ) {
                        require_once 'CRM/Member/BAO/MembershipStatus.php';
                        $dao = &new CRM_Member_DAO_Membership();
                        $dates = CRM_Member_BAO_MembershipType::getRenewalDatesForMembershipType( $currentMembership['id']);
                        $currentMembership['start_date'] = CRM_Utils_Date::customFormat($dates['start_date'],'%Y%m%d');
                        $currentMembership['end_date']   = CRM_Utils_Date::customFormat($dates['end_date'],'%Y%m%d');
                        $currentMembership['source']     = ts( 'Online Contribution:' ) . ' ' . $this->_values['title'];
                        $dao->copyValues($currentMembership);
                        $membership = $dao->save();
                        
                        //insert log here 
                        require_once 'CRM/Member/DAO/MembershipLog.php';
                        $dao = new CRM_Member_DAO_MembershipLog();
                        $dao->membership_id = $membership->id;
                        $dao->status_id     = $membership->status_id;
                        $dao->start_date    = CRM_Utils_Date::customFormat($dates['start_date'],'%Y%m%d');
                        $dao->end_date      = CRM_Utils_Date::customFormat($dates['end_date'],'%Y%m%d'); 
                        $dao->modified_id   = $contactID;
                        $dao->modified_date = date('Ymd');
                        $dao->save();

                        $this->assign('mem_start_date' , CRM_Utils_Date::customFormat($dates['start_date'],'%Y%m%d'));
                        $this->assign('mem_end_date', CRM_Utils_Date::customFormat($dates['end_date'],'%Y%m%d'));
                        
                    } else {
                        require_once 'CRM/Member/BAO/MembershipStatus.php';
                        $dao = &new CRM_Member_DAO_Membership();
                        $dao->id = $currentMembership['id'];
                        $dao->find(true); 
                        $membership = $dao ;

                        //insert log here 
                        require_once 'CRM/Member/DAO/MembershipLog.php';
                        $dates = CRM_Member_BAO_MembershipType::getRenewalDatesForMembershipType( $membership->id);
                        $dao = new CRM_Member_DAO_MembershipLog();
                        $dao->membership_id = $membership->id;
                        $dao->status_id     = $membership->status_id;
                        $dao->start_date    = CRM_Utils_Date::customFormat($dates['log_start_date'],'%Y%m%d');
                        $dao->end_date      = CRM_Utils_Date::customFormat($dates['end_date'],'%Y%m%d'); 
                        $dao->modified_id   = $contactID;
                        $dao->modified_date = date('Ymd');
                        $dao->save();
                        $this->assign('mem_start_date' , CRM_Utils_Date::customFormat($dates['start_date'],'%Y%m%d'));
                        $this->assign('mem_end_date', CRM_Utils_Date::customFormat($dates['end_date'],'%Y%m%d'));
                        
                    }
            
                } else {
                    require_once 'CRM/Member/BAO/MembershipStatus.php';
                    $memParams = array();
                    $memParams['contact_id']             = $contactID;
                    $memParams['membership_type_id']     = $membersshipID;
                    $dates = CRM_Member_BAO_MembershipType::getDatesForMembershipType($membersshipID);
                  
                    $memParams['join_date']  = CRM_Utils_Date::customFormat($dates['join_date'],'%Y%m%d');
                    $memParams['start_date'] = CRM_Utils_Date::customFormat($dates['start_date'],'%Y%m%d');
                    $memParams['end_date']   = CRM_Utils_Date::customFormat($dates['end_date'],'%Y%m%d');
                    $memParams['source'  ]   = ts( 'Online Contribution:' ) . ' ' . $this->_values['title'];
                    $status = CRM_Member_BAO_MembershipStatus::getMembershipStatusByDate( CRM_Utils_Date::customFormat($dates['start_date'],'%Y-%m-%d'),CRM_Utils_Date::customFormat($dates['end_date'],'%Y-%m-%d'),CRM_Utils_Date::customFormat($dates['join_date'],'%Y-%m-%d')) ;
                   
                    $memParams['status_id']   = $status['id'];
                    $memParams['is_override'] = false;
                    $dao = &new CRM_Member_DAO_Membership();
                    $dao->copyValues($memParams);
                    $membership = $dao->save();
                    $this->assign('mem_start_date' , CRM_Utils_Date::customFormat($dates['start_date'],'%Y%m%d'));
                    $this->assign('mem_end_date', CRM_Utils_Date::customFormat($dates['end_date'],'%Y%m%d'));
                }
                //insert payment record
                require_once 'CRM/Member/DAO/MembershipPayment.php';
                $dao = &new CRM_Member_DAO_MembershipPayment();    
                $dao->membership_id         =  $membership->id;
                $dao->payment_entity_table  = 'civicrm_contribute';
                $dao->payment_entity_id     = $contribution[$index]->id;
                $dao->save();
            }
            
            foreach($errors as $error ) {
                CRM_Core_Error::displaySessionError( $error );
                CRM_Utils_System::redirect( CRM_Utils_System::url( 'civicrm/contribute/transact', '_qf_Main_display=true' ) );
            }
            
            //finally send an email receipt
            if ( !$errors[1]  &&  !$errors[2] ) {
                self::sendMail( $contactID );
            }
        } else {
            $contributionType =& new CRM_Contribute_DAO_ContributionType( );
            $contributionType->id = $this->_values['contribution_type_id'];
            if ( ! $contributionType->find( true ) ) {
                CRM_Core_Error::fatal( "Could not find a system table" );
            }
            
            // add some contribution type details to the params list
            // if folks need to use it
            $this->_params['contributionType_name']            = $contributionType->name;
            $this->_params['contributionType_accounting_code'] = $contributionType->accounting_code;
            $this->_params['contributionForm_id']              = $this->_values['id'];
            
            require_once 'CRM/Contribute/Payment.php';
            $payment =& CRM_Contribute_Payment::singleton( $this->_mode );
            
            if ( $this->_contributeMode == 'express' ) {
                       $result =& $payment->doExpressCheckout( $this->_params );
                     } else {
                         $result =& $payment->doDirectPayment( $this->_params );
               }
            
            if ( is_a( $result, 'CRM_Core_Error' ) ) {
                CRM_Core_Error::displaySessionError( $result );
                CRM_Utils_System::redirect( CRM_Utils_System::url( 'civicrm/contribute/transact', '_qf_Main_display=true' ) );
            }
            
            $now = date( 'YmdHis' );
            
            $this->_params = array_merge( $this->_params, $result );
            $this->_params['receive_date'] = $now;
            $this->set( 'params', $this->_params );
            $this->assign( 'trxn_id', $result['trxn_id'] );
            $this->assign( 'receive_date',
                           CRM_Utils_Date::mysqlToIso( $this->_params['receive_date']) );
            
            // result has all the stuff we need
            // lets archive it to a financial transaction
            $config =& CRM_Core_Config::singleton( );
            if ( $contributionType->is_deductible ) {
                $this->assign('is_deductible' , true );
                $this->set('is_deductible' , true);
            }
            
            $contribution =  self::processContribution( $this->_params ,$result ,$contactID ,$contributionType , true );
            
            self::postProcessPremium( $premiumParams ,$contribution );
            
        
            // finally send an email receipt
            self::sendMail( $contactID );
        }
    
    }
    
    /**
     * Process the form
     *
     * @return void
     * @access public
     */
    public function postProcessPremium( $premiumParams ,$contribution )
    {
        // assigning Premium information to receipt tpl
        if ( $premiumParams['selectProduct'] && $premiumParams['selectProduct'] != 'no_thanks') {
            $startDate = $endDate = "";
            $this->assign('selectPremium' , true );
            require_once 'CRM/Contribute/DAO/Product.php';
            $productDAO =& new CRM_Contribute_DAO_Product();
            $productDAO->id = $premiumParams['selectProduct'];
            $productDAO->find(true);
            $this->assign('product_name' , $productDAO->name );
            $this->assign('price', $productDAO->price);
            $this->assign('sku', $productDAO->sku);
            $this->assign('option',$premiumParams['options_'.$premiumParams['selectProduct']]);
            
            $periodType = $productDAO->period_type;
            
            if ( $periodType ) {
                $fixed_period_start_day = $productDAO->fixed_period_start_day;
                $duration_unit          = $productDAO->duration_unit;
                $duration_interval      = $productDAO->duration_interval;
                if ( $periodType == 'rolling' ) {
                    $startDate = date('Y-m-d');
                } else if ($periodType == 'fixed') {
                    if ( $fixed_period_start_day ) {
                        $date  = explode('-', date('Y-m-d') );
                        $month     = substr( $fixed_period_start_day, 0, strlen($fixed_period_start_day)-2);
                        $day       = substr( $fixed_period_start_day,-2)."<br>";
                        $year      = $date[0];
                        $startDate = $year.'-'.$month.'-'.$day;
                    } else {
                        $startDate = date('Y-m-d');
                    }
                }
                
                $date  = explode('-', $startDate );
                $year  = $date[0];
                $month = $date[1];
                $day   = $date[2];
                
                switch ( $duration_unit ) {
                case 'year' :
                    $year  = $year   + $duration_interval;
                    break;
                case 'month':
                    $month = $month  + $duration_interval;
                    break;
                case 'day':
                    $day   = $day    + $duration_interval;
                    break;
                case 'week':
                    $day   = $day    + ($duration_interval * 7);
                }
                $endDate = date('Y-m-d H:i:s',mktime($hour, $minute, $second, $month, $day, $year));
                $this->assign('start_date',$startDate);
                $this->assign('end_date',$endDate);
            }
            
            require_once 'CRM/Contribute/DAO/Premium.php';
            $dao = & new CRM_Contribute_DAO_Premium();
            $dao->entity_table = 'civicrm_contribution_page';
            $dao->entity_id    = $this->_id;
            $dao->find(true);
            $this->assign('contact_phone',$dao->premiums_contact_phone);
            $this->assign('contact_email',$dao->premiums_contact_email);
            
            //create Premium record
            
            require_once 'CRM/Contribute/DAO/Product.php';
            $productDAO =& new CRM_Contribute_DAO_Product();
            $productDAO->id = $premiumParams['selectProduct'];
            $productDAO->find(true);
            
            $periodType = $productDAO->period_type;
            
            require_once 'CRM/Utils/Date.php';
            $params = array(
                            'product_id'         => $premiumParams['selectProduct'],
                            'contribution_id'    => $contribution->id,
                            'product_option'     => $premiumParams['options_'.$premiumParams['selectProduct']],
                            'quantity'           => 1,
                            'start_date'         => CRM_Utils_Date::customFormat($startDate,'%Y%m%d'),
                            'end_date'           => CRM_Utils_Date::customFormat($endDate,'%Y%m%d'),
                            );
            
            CRM_Contribute_BAO_Contribution::addPremium($params);
        }
    }

    /**
     * Process the form
     *
     * @return void
     * @access public
     */
    public function processContribution( $params ,$result ,$contactID ,$contributionType ,$deductableMode = true ) {
        CRM_Core_DAO::transaction( 'BEGIN' );

        $config =& CRM_Core_Config::singleton( );
        $nonDeductibleAmount = $result['gross_amount'];
        if ( $contributionType->is_deductible && $deductableMode ) {
            if ( $this->_params['selectProduct'] != 'no_thanks' ) {
                require_once 'CRM/Contribute/DAO/Product.php';
                $productDAO =& new CRM_Contribute_DAO_Product();
                $productDAO->id = $premiumParams['selectProduct'];
                $productDAO->find(true);
                if( $result['gross_amount'] < $productDAO->price ){
                    $nonDeductibleAmount = $result['gross_amount'];
                } else {
                    $nonDeductibleAmount = $productDAO->price;
                }
            } else {
                $nonDeductibleAmount = '0.00';
            }
        }

        $now = date( 'YmdHis' );    
        $receiptDate = null;
        if ( $this->_values['is_email_receipt'] ) {
            $receiptDate = $now ;
        }
        
        // check contribution Type
        // first create the contribution record
        $contribParams = array(
                        'contact_id'            => $contactID,
                        'contribution_type_id'  => $contributionType->id,
                        'contribution_page_id'  => $this->_id,
                        'payment_instrument_id' => 1,
                        'receive_date'          => $now,
                        'non_deductible_amount' => $nonDeductibleAmount,
                        'total_amount'          => $result['gross_amount'],
                        'fee_amount'            => CRM_Utils_Array::value( 'fee_amount', $result ),
                        'net_amount'            => CRM_Utils_Array::value( 'net_amount', $result, $result['gross_amount'] ),
                        'trxn_id'               => $result['trxn_id'],
                        'invoice_id'            => $params['invoiceID'],
                        'currency'              => $params['currencyID'],
                        'receipt_date'          => $receiptDate,
                        'source'                => ts( 'Online Contribution:' ) . ' ' . $this->_values['title'],
                        );
            
        $ids = array( );
        $contribution =& CRM_Contribute_BAO_Contribution::add( $contribParams, $ids );

           
        // process the custom data that is submitted or that came via the url
        $groupTree    = $this->get( 'groupTree' );
        $customValues = $this->get( 'customGetValues' );
        $customValues = array_merge( $params, $customValues );

        require_once 'CRM/Core/BAO/CustomGroup.php';
        CRM_Core_BAO_CustomGroup::postProcess( $groupTree, $customValues );
        CRM_Core_BAO_CustomGroup::updateCustomData($groupTree, 'Contribution', $contribution->id);
            
        // next create the transaction record
        $trxnParams = array(
                        'entity_table'      => 'civicrm_contribution',
                        'entity_id'         => $contribution->id,
                        'trxn_date'         => $now,
                        'trxn_type'         => 'Debit',
                        'total_amount'      => $result['gross_amount'],
                        'fee_amount'        => CRM_Utils_Array::value( 'fee_amount', $result ),
                        'net_amount'        => CRM_Utils_Array::value( 'net_amount', $result, $result['gross_amount'] ),
                        'currency'          => $params['currencyID'],
                        'payment_processor' => $config->paymentProcessor,
                        'trxn_id'           => $result['trxn_id'],
                        );
            
        require_once 'CRM/Contribute/BAO/FinancialTrxn.php';
        $trxn =& CRM_Contribute_BAO_FinancialTrxn::create( $trxnParams );

        // also create an activity history record
        require_once 'CRM/Utils/Money.php';
        $params = array('entity_table'     => 'civicrm_contact', 
                        'entity_id'        => $contactID, 
                        'activity_type'    => $contributionType->name,
                        'module'           => 'CiviContribute', 
                        'callback'         => 'CRM_Contribute_Page_Contribution::details',
                        'activity_id'      => $contribution->id, 
                        'activity_summary' => CRM_Utils_Money::format($result['gross_amount']). ' - ' . $this->_values['title'] . ' (online)',
                        'activity_date'    => $now,
                        );
        if ( is_a( crm_create_activity_history($params), 'CRM_Core_Error' ) ) { 
            CRM_Core_Error::fatal( "Could not create a system record" );
        }

        CRM_Core_DAO::transaction( 'COMMIT' );

        return $contribution;
    }

     /**
     * Process that send e-mails
     *
     * @return void
     * @access public
     */
    function sendMail( $contactID ) {
        if ( $this->_values['is_email_receipt'] ) {
            list( $displayName, $email ) = CRM_Contact_BAO_Contact::getEmailDetails( $contactID );
            
            $template =& CRM_Core_Smarty::singleton( );
            $subject = trim( $template->fetch( 'CRM/Contribute/Form/Contribution/ReceiptSubject.tpl' ) );
            $message = $template->fetch( 'CRM/Contribute/Form/Contribution/ReceiptMessage.tpl' );
           
            $receiptFrom = '"' . $this->_values['receipt_from_name'] . '" <' . $this->_values['receipt_from_email'] . '>';
            require_once 'CRM/Utils/Mail.php';
            CRM_Utils_Mail::send( $receiptFrom,
                                   $displayName,
                                   $email,
                                   $subject,
                                   $message,
                                   $this->_values['cc_receipt'],
                                   $this->_values['bcc_receipt']
                                   );
            
        }
        

    }
}

?>
