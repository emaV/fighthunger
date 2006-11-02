<?php
// Reads a csv formatted file and creates users.
// $Id: import-users.php,v 1.1.2.2 2006/07/20 20:29:05 weitzman Exp $
include "includes/bootstrap.inc";
include "includes/common.inc";

$handle = fopen("daten.csv", "r");
while ($data = fgetcsv ($handle, 1000, ";")) {
  $array = array("name" => $data[0], "pass" => $data[1], "mail" => $data[2], "status" => 1, "rid" => _user_authenticated_id());
  user_save($account, $array);
}
fclose ($handle);

?>
