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

/**
 * This class holds all the Pseudo constants that are specific to Mass mailing. This avoids
 * polluting the core class and isolates the mass mailer class
 */
class CRM_Contribute_PseudoConstant extends CRM_Core_PseudoConstant {

    /**
     * contribution types
     * @var array
     * @static
     */
    private static $contributionType;

    /**
     * contribution pages
     * @var array
     * @static
     */
    private static $contributionPage;

    /**
     * payment instruments
     *
     * @var array
     * @static
     */
    private static $paymentInstrument;

    /**
     * credit card
     *
     * @var array
     * @static
     */
    private static $creditCard;

    /**
     * contribution status 
     *
     * @var array
     * @static
     */
    private static $contributionStatus; 

    /**
     * Get all the contribution types
     *
     * @access public
     * @return array - array reference of all contribution types if any
     * @static
     */
    public static function &contributionType($id = null)
    {
        if ( ! self::$contributionType ) {
            CRM_Core_PseudoConstant::populate( self::$contributionType,
                                               'CRM_Contribute_DAO_ContributionType' );
        }
        if ($id) {
            $result = CRM_Utils_Array::value( $id, self::$contributionType );
            return $result;
        }
        return self::$contributionType;
    }

    /**
     * Get all the contribution pages
     *
     * @access public
     * @return array - array reference of all contribution pages if any
     * @static
     */
    public static function &contributionPage($id = null)
    {
        if ( ! self::$contributionPage ) {
            CRM_Core_PseudoConstant::populate( self::$contributionPage,
                                               'CRM_Contribute_DAO_ContributionPage',
                                               false, 'title' );
        }
        if ( $id ) {
            return CRM_Utils_Array::value( $id, self::$contributionPage );
        }
        return self::$contributionPage;
    }

    /**
     * Get all the payment instruments
     *
     * @access public
     * @return array - array reference of all payment instruments if any
     * @static
     */

    public static function &paymentInstrument( )
    {
        require_once 'CRM/Core/OptionGroup.php';
        $paymentInstrument = CRM_Core_OptionGroup::values('payment_instrument');
        if ( ! $paymentInstrument ) {
            $paymentInstrument = array( );

        }
        return $paymentInstrument;
    }


    /**
     * Get all the valid accepted credit cards
     *               
     * @access public 
     * @return array - array reference of all payment instruments if any 
     * @static 
     */                  
    public static function &creditCard( ) {
        
        require_once 'CRM/Core/OptionGroup.php';
        $creditCard = CRM_Core_OptionGroup::values('accept_creditcard');
        
        if  ( ! $creditCard ) {
            $creditCard = array( );
         }
        foreach($creditCard as $key => $value) {
            $acceptCreditCard[$value] = $value;
        }
        return $acceptCreditCard;

    }

    /**
     * Get all premiums 
     *               
     * @access public 
     * @return array - array of all Premiums if any 
     * @static 
     */  
    public static function products( $pageID = null ) {
        $products = array();
        require_once 'CRM/Contribute/DAO/Product.php';
        $dao = & new CRM_Contribute_DAO_Product();
        $dao->domain_id  = CRM_Core_Config::domainID( );
        $dao->is_active = 1;
        $dao->orderBy( 'id' );
        $dao->find( );
        
        while ( $dao->fetch( ) ) {
            $products[$dao->id] = $dao->name;
        }
        if ( $pageID ) {
            require_once 'CRM/Contribute/DAO/Premium.php';
            $dao =& new CRM_Contribute_DAO_Premium();
            $dao->entity_table = 'civicrm_contribution_page';
            $dao->entity_id = $pageID; 
            $dao->find(true);
            $premiumID = $dao->id;
            
            $productID = array();  
            
            require_once 'CRM/Contribute/DAO/PremiumsProduct.php';
            $dao =& new CRM_Contribute_DAO_PremiumsProduct();
            $dao->premiums_id = $premiumID;
            $dao->find();
            while ($dao->fetch()) {
                $productID[$dao->product_id] = $dao->product_id;
            }
           
            $tempProduct = array();
            foreach( $products as $key => $value ) {
                if ( ! array_key_exists( $key , $productID ) ) {
                    $tempProduct[$key] = $value;
                }
            }
            
            return $tempProduct;
        }

        return $products;        
    }
    
    /**
     * Get all the contribution types
     *
     * @access public
     * @return array - array reference of all contribution types if any
     * @static
     */
    public static function &contributionStatus( )
    {
        self::$contributionStatus = array();
        if ( ! self::$contributionStatus ) {
            require_once "CRM/Core/OptionGroup.php";
            self::$contributionStatus = CRM_Core_OptionGroup::values("contribution_status");
        }
        return self::$contributionStatus;
    }
}

?>
