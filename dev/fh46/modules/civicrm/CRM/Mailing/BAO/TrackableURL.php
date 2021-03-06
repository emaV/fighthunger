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
 * $Id$
 *
 */

$GLOBALS['_CRM_MAILING_BAO_TRACKABLEURL']['base'] =  null;
$GLOBALS['_CRM_MAILING_BAO_TRACKABLEURL']['pattern'] =  null;

require_once 'CRM/Mailing/DAO/TrackableURL.php';

class CRM_Mailing_BAO_TrackableURL extends CRM_Mailing_DAO_TrackableURL {

    /**
     * class constructor
     */
    function CRM_Mailing_BAO_TrackableURL( ) {
        parent::CRM_Mailing_DAO_TrackableURL( );
    }

    /**
     * Given a url, mailing id and queue event id, find or construct a
     * trackable url and redirect url.
     *
     * @param string $url       The target url to track
     * @param int $mailing_id   The id of the mailing
     * @param int $queue_id     The queue event id (contact clicking through)
     * @return string $redirect The redirect/tracking url
     * @static
     */
      function getTrackerURL($url, $mailing_id, $queue_id) {

        
        
        if ($GLOBALS['_CRM_MAILING_BAO_TRACKABLEURL']['base'] == null) {
            $GLOBALS['_CRM_MAILING_BAO_TRACKABLEURL']['base'] = CRM_Utils_System::baseURL();
        }
        
        $tracker =& new CRM_Mailing_BAO_TrackableURL();
        $tracker->url = $url;
        $tracker->mailing_id = $mailing_id;
        
        if (! $tracker->find(true)) {
            $tracker->save();
        }
        $id = $tracker->id;
        
        $redirect = $GLOBALS['_CRM_MAILING_BAO_TRACKABLEURL']['base'] . 'modules/civicrm/extern/url.php?q=' . $queue_id .
                    '&u=' . $id;
        return $redirect;
    }

      function scan_and_replace(&$msg, $mailing_id, $queue_id) {
        
    
        if (! $mailing_id) {
            return;
        }

        if ($GLOBALS['_CRM_MAILING_BAO_TRACKABLEURL']['pattern'] == null) {
            $protos = '(https?|ftp)';
            $letters = '\w';
            $gunk = '/#~:.?+=&%@!\-';
            $punc = '.:?\-';
            $any = "{$letters}{$gunk}{$punc}";
            $GLOBALS['_CRM_MAILING_BAO_TRACKABLEURL']['pattern'] = "{\\b($protos:[$any]+?(?=[$punc]*[^$any]|$))}eim";
        }
        
        $msg = preg_replace($GLOBALS['_CRM_MAILING_BAO_TRACKABLEURL']['pattern'],
                            "CRM_Mailing_BAO_TrackableURL::getTrackerURL('\\1', $mailing_id, $queue_id)", 
                            $msg);
    }
}

?>
