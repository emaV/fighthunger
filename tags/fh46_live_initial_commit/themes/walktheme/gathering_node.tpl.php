<div class="blue_bl"><div class="blue_br"><div class="blue_tl"><div class="blue_tr"><dl> <dt><? print t("When") ?>:</dt><dd>
<?
    print date("j F, Y; ",strtotime($startdate));
    print date("g:i A - ",strtotime($starttime));
    print date("g:i A ",strtotime($endtime))."(local time)";
?>
</dd></dl><dl> <dt><? print t("Where") ?>:</dt><dd>
<?
    print "<strong>".$city.", ".
            $countryname."</strong><br/>\n";
    print $address1."<br/>\n";
    print $address2."<br/>\n";
?>
</dd></dl><dl> <dt><? print t("Route") ?>:</dt><dd>
<? 
print $route
?>
</dd></dl>

<?
if ($signupcount>48):
?>
<dl> <dt><? print t("Who") ?>:</dt><dd>
<?
    print $signupcount.t(" people expected to walk.");
?>
</dd></dl>
<? endif;?>

<? if ($sponsors){
    if ($partners){
      foreach($partners as $pid){
        $p = node_load(array("nid"=>$pid));
        $ps[] = l($p->title,"node/".$p->nid);
      }
      $ps[] = $sponsors;
      
      $sponsors = implode(", ",$ps);
    }
    print t("Partners for this event:")." <em>".$sponsors."</em>";
}
?>

</div></div></div></div>

<div class="clear">&nbsp;</div>
<br/>