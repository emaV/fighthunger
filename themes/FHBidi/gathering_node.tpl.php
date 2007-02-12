<!-- event start -->
<div class="event">

<?php

$alias = db_result(db_query("SELECT dst FROM {url_alias} WHERE src='node/%d'",$node['nid']));
list($campaign, $event) = split('/',  $alias );
$mail_to = "$event.$campaign@fighthunger.org";
$subject = 'subject=' . utf8_encode("tags: $campaign $event");
//$body = 
// print ("<a href='mailto:$mail_to?$subject'>$mail_to</a>");
// print("<br />alias: $alias");
?>


<!-- Status: <?php print $fields['active'] . " - " . _gathering_admin_status($fields['active']) ?> -->

  <dl>
   <dt><?php print t("When") ?>:</dt>
  <dd>
  <?php
      print date("j F, Y; ",strtotime($node['startdate']));
      print date("g:i A - ",strtotime($node['starttime']));
      print date("g:i A ",strtotime($node['endtime']))."(local time)";
  ?>
  </dd>
  </dl>
  
  <dl>
   <dt><?php print t("Where") ?>:</dt>
  <dd>
  <?php
      print "<strong>".$node['city'].", ". $node['countryname']."</strong><br/>\n";
      print $node['address1']."<br />\n";
      print $node['address2']."<br />\n";
  ?>
  </dd>
  </dl>
  
  <dl>
   <dt><?php print t("Route") ?>:</dt>
  <dd>
  <?php print $node['route']; ?>
  </dd>
  </dl>
  
  <?php if ($node['signup_show']):?>
    <dl>
      <dt><?php print t("Who") ?>:</dt>
      <dd>
      <?php print $node['signupcount']. " " . t("people expected to walk."); ?>
      </dd>
    </dl>
  <?php endif;?>
  
  <?php 
    if ($node['sponsors']) {
        print t("This event is sponsored by:")."<br /> <em>".$node['sponsors']."</em>";
    }
  ?>

</div>
<!-- event end -->

<div class="clear">&nbsp;</div>
<br />

<!-- gathering_footer start -->
<div class='gathering_footer'>

<?php
/*
  print "<hr /><h3>fields</h3>";
  print_r($fields);
  print "<hr /><h3> node campaign </h3>";
  print_r($node['campaign']);
  print "<hr /><h3>node</h3>";
  $header = array('key' ,'value');
  foreach ($node as $key => $value) {
    $rows[] = array($key, "<pre>$value</pre>");
  }
  print theme('table', $header, $rows);
  print "<hr />";
*/
?>

</div>
<!-- gathering_footer end -->

