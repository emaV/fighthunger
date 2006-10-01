<!-- profile_content start -->
<div class='profile_content'>

<!-- profile_left start -->
<div class='profile_left'>
  <h2 class='profile'>My information</h2>
  <p>My name is <?php print $user->name ?></p>
<?php if ($user->longcountry): ?>
  <p>I live in <?php print $user->longcountry ?></p>
<?php endif; ?>
  
<?php if ($user->profile_presentation): ?>
  <div class='profile_motivation'>
    <h3>Why I'm supporting Fight Hunger?</h3>
    <?php print theme('user_picture', $user) . $user->profile_presentation; ?>
    <div style='clear:both;'></div>
  </div>
  <div style='clear:both'>&nbsp;</div>
<?php endif; ?>

<?php
  if($user->profile_flickr) {
    print "<p style='vertical-align : middle'><img src='themes/FHbidi/images/flickricon.jpg' style='vertical-align:middle'> " . l("My Flickr photos",$user->profile_flickr) . "</p>"; 
  }
  if($user->profile_blog) {
    print "<p style='vertical-align : middle'><img src='themes/FHbidi/images/feed-icon.png' style='vertical-align:middle'> " . l("My blog / web site",$user->profile_blog) . "</p>"; 
  }
  if($user->profile_delicious) {
    print "<p style='vertical-align : middle'><img src='themes/FHbidi/images/delicious.gif' style='vertical-align:middle'> " . l("My delicious tags",$user->profile_delicious) . "</p>"; 
  }
?>

<?php if ($fields['Walk The World 2006']): ?>
    <h3 class='profile'>My past Fight Hunger activities</h3>
    <?php print $fields['Walk The World 2006']; ?>
<?php endif; ?>

</div>
<!-- profile_left end -->

<!-- profile_right start -->
<div class='profile_right'>
<h2 class='profile'>Team up!</h2>


<?php 
if($fields['donation_obj']) {
  print "  <h3 class='profile'>Donate to Help Child Hunger with me!</h3>";
//print donation_form($fields['donation_obj'], array('donation_source' => $user->uid)); 
    $form_don  = theme('donation_presentation', $fields['donation_obj']);
    $form_don .= theme('donation_btn_donate');
//  $form_don .= form_hidden('amount', 10);
    $form_don  = form($form_don, 'post', 'donation/' . $fields['donation_obj']->nid);
    print $form_don;
  print "  <h3 class='profile'>My donation to date</h3>"; 
  print donation_list($fields['donation-obj']->nid, array('donation_source' => $user->uid));
} else { ?>
  <div class='profile_motivation' align='center'>
<p>Save the date<br />
13 May 2007<br />
Walk with us!</p>
  </div>
<?php }
?>
</div>
<!-- profile_right end -->

<!-- profile_footer start -->
<div class='profile_footer'>

<?php

//  print '<hr /><h3>$fields["donation_obj"]</h3><pre>' . print_r($fields['donation_obj'], true) . "</pre>";
//  print '<hr /><h3>$user->donation</h3><pre>' . print_r($user->donation, true) . "</pre>";
//  print "<hr />";
//  print _donation_list($user->donation);
//  print "<hr />";
//  foreach($fields as $key => $value) {
//  foreach($user as $key => $value) {
//    print "<h3>$key</h3>";
//    print "<p>" . htmlentities(print_r($value,true)) . "</p>";
//  }  
//  print "<hr /><pre>" . print_r($fields, true) . "</pre>";
?>

</div>
<!-- profile_footer end -->

</div>
<!-- profile_content end -->
