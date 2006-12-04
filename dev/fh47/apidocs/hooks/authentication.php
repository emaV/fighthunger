<?php
// $Id: authentication.php,v 1.9.2.2 2006/05/01 10:08:51 webchick Exp $

/**
 * @file
 * These hooks are defined by authentication modules, modules that define
 * ways users can log on using accounts from other sites and servers.
 *
 * A module intending to allow authentication should implement all of these
 * hooks. See the contributed jabber.module for an example.
 *
 * Authentication hooks are typically called by user.module using
 * module_invoke().
 */

/**
 * @addtogroup hooks
 * @{
 */

/**
 * Verify authentication of a user.
 *
 * The _auth hook is the heart of any authentication module. This function
 * is called whenever a user is attempting to log in using your
 * authentication module. The module uses this information to allow or deny
 * access to the site.
 *
 * @param $username
 *   The substring before the final '@' character in the username field.
 * @param $password
 *   The whole string submitted by the user in the password field.
 * @param $server
 *   The substring after the final '@' symbol in the username field.
 * @return
 *   For successful authentications, this function returns TRUE. Otherwise,
 *   it returns FALSE.
 */
function hook_auth($username, $password, $server) {
  $message = new xmlrpcmsg('drupal.login', array(new xmlrpcval($username,
    'string'), new xmlrpcval($password, 'string')));

  $client = new xmlrpc_client('/xmlrpc.php', $server, 80);
  $result = $client->send($message, 5);
  if ($result && !$result->faultCode()) {
    $value = $result->value();
    $login = $value->scalarval();
  }

  return $login;
}

/**
 * Declare authentication scheme information.
 *
 * This hook is required of authentication modules. It defines basic
 * information about the authentication scheme.
 *
 * @param $field
 *   The type of information requested. Possible values:
 *   - "name"
 *   - "protocol"
 * @return
 *   A string containing the requested piece of information. If $field
 *   is not provided, an array containing all the fields should be returned.
 */
function hook_info($field = 0) {
  $info['name'] = 'Drupal';
  $info['protocol'] = 'XML-RPC';

  if ($field) {
    return $info[$field];
  }
  else {
    return $info;
  }
}

/**
 * @} End of "addtogroup hooks".
 */ 