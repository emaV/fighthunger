<?php
// $Id: smtp.inc,v 1.6 2003/12/11 13:59:58 mathias Exp $
include_once 'includes/class.smtp.inc';

function user_mail_wrapper($mail, $subject, $message, $header) {
  // The smtp server host/ip
  $params['host'] = 'relayout.edgewebhosting.net';
  // The smtp server port
  $params['port'] = 25;
  // What to use when sending the helo command. Typically, your domain/hostname
  $params['helo'] = 'relayout.edgewebhosting.net';
  // Whether to use basic authentication or not
  $params['auth'] = FALSE;
  // Username for authentication
  $params['user'] = '';
  // Password for authentication
  $params['pass'] = '';

  // The recipients (can be multiple)
  $send_params['recipients'] = $mail;
  // The headers of the mail
  $send_params['headers'] = explode("\n", $header ."\nSubject: $subject\nTo: $mail");
  // The body of the email
  $send_params['body'] = str_replace("\n", "\r\n", $message);

  $smtp = smtp::connect($params);
  if (count($smtp->errors)) {
    watchdog('error', 'mail connect error: '. implode('<br />', $smtp->errors));
    return false;
  }
  $smtp->send($send_params);
  if (count($smtp->errors)) {
    watchdog('error', 'mail send error: '. implode('<br />', $smtp->errors));
    return false;
  }

  return true;
}
?>
