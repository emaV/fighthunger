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
 * We use QFC for both single page and multi page wizards. We want to make
 * creation of single page forms as easy and as seamless as possible. This
 * class is used to optimize and make single form pages a relatively trivial
 * process
 *
 * @package CRM
 * @author Donald A. Lobo <lobo@yahoo.com>
 * @copyright CiviCRM LLC (c) 2004-2006
 * $Id$
 *
 */

require_once 'CRM/Core/Controller.php';
require_once 'CRM/Core/StateMachine.php';

class CRM_Core_Controller_Simple extends CRM_Core_Controller {

    /**
     * constructor
     *
     * @param string path   the class Path of the form being implemented
     * @param string title  the descriptive name for the page
     * @param int    mode   the mode that the form will operate on
     *
     * @return object
     * @access public
     */
    function __construct($path, $title, $mode , $imageUpload = false ) {
        // by definition a single page is modal :). We use the form name as the scope for this controller
        parent::__construct( $title, true, $path );

        $this->_stateMachine =& new CRM_Core_StateMachine( $this );

        $params = array($path => null);

        $this->_stateMachine->addSequentialPages($params, $mode);

        $this->addPages( $this->_stateMachine, $mode );
        
        //changes for custom data type File
        $session = & CRM_Core_Session::singleton( );
        $uploadNames = $session->get( 'uploadNames' );
        
        $config =& CRM_Core_Config::singleton( );
        
        if ( is_array( $uploadNames ) && ! empty ( $uploadNames ) ) {
            $uplaodArray = $uploadNames;
            $this->addActions( $config->customFileUploadDir, $uplaodArray );
            $uploadNames = $session->set( 'uploadNames',null );
            
        } else {
            // always allow a single upload file with same name
            if ( $imageUpload ) {
                $this->addActions( $config->imageUploadDir, array( 'uploadFile' ));
            } else {
                $this->addActions( $config->uploadDir, array( 'uploadFile' ) );
            }
        }
        
    }
}

?>