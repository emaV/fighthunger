<!-- fhwalk start -->
<div class="node<?php print ($sticky) ? " sticky" : ""; ?>">

  <?php if ($page == 0): ?>
    <h2><a href="<?php print $node_url ?>" title="<?php print $title ?>"><?php print $title ?></a></h2>
  <?php endif; ?>


  <div class="content">
<?php // print $content ?>
  </div>

<?php if ($links): ?>
  <div class="links"><?php // print $links ?></div>
<?php endif; ?>


  <div class="content">
<?php
// When
  print "<h3>" . t("When") . "</h3>";
  print $node->hook_view['event'];
// Where
  print $node->hook_view['location'];
// Route
  print "<h3>" . t("Route") . "</h3>";
  print $node->location['additional'];
// Sponsor

// Details
  print "<h3>" . t("Details") . "</h3>";
  print $node->body_plain;

// Donation
//  print "<h3>" . t("donation") . "</h3>";
  print $node->hook_view['donation'];

// Actions
  print $node->actions;

// LCP
  print $node->LCP;
/*
  foreach($node->LCP as $LCPkey => $LCPvalue) {
    $LCPnode = node_view(node_load($LCPvalue['nid']), FALSE);
    $LCPout .= "<div class='LCP-node'>";
    $LCPout .= '<h3>' . $LCPvalue['language'] . '</h3>';
    $LCPout .= $LCPnode;
    $LCPout .= '</div>';
  }
  print "<div class='LCP-container'>";
  print $LCPout;
  print "</div>";
*/
  
// DEBUG
/*
  print "<hr /><h2>START DEBUG</h2>";
    
  $hook_array = $node->hook_view;
  foreach($node->hook_view as $key => $value) {
   print "<h3>$key</h3>\n$value";
  }
 
  print "<hr />" . print_r($node, true);
//  print "<hr />" . print_r($node->LCP, true);
  print "<hr /><h2>END DEBUG</h2>";
*/

?>
  </div>

</div>


<div class="event">

<?php
/*
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
//      print date("j F, Y; ",strtotime($node['startdate']));
//      print date("g:i A - ",strtotime($node['starttime']));
//      print date("g:i A ",strtotime($node['endtime']))."(local time)";
  ?>
  </dd>
  </dl>
  
  <dl>
   <dt><?php print t("Where") ?>:</dt>
  <dd>
  <?php
//      print "<strong>".$node['city'].", ". $node['countryname']."</strong><br/>\n";
//      print $node['address1']."<br />\n";
//      print $node['address2']."<br />\n";
  ?>
  </dd>
  </dl>
  
  <dl>
   <dt><?php print t("Route") ?>:</dt>
  <dd>
  <?php print //$node['route']; ?>
  </dd>
  </dl>
  
  <?php if ($node['signup_show']):?>
    <dl>
      <dt><?php print t("Who") ?>:</dt>
      <dd>
      <?php // print $node['signupcount']. " " . t("people expected to walk."); ?>
      </dd>
    </dl>
  <?php endif;?>
  
  <?php 
//    if ($node['sponsors']) {
//        print t("This event is sponsored by:")."<br /> <em>".$node['sponsors']."</em>";
//    }
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

<!--
[nid] => 2440 
[vid] => 2440 
[type] => fhwalk 
[status] => 1 
[created] => 1169236455 
[changed] => 1169391910 
[comment] => 0 
[promote] => 0 
[moderate] => 0 
[sticky] => 0 
[revision_timestamp] => 1169391910 
[title] => StraPavia
[log] => 
[format] => 1 
[uid] => 3933 
[name] => Emanuele.Quinto@wfp.org 
[picture] => files/pictures/picture-3933.jpg 
[data] => a:22:{s:5:"roles";                 a:1:{s:18:"authenticated user";i:2;}
                s:19:"civicrm_dummy_field";  s:30:"CiviCRM Dummy Field for Drupal";
                s:17:"mimemail_textonly";    s:1:"0";
                s:8:"donation";              s:5:"plain";
                s:20:"profile_presentation"; s:153:"I would like to see at least two million people walking in 2007 and asking their governments to use money to stop child hunger rather than starting wars.";
                s:14:"profile_flickr";       s:41:"http://www.flickr.com/photos/walktheworld";
                s:12:"profile_blog";         s:41:"http://www.flickr.com/photos/walktheworld";
                s:17:"profile_delicious";    s:23:"http://del.icio.us/mupa";
                s:10:"form_token";           s:32:"0bac65bf4350d64d341eab928821916b";
                s:17:"profile_firstname";    s:8:"emanuele";
                s:16:"profile_lastname";     s:6:"quinto";
                s:13:"profile_phone";        s:0:"";
                s:15:"profile_address";      s:0:"";
                s:12:"profile_city";         s:0:"";
                s:15:"profile_zipcode";      s:0:"";
                s:15:"profile_country";      s:2:"it";
                s:13:"profile_state";        s:0:"";
                s:11:"_categories";          a:3:{s:7:"account"; a:3:{s:4:"name";   s:7:"account";
                                                                      s:5:"title";  s:16:"account settings";
                                                                      s:6:"weight"; i:1;}
                                                  s:20:"Personal Information";  a:3:{s:4:"name";    s:20:"Personal Information";
                                                                                     s:5:"title";   s:20:"Personal Information";
                                                                                     s:6:"weight";  i:3;}
                                                  s:7:"Team Up";                a:3:{s:4:"name";    s:7:"Team Up";
                                                                                     s:5:"title";   s:7:"Team Up";
                                                                                     s:6:"weight";  i:3;}}
                s:9:"_category";             s:7:"Team Up";
                s:10:"first_name";           s:8:"emanuele";
                s:9:"last_name";             s:6:"quinto";
                s:7:"country";               s:2:"it";} 
[signup_status] => 2 
[signup_count] => 0 
[signup_pad] => 2000 
[signup_show] => 1 
[donation] => stdClass Object ( [nid] => 2440 
                                [type] => fee 
                                [amount] => 20.00 
                                [currency] => EUR 
                                [active] => 1 ) 
[volunteer] => Array ( [1] => Array ( [wanted] => -1 
                                      [approvation] => 0 
                                      [coordinator] => 3933 
                                      [message_approve] => Thanks for partecipating to %event_title. -%coordinator %site_link 
                                      [message_deny] => Thanks for partecipating to %event_title. Unfortunately the event is full. Please check back for other events. -%coordinator %site_link 
                                      [message_wait] => Thanks for partecipating ! The event is full, but we have put you on the wait-list, so cross your fingers. -%coordinator %site_link 
                                      [message_reminder] => Hey we just wanted to remind you that the event you want to partecipate for is happening soon. Thanks again for partecipate. -%coordinator %site_link 
                                      [message_follow_up] => Thanks for partecipate. We are hoping that you could take a second and tell us about your partecipation. -%coordinator %site_link ) ) 
[_workflow] => 
[last_comment_timestamp] => 1169236455 
[last_comment_name] => 
[comment_count] => 0 
[event_start] => 1179039600 
[event_end] => 1179039600 
[timezone] => 0 
[start_offset] => 
[start_format] => 13 May 2007 - 08:00 
[start_time_format] => 08:00 
[end_offset] => 
[end_format] => 13 May 2007 - 08:00 
[end_time_format] => 08:00 
[event_node_title] => fhwalk 
[location] => Array ( [oid] => 2440 
                      [type] => node 
                      [name] => Palazzo del Comune 
                      [street] => Piazza del Municipio, 23 
                      [additional] => werwer 
                      [city] => Pavia 
                      [province] => PV 
                      [postal_code] => 
                      [country] => it 
                      [latitude] => 
                      [longitude] => 
                      [source] => 0 ) 
[taxonomy] => Array ( [33] => stdClass Object ( [tid] => 33 
                                                [vid] => 7 
                                                [name] => Walk the World 2007 
                                                [description] => On 13 May 2007 people all over the world walked to call for an end to child hunger. In 24 hours and in 24 time-zones people joined us to raise the funds and awareness we need to end child hunger. 
                                                [weight] => 7 
                                                [language] => en 
                                                [trid] => 0 ) ) 
[files] => Array ( ) 
[readmore] => 
[links] => Array ( [0] => calendar  [1] => Signup  ) )

-->
