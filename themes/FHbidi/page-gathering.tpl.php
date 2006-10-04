<div id="content">
<table id="content">
	<tr>
	
<!-- left-sidebar start -->
<?php if ($sidebar_left != ""): ?>
		<td class="sidebar" id="sidebar-left">
				<?php print $sidebar_left ?>
		</td>
<?php endif; ?>		
<!-- left-sidebar end -->

<!-- center-content start -->
		<td class="main-content" id="content-<?php print $layout ?>">

<?php if ($title != ""): ?>
        <?php if ($title != "layout"): ?>
		        <h2 class="content-title"><?php print $title ?></h2>
		     <?php endif; ?>   
<?php endif; ?>

<? if (($tabs != "") && (user_access("view tabs") || (arg(0) == "faq"))): ?>
			<?php print $tabs ?>
<?php endif; ?>
				
<?php if ($mission != ""): ?>
			<div id="mission"><?php print $mission ?></div>
<?php endif; ?>
				
<?php if ($help != ""): ?>
			<p id="help"><?php print $help ?></p>
<?php endif; ?>
				
<?php if ($messages != ""): ?>
			<div id="message"><?php print $messages ?></div>
<?php endif; ?>
				
<!-- content start -->
<?php
/*
  print('<h1>bubusetttete</h1>');
  print "<hr />";
  print_r($fields);
  print "<hr />";
  print_r($title);
  print "<hr />";
  $header = array('key' ,'value');
  foreach ($node as $key => $value) {
    $rows[] = array($key, "<pre>$value</pre>");
  }
  print theme('table', $header, $rows);
  print "<hr />";
*/
?>
				
<?php 
  if ($node->type==gathering-1) {
    $out = $content;
  } else {
    switch(arg(0)) {
      case 'donation':
      case 'comment':
        $out = $content;
        break;
      default:
        $out = $node->body;
    }
  }
  print "\n$out\n";
?>
<!-- content end -->

		</td>
<!-- center-content start -->

<!-- right-sidebar start -->
		<?php if ($sidebar_right != ""): ?>
		<td class="sidebar" id="sidebar-right">
				<?php print $sidebar_right ?>
		</td>
		<?php endif; ?>
<!-- right-sidebar end -->

	</tr>
</table>

<!--
<pre><?php //print "content of node is\n". htmlentities(print_r($content,true)) ?></pre>
<pre><?php // print "content of node is\n". htmlentities(print_r($node,true)) ?></pre>
-->

</div>
