<?php
// $Id: cron.php,v 1.28 2005-01-09 09:22:38 dries Exp $

/**
 * @file
 * Handles incoming requests to fire off regularly-scheduled tasks (cron jobs).
 */

include_once 'includes/bootstrap.inc';
include_once 'includes/common.inc' ;

// If not in 'safe mode', increase the maximum execution time:
if (!ini_get('safe_mode')) {
  set_time_limit(240);
}

// Check if the last cron run completed
if (variable_get('cron_busy', false)) {
  watchdog('cron', t('Last cron run did not complete.'), WATCHDOG_WARNING);
}
else {
  variable_set('cron_busy', true);
}

// Iterate through the modules calling their cron handlers (if any):
module_invoke_all('cron');

// Clean up
variable_set('cron_busy', false);
watchdog('cron', t('Cron run completed'));

?>
