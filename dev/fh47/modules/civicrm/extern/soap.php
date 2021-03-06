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

session_start( );

require_once '../civicrm.config.php';
require_once 'CRM/Core/Config.php';

$server =& new SoapServer(null, 
                          array('uri' => 'urn:civicrm',
                                'soap_version' => SOAP_1_2 ) );


require_once 'CRM/Utils/SoapServer.php';
$crm_soap =& new CRM_Utils_SoapServer();

/* Cache the real UF, override it with the SOAP environment */
$config =& CRM_Core_Config::singleton();

$server->setClass('CRM_Utils_SoapServer', $config->userFrameworkClass);

$config->userFramework      = 'Soap';
$config->userFrameworkClass = 'CRM_Utils_System_Soap';
$config->userHookClass      = 'CRM_Utils_Hook_Soap';

$server->setPersistence(SOAP_PERSISTENCE_SESSION);

$server->handle();

?>