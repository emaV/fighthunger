<?php
// $Id: update-teaser.php,v 1.2.4.2 2006/07/20 20:29:05 weitzman Exp $
include "includes/bootstrap.inc";
include "includes/common.inc";

$result = db_query("SELECT nid,body,teaser FROM node");

while ($node = db_fetch_object($result)) {
  $teaser = node_teaser($node->body);
  db_query("UPDATE node SET teaser = '%s' WHERE nid = %d", $teaser, $node->nid);
}

?>
