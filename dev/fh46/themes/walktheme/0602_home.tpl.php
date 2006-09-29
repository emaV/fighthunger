<?php print $featured ?>    

    <div class="blogmore">
      <h3>more from the blog</h3>
      
      <?php print $blogs ?>
      
      <div class="more-link"><a href="blog" title="Read the latest blog entries.">more</a></div>


</div><!-- close blogmore -->
</div><!-- close main -->


<div id="sidebar">

<!-- 0602 join start -->
    <div class="action">
    <h2>walk with us...</h2>
    <p>Join our growing global movement to end child hunger. Take action on May 21st and Walk the World! <a href="signup06">Find out more>></a></p>
      <a href="signup06">
<img src="misc/WalkButtonAnimated.gif" alt="" width="324" height="161" border="0" />
      </a>
    </div>
<!-- 0602 join end -->
<br />

    <div class="action">
    <p><? print $fact ?></p>
	<img src="<? print $kids_graphic ?>" alt="images of children" width="324" height="106" border="0" />
	<p><a href="click"><img src="themes/walktheme/images/btn_kids_click.gif" alt="click to feed a child" width="110" height="110" border="0" align="right" /></a><span class="orange">You have the power to help change this.</span> <? print number_format($fed) ?> children will be fed for a day through this site. <a href="click">Click here</a> to make it <? print number_format($fed + 1) ?>!</p>
    </div>

<br clear="all" />
</div>

