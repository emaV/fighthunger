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

require_once 'CRM/Core/SelectValues.php';
require_once 'CRM/Core/Form.php';

/**
 * This class generates form components for relationship
 * 
 */
class CRM_Contribute_Form_Task extends CRM_Core_Form
{
    /**
     * the task being performed
     *
     * @var int
     */
    protected $_task;

    /**
     * The additional clause that we restrict the search with
     *
     * @var string
     */
    protected $_contributionClause = null;

    /**
     * The array that holds all the contribution ids
     *
     * @var array
     */
    protected $_contributionIds;

    /**
     * build all the data structures needed to build the form
     *
     * @param
     * @return void
     * @access public
     */
    function preProcess( ) 
    {
        $this->_contributionIds = array();

        $values = $this->controller->exportValues( 'Search' );
        
        $this->_task = $values['task'];
        $contributeTasks = CRM_Contribute_Task::tasks();
        $this->assign( 'taskName', $contributeTasks[$this->_task] );
        $ids = array();
        if ( $values['radio_ts'] == 'ts_sel' ) {
            foreach ( $values as $name => $value ) {
                if ( substr( $name, 0, CRM_Core_Form::CB_PREFIX_LEN ) == CRM_Core_Form::CB_PREFIX ) {
                    $ids[] = substr( $name, CRM_Core_Form::CB_PREFIX_LEN );
                }
            }
            if ( ! empty( $ids ) ) {
                $this->_contributionClause =
                    ' civicrm_contribution.id IN ( ' .
                    implode( ',', $ids ) . ' ) ';
                $this->assign( 'totalSelectedContributions', count( $ids ) );
            }
        } else {
            $queryParams =  $this->get( 'queryParams' );
            $query       =& new CRM_Contact_BAO_Query( $queryParams, null, null, false, false, 
                                                       CRM_Contact_BAO_Query::MODE_CONTRIBUTE);
            $result = $query->searchQuery(0, 0, null);
            while ($result->fetch()) {
                $ids[] = $result->contribution_id;
            }
            $this->assign( 'totalSelectedContributions', $this->get( 'rowCount' ) );
        }
        $this->_contributionIds = $ids;
    }

    /**
     * Function to actually build the form
     *
     * @return void
     * @access public
     */
    public function buildQuickForm( ) 
    {
    }

    /**
     * simple shell that derived classes can call to add buttons to
     * the form with a customized title for the main Submit
     *
     * @param string $title title of the main button
     * @param string $type  button type for the form after processing
     * @return void
     * @access public
     */
    function addDefaultButtons( $title, $nextType = 'next', $backType = 'back' ) {
        $this->addButtons( array(
                                 array ( 'type'      => $nextType,
                                         'name'      => $title,
                                         'isDefault' => true   ),
                                 array ( 'type'      => $backType,
                                         'name'      => ts('Cancel') ),
                                 )
                           );
    }

}

?>
