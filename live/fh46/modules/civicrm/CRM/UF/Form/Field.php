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
 * @package CRM
 * @author Donald A. Lobo <lobo@yahoo.com>
 * @copyright Donald A. Lobo (c) 2005
 * $Id: Field.php 1419 2005-06-10 12:18:04Z shot $
 *
 */


require_once 'CRM/Core/Form.php';
require_once 'CRM/Contact/BAO/Contact.php';

require_once 'CRM/Core/BAO/UFField.php';

/**
 * form to process actions on the field aspect of Custom
 */
class CRM_UF_Form_Field extends CRM_Core_Form {
    /**
     * the uf group id saved to the session for an update
     *
     * @var int
     * @access protected
     */
    var $_gid;

    /**
     * The field id, used when editing the field
     *
     * @var int
     * @access protected
     */
    var $_id;

    /**
     * The set of fields that we can view/edit in the user field framework
     *
     * @var array
     * @access protected
     */
    
    var $_fields;

    /**
     * the title for field
     *
     * @var int
     * @access protected
     */
    var $_title;

    /**
     * The set of fields sent to the select element
     *
     * @var array
     * @access protected
     */
    var $_selectFields;

    /**
     * to store fields with if locationtype exits status 
     *
     * @var array
     * @access protected
     */
    var $_hasLocationTypes;
    
    /**
     * Function to set variables up before form is built
     *
     * @return void
     * @access public
     */
     function preProcess()
    {
        $this->_gid = CRM_Utils_Request::retrieve('gid', $this);
        $this->_id  = CRM_Utils_Request::retrieve('id' , $this);
       

        $this->_fields =& CRM_Contact_BAO_Contact::importableFields('Individual', 1);
       
        $this->_selectFields = array( );
        foreach ($this->_fields as $name => $field ) {
            // if ( $name ) {
                // lets skip note for now since we dont support it
                if ( $name == 'note' ) {
                    continue;
                }
                $this->_selectFields    [$name] = $field['title'];
                $this->_hasLocationTypes[$name] = $field['hasLocationType'];
                //}
        }
        // lets add group and tag to this list
        $this->_selectFields['group'] = ts('Group(s)');
        $this->_selectFields['tag'  ] = ts('Tag(s)');

    }

    /**
     * Function to actually build the form
     *
     * @return void
     * @access public
     */
     function buildQuickForm()
    {

        if($this->_action & CRM_CORE_ACTION_DELETE) {
            $this->addButtons(array(
                                array ( 'type'      => 'next',
                                        'name'      => ts('Delete Profile Field'),
                                        'spacing'   => '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;',
                                        'isDefault' => true   ),
                                array ( 'type'      => 'cancel',
                                        'name'      => ts('Cancel') ),
                                )
                          );
            return;

        }

        if (isset($this->_id)) {
            $params = array('id' => $this->_id);
            CRM_Core_BAO_UFField::retrieve($params, $defaults);
            $defaults[ 'field_name' ] = array ($defaults['field_name'], $defaults['location_type_id'], $defaults['phone_type']);
            $this->_gid = $defaults['uf_group_id'];
        } else {
            $defaults['is_active'] = 1;
        }
        
        if ($this->_action & CRM_CORE_ACTION_ADD) {
            $uf =& new CRM_Core_DAO();
            $sql = "SELECT weight FROM civicrm_uf_field  WHERE uf_group_id = ". $this->_gid ." ORDER BY weight  DESC LIMIT 0, 1"; 
            $uf->query($sql);
            while( $uf->fetch( ) ) {
                $defaults['weight'] = $uf->weight + 1;
            }
            
            if ( empty($defaults['weight']) ) {
                $defaults['weight'] = 1;
            }
        }
        
        // lets trim all the whitespace
        $this->applyFilter('__ALL__', 'trim');

        //hidden field to catch the group id in profile
        $this->add('hidden', 'group_id', $this->_gid);
        
        //hidden field to catch the field id in profile
        $this->add('hidden', 'field_id', $this->_id);

        // field name
        //$this->add( 'select', 'field_name', ts('CiviCRM Field Name'), $this->_selectFields, true );

        $this->_location_types  =& CRM_Core_PseudoConstant::locationType();
        
        require_once 'CRM/Core/BAO/LocationType.php';
        $defaultLocationType =& CRM_Core_BAO_LocationType::getDefault();
        
        /* FIXME: dirty hack to make the default option show up first.  This
         * avoids a mozilla browser bug with defaults on dynamically constructed
         * selector widgets. */
        
        if ($defaultLocationType) {
            $defaultLocation = $this->_location_types[$defaultLocationType->id];
            unset($this->_location_types[$defaultLocationType->id]);
            $this->_location_types = 
                array($defaultLocationType->id => $defaultLocation) + 
                $this->_location_types;
        }
        
        $sel1 = $this->_selectFields;
        
        $sel2[''] = null;
        $phoneTypes = CRM_Core_SelectValues::phoneType();
        foreach ($this->_location_types as $key => $value) {
            $sel3['phone'][$key] =& $phoneTypes;
        }
        
        foreach ($this->_hasLocationTypes  as $key => $value) {
            if ($this->_hasLocationTypes[$key]) {
                $sel2[$key] = $this->_location_types;
            } else {
                $sel2[$key] = null;
            }
        }
        
        $js = "<script type='text/javascript'>\n";
        $formName = 'document.forms.' . $this->_name;
                
        //$sel =& $this->addElement('hierselect', "field_name", ts('CiviCRM Field Name'), 'onclick="getHelpText(this,event, false);"  onblur="getHelpText(this,event, false);" autocomplete="off"', '&nbsp;&nbsp;'); //this commented to fix javascript error
        
        $sel =& $this->addElement('hierselect', "field_name", ts('CiviCRM Field Name'));
        
        for ( $k = 1; $k < 3; $k++ ) {
            if (!$defaults['field_name'][$k]) {
                $js .= "{$formName}['field_name[$k]'].style.display = 'none';\n"; 
            }
        }
        $sel->setOptions(array($sel1, $sel2, $sel3));
       
        $js .= "</script>\n";
        $this->assign('initHideBoxes', $js);

        $this->add( 'select', 'visibility', ts('Visibility'        ), CRM_Core_SelectValues::ufVisibility( ), true );

        // should the field appear in selector?
        $this->add('checkbox', 'in_selector', ts('In Selector?'));

        // weight
        $this->add('text', 'weight', ts('Weight'), CRM_Core_DAO::getAttribute('CRM_Core_DAO_UFField', 'weight'), true);
        $this->addRule('weight', ts(' is a numeric field') , 'numeric');

        $this->add('textarea', 'help_post', ts('Field Help'), CRM_Core_DAO::getAttribute('CRM_Core_DAO_UFField', 'help_post'));

        // listings title
        $this->add('text', 'listings_title', ts('Listings Title'),
                   CRM_Core_DAO::getAttribute('CRM_Core_DAO_UFField', 'listings_title') );
        $this->addRule('listings_title', ts('Please enter a valid title for this field when displayed in user listings.'), 'title');
        
        $this->add( 'checkbox', 'is_required'    , ts( 'Required?'                     ) );
        $this->add( 'checkbox', 'is_active'      , ts( 'Active?'                       ) );
        $this->add( 'checkbox', 'is_view'        , ts( 'View Only?'                    ) );
        // $this->add( 'checkbox', 'is_registration', ts( 'Display in Registration Form?' ) );
        //$this->add( 'checkbox', 'is_match'       , ts( 'Key to Match Contacts?'        ) );
        
        // add buttons
        $this->addButtons(array(
                                array ('type'      => 'next',
                                       'name'      => ts('Save'),
                                       'isDefault' => true),
                                array ('type'      => 'cancel',
                                       'name'      => ts('Cancel')),
                                )
                          );

        $this->addFormRule( array( 'CRM_UF_Form_Field', 'formRule' ));

        // if view mode pls freeze it with the done button.
        if ($this->_action & CRM_CORE_ACTION_VIEW) {
            $this->freeze();
            $this->addElement('button', 'done', ts('Done'), array('onClick' => "location.href='civicrm/admin/uf/group/field?reset=1&action=browse&gid=" . $this->_gid . "'"));
        }

        $this->setDefaults($defaults);

    }

    /**
     * Process the form
     *
     * @return void
     * @access public
     */
     function postProcess()
    {
        if ($this->_action & CRM_CORE_ACTION_DELETE) {
            CRM_Core_BAO_UFField::del($this->_id);
            CRM_Core_Session::setStatus(ts('Selected Profile Field has been deleted.'));
            return;
        }
     
        // store the submitted values in an array
        $params = $this->controller->exportValues('Field');
        $ids = array( );
        
        if ($this->_action & CRM_CORE_ACTION_UPDATE ) {
            $ids['uf_field'] = $this->_id;
        }
        
        $ids['uf_group'] = $this->_gid;
        
        //check for duplicate fields
        if (CRM_Core_BAO_UFField::duplicateField($params, $ids) ) {
            CRM_Core_Session::setStatus(ts('The selected field was not added. It already exists in this profile.'));
            return;
        } else {
            $ufField = CRM_Core_BAO_UFField::add($params,$ids);
            $name = $this->_selectFields[$ufField->field_name];
            CRM_Core_Session::setStatus(ts('Your civicrm profile field "%1" has been saved.', array(1 => $name)));
        }
                
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
     function formRule( &$fields ) {
        $is_required     = CRM_Utils_Array::value( 'is_required'    , $fields, false );
        $is_registration = CRM_Utils_Array::value( 'is_registration', $fields, false );
        $is_view         = CRM_Utils_Array::value( 'is_view'        , $fields, false );     

        $errors = array( );
        if ( $is_view && $is_registration ) {
            $errors['is_registration'] = 'View Only cannot be selected if this field is to be included on the registration form';
        }
        if ( $is_view && $is_required ) {
            $errors['is_view'] = 'A View Only field cannot be required';
        }

        $fieldName = $fields['field_name'][0];
        if (!$fieldName) {
            $errors['field_name'] = 'Please select a field name';
        }
        
        /*   
        if (CRM_Core_Action::ADD && empty($fields['field_id'])) {
            $groupId = $fields['group_id'];
            $query = "SELECT count(*) FROM civicrm_uf_field WHERE uf_group_id = " . CRM_Utils_Type::escape($groupId, 'Integer')  . " AND field_name = '" . CRM_Utils_Type::escape($fieldName, 'String') . "'";
            
            if ( CRM_Core_DAO::singleValueQuery( $query ) > 0 ) {
                $errors['field_name'] = 'Duplicate Field Name choosen. Select different field name';
            }
        }*/
        return empty($errors) ? true : $errors;
    }

}

?>
