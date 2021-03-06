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
 
require_once 'CRM/Core/Page.php';

/**
 * ICalendar class
 *
 */
class CRM_Event_Page_ICalendar extends CRM_Core_Page
{
    /**
     * Heart of the iCalendar data assignment process. The runner gets all the meta
     * data for the event and calls the  method to output the iCalendar
     * to the user. If gData param is passed on the URL, outputs gData XML format.
     * Else outputs iCalendar format per IETF RFC2445. Page param true means send
     * to browser as inline content. Else, we send .ics file as attachment.
     *
     * @return void
     */
    function run( )
    {
        $type     = CRM_Utils_Request::retrieve('type' , 'Positive', $this, false, 0);
        $start    = CRM_Utils_Request::retrieve('start', 'Positive', $this, false, 0);
        $iCalPage = CRM_Utils_Request::retrieve('page' , 'Positive', $this, false, 0);
        $gData    = CRM_Utils_Request::retrieve('gData', 'Positive', $this, false, 0);
        $rss      = CRM_Utils_Request::retrieve('rss'  , 'Positive', $this, false, 0);

        require_once "CRM/Event/BAO/Event.php";
        $info = CRM_Event_BAO_Event::getCompleteInfo( $start, $type );
        $this->assign( 'events', $info );

        // Send data to the correct template for formatting (iCal vs. gData)
        $template =& CRM_Core_Smarty::singleton( );
        if ( empty ( $gData ) && empty ( $rss ) ) {
            $calendar = $template->fetch( 'CRM/Core/Calendar/ICal.tpl' );
        } else {
            if ( $rss ) {
                $config =& CRM_Core_Config::singleton( );
                // rss 2.0 requires lower case dash delimited locale
                $this->assign( 'rssLang', str_replace( '_', '-', strtolower($config->lcMessages) ) );
                $calendar = $template->fetch( 'CRM/Core/Calendar/Rss.tpl' );
            } else {
                $calendar = $template->fetch( 'CRM/Core/Calendar/GData.tpl' );
            }
        }

        // Push output for feed or download
        require_once "CRM/Utils/ICalendar.php";
        if( $iCalPage == 1) {
            if ( empty ( $gData ) && empty ( $rss ) ) {
                CRM_Utils_ICalendar::send( $calendar, 'text/plain', 'utf-8' );
            } else {
                CRM_Utils_ICalendar::send( $calendar, 'text/xml', 'utf-8' );
            }
        } else {
            CRM_Utils_ICalendar::send( $calendar, 'text/calendar', 'utf-8', 'civicrm_ical.ics', 'attachment' );
        }
        exit( );
    }
}

?>
