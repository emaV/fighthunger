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
 * new version of civicrm apis. See blog post at
 * http://civicrm.org/node/131
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2007
 * $Id: GroupContact.php 10014 2007-06-17 21:54:26Z lobo $
 *
 */

require_once 'api/v2/utils.php';

function civicrm_group_contact_get( &$params ) {
    if ( ! array_key_exists( 'contact_id', $params ) ) {
        return civicrm_create_error( ts( 'contact_id is a required field' ) );
    }

    $status = CRM_Utils_Array::value( 'status', $params, 'Added' );
    require_once 'CRM/Contact/BAO/GroupContact.php';
    $values =& CRM_Contact_BAO_GroupContact::getContactGroup( $params['contact_id'], $status, null, false, true );
    return $values;
}

function civicrm_group_contact_add( &$params ) {
    return civicrm_group_contact_common( $params, 'add' );
}

function civicrm_group_contact_remove( &$params ) {
    return civicrm_group_contact_common( $params, 'remove' );
}

function civicrm_group_contact_common( &$params, $op = 'add' ) {
    $contactIDs = array( );
    $groupIDs = array( );
    foreach ( $params as $n => $v ) {
        if ( substr( $n, 0, 10 ) == 'contact_id' ) {
            $contactIDs[] = $v;
        } else if ( substr( $n, 0, 8 ) == 'group_id' ) {
            $groupIDs[] = $v;
        }
    }

    if ( empty( $contactIDs ) ) {
        return civicrm_create_error( ts( 'contact_id is a required field' ) );
    }

    if ( empty( $groupIDs ) ) {
        return civicrm_create_error( ts( 'group_id is a required field' ) );
    }

    $method     = CRM_Utils_Array::value( 'method'  , $params, 'API v2' );
    if ( $op == 'add' ) {
        $status     = CRM_Utils_Array::value( 'status'  , $params, 'Added'  );
    } else {
        $status     = CRM_Utils_Array::value( 'status'  , $params, 'Removed');
    }
    $tracking   = CRM_Utils_Array::value( 'tracking', $params );

    require_once 'CRM/Contact/BAO/GroupContact.php';
    $values = array( 'is_error' => 0 );
    if ( $op == 'add' ) {
        $values['total_count'] = $values['added'] = $values['not_added'] = 0;
        foreach ( $groupIDs as $groupID ) {
            list( $tc, $a, $na ) = 
                CRM_Contact_BAO_GroupContact::addContactsToGroup( $contactIDs, $groupID,
                                                                  $method, $status, $tracking );
            $values['total_count'] += $tc;
            $values['added']       += $a;
            $values['not_added']   += $na;
        }
    } else {
        $values['total_count'] = $values['removed'] = $values['not_removed'] = 0;
        foreach ( $groupIDs as $groupID ) {
            list( $tc, $r, $nr ) = 
                CRM_Contact_BAO_GroupContact::removeContactsFromGroup( $contactIDs, $groupID,
                                                                       $method, $status, $tracking );
            $values['total_count'] += $tc;
            $values['removed']     += $r;
            $values['not_removed'] += $nr;
        }
    }
    return $values;
}
