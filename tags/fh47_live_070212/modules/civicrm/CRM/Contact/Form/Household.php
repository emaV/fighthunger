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

require_once 'CRM/Core/Form.php';
require_once 'CRM/Core/SelectValues.php';
require_once 'CRM/Core/ShowHideBlocks.php';

/**
 * Auxilary class to provide support to the Contact Form class. Does this by implementing
 * a small set of static methods
 *
 */
class CRM_Contact_Form_Household 
{
    /**
     * This function provides the HTML form elements that are specific to the Individual Contact Type
     *
     * @access public
     * @return None
     */
    public function buildQuickForm( &$form ) 
    {
        $attributes = CRM_Core_DAO::getAttribute('CRM_Contact_DAO_Household');
        
        $form->applyFilter('__ALL__','trim');  
      
        // household_name
        $form->add('text', 'household_name', ts('Household Name'), $attributes['household_name']);
        
        // nick_name
        $form->addElement('text', 'nick_name', ts('Nick Name'),
                          CRM_Core_DAO::getAttribute('CRM_Contact_DAO_Contact', 'nick_name') );
    }
    
    /**
     * add rule for household
     *
     * @params array $fields array of form values
     *
     * @return $error 
     * @static
     * @public
     */
    static function formRule( &$fields ,&$files, $options) 
    {
        $errors = array( );

        $primaryEmail = CRM_Contact_Form_Edit::formRule( $fields, $errors );

        // make sure that household name is set
        if (! CRM_Utils_Array::value( 'household_name', $fields ) ) {
            $errors['household_name'] = 'Household Name should be set.';
        }
        
        //code for dupe match
        if ( ! CRM_Utils_Array::value( '_qf_Edit_next_duplicate', $fields )) {
            $dupeIDs = array();
            require_once "CRM/Contact/DAO/Household.php";
            $contact = & new CRM_Contact_DAO_Household();
            $contact->household_name = $fields['household_name'];
            $contact->find();
            while ($contact->fetch(true)) {
                if ( $contact->contact_id != $options) {
                    $dupeIDs[] = $contact->contact_id;
                }
            }
            foreach( $dupeIDs as $id ) {
                $displayName = CRM_Core_DAO::getFieldValue( 'CRM_Contact_DAO_Contact', $id, 'display_name' );
                $urls[] = '<a href="' . CRM_Utils_System::url( 'civicrm/contact/add', 'reset=1&action=update&cid=' . $id ) .
                    '">' . $displayName . '</a>';
            }
            if (!empty($dupeIDs)) {
                $url = implode( ', ',  $urls );
                $errors['_qf_default'] = ts( 'One matching contact was found. You can edit it here: %1, or click Save Duplicate Contact button below.', array( 1 => $url, 'count' => count( $urls ), 'plural' => '%count matching contacts were found. You can edit them here: %1, or click Save Duplicate Contact button below.' ) );
                $template =& CRM_Core_Smarty::singleton( );
                $template->assign( 'isDuplicate', 1 );
            } else if ( CRM_Utils_Array::value( '_qf_Edit_refresh_dedupe', $fields ) ) {
                // add a session message for no matching contacts
                CRM_Core_Session::setStatus( 'No matching contact found.' );
            }
        }

        return empty( $errors ) ? true : $errors;
    }

}


    
?>
