<div class="blue_bl"><div class="blue_br"><div class="blue_tl"><div class="blue_tr">
<?
    print date("j F, Y; ",strtotime($startdate));
    print date("g:i A - ",strtotime($starttime));
    print date("g:i A ",strtotime($endtime))."(local time)";
?>
</dd>
<?
    print "<strong>".$city.", ".
            $countryname."</strong><br/>\n";
    print $address1."<br/>\n";
    print $address2."<br/>\n";
?>
</dd>
<? 
print $route
?>
</dd>

<?
if ($signupcount>48):
?>
<dl>
<?
    print $signupcount.t(" people expected to walk.");
?>
</dd>
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