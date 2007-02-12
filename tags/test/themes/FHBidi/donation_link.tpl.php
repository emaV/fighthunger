<!-- donation_link start -->
<!-- 
<div class='donation_link' style='background: #efefef; border-bottom: solid 2px #ddd; padding: 20px; width: 200px; margin: 10px auto; text-align: center;'>
<div class='donation_link' style='background: #0065B5; padding: 20px; width: 200px; margin: 10px auto; text-align: center;'>
-->
<div class='donation_link'>
<?php
  if ($dl['link']<>'') {
    $txt =  $dl['text'];
    if ($dl['amount']>0)
      $txt .= "<br />(" . $dl['amount'] . " " . $dl['currency'] .")";
    $out = l($txt, $dl['link'], NULL, NULL, NULL, FALSE, TRUE);
  } else {
    $out = $dl['text'];
  }  
  print $out;
?>
</div>
<!-- donation_link end -->
