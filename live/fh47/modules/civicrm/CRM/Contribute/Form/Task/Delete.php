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

require_once 'CRM/Contribute/Form/Task.php';
require_once 'CRM/Contribute/BAO/Contribution.php';

/**
 * This class provides the functionality to delete a group of
 * contributions. This class provides functionality for the actual
 * deletion.
 */
class CRM_Contribute_Form_Task_Delete extends CRM_Contribute_Form_Task {

    /**
     * Are we operating in "single mode", i.e. deleting one
     * specific contribution?
     *
     * @var boolean
     */
    protected $_single = false;

    /**
     * build all the data structures needed to build the form
     *
     * @return void
     * @access public
     */
    function preProcess() {
        parent::preProcess();
    }

    /**
     * Build the form
     *
     * @access public
     * @return void
     */
    function buildQuickForm() {
        $this->addDefaultButtons(ts('Delete Contributions'), 'done');
    }

    /**
     * process the form after the input has been submitted and validated
     *
     * @access public
     * @return None
     */
    public function postProcess() {
        $session =& CRM_Core_Session::singleton();

        $deletedContributions = 0;
        foreach ($this->_contributionIds as $contributionId) {
            if (CRM_Contribute_BAO_Contribution::deleteContribution($contributionId)) {
                $deletedContributions++;
            }
        }

        $status = array(
                        ts('Deleted Contribution(s): %1', array(1 => $deletedContributions)),
                        ts('Total Selected Contribution(s): %1', array(1 => count($this->_contributionIds))),
                        );
        CRM_Core_Session::setStatus($status);
    }


}

?>
