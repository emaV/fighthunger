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
 * $Id: Wrapper.php 10014 2007-06-17 21:54:26Z lobo $
 *
 */

require_once 'Reports/Zend/Birt/Design.php';
require_once '/opt/local/apache2/htdocs/ZendPlatform/server/birtGlobals.php';

// $fileName = '/Users/lobo/tools/workspace.old/CiviCRM Reports/ContributionByYear.rptdesign';
$fileName = '/Users/lobo/svn/trunk/Reports/Contact/contactReport.rptdesign';

echo "$fileName<p>";
$birt = new Reports_Zend_Birt_Design( $fileName );

$birt->setParameter('StateID','1014');
$birt->setParameter('Year','2005');

// "BIRT_TMP_DIR" represents the path to a writable directory, and "birtImage.php?image=" is
// a php script that displays the image from its original location
$birt->setImageConfiguration(BIRT_TMP_DIR, 'birtImage.php?image=');
   
// Render report.
// BIRT_REPORT_FORMAT_HTML - render an html report
echo $birt->renderReport(BIRT_REPORT_FORMAT_HTML);

?>
