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

require_once 'CRM/Activity/Form.php';
require_once 'CRM/Core/BAO/ActivityType.php';

/**
 * This class generates form components for OtherActivity
 * 
 */
class CRM_Activity_Form_OtherActivity extends CRM_Activity_Form
{

    /**
     * variable to store activity type name
     *
     */
    public $_activityType = 'Activity';

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
        $activityType = CRM_Core_PseudoConstant::activityType(false);

        $this->applyFilter('__ALL__', 'trim');
        $this->add('select', 'activity_type_id', ts('Activity Type'), array('' => ts('- select activity type -')) + $activityType, 
                   array('onChange' => 'activity_get_description( )'), true );
        $this->addRule('activity_type_id', ts('Select a valid activity.'), 'required');

        $this->add('text', 'description', ts('Description'),
                   CRM_Core_DAO::getAttribute( 'CRM_Core_DAO_ActivityType', 'description' ), false);

        $this->add('text', 'subject', ts('Subject') , CRM_Core_DAO::getAttribute( 'CRM_Activity_DAO_Activity', 'subject' ), true );

        $this->add('date', 'scheduled_date_time', ts('Date and Time'), CRM_Core_SelectValues::date('datetime'), true);
        $this->addRule('scheduled_date_time', ts('Select a valid date.'), 'qfDate');
        
        $this->add('select','duration_hours',ts('Duration'),CRM_Core_SelectValues::getHours());
        $this->add('select','duration_minutes', null,CRM_Core_SelectValues::getMinutes());

        $this->add('text', 'location', ts('Location'), CRM_Core_DAO::getAttribute( 'CRM_Activity_DAO_Activity', 'location' ) );
        
        $this->add('textarea', 'details', ts('Details'), CRM_Core_DAO::getAttribute( 'CRM_Activity_DAO_Activity', 'details' ) );
        
        $this->add('select','status',ts('Status'), CRM_Core_SelectValues::activityStatus(), true );
        
    }

    /**
     * Function to process the form
     *
     * @access public
     * @return None
     */
    public function postProcess() 
    {
        if ($this->_action & CRM_Core_Action::VIEW ) { 
            return;
        }

        if ($this->_action & CRM_Core_Action::DELETE ) { 
            CRM_Activity_BAO_Activity::del( $this->_id, $this->_activityType);
            CRM_Core_Session::setStatus( ts("Selected Meeting is deleted sucessfully."));
            return;
        }

        // store the submitted values in an array
        $params = $this->controller->exportValues( $this->_name );
        $ids = array();
        
        // store the date with proper format
        $params['scheduled_date_time']= CRM_Utils_Date::format( $params['scheduled_date_time'] );

        // store the contact id and current drupal user id
        $params['source_contact_id'  ] = $this->_sourceCID;
        $params['target_entity_id'   ] = $this->_targetCID;
        $params['target_entity_table'] = 'civicrm_contact';

        //set parent id if exists for follow up activities
        if ($this->_pid) {
            $params['parent_id'] = $this->_pid;            
        }
        
        if ($this->_action & CRM_Core_Action::UPDATE ) {
            $ids['id'] = $this->_id;
        }

        require_once "CRM/Activity/BAO/Activity.php";
        CRM_Activity_BAO_Activity::createActivity($params, $ids, $this->_activityType);
    }
}

?>
