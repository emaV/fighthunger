<!-- user_edit start -->

<?php
  global $user;

  $account = user_load(array('uid' => arg(1)));
  $edit = $_POST['op'] ? $_POST['edit'] : object2array($account);
  $category = (arg(3)<>'') ? arg(3) : 'account';
  $form_link = $_GET['q'];
  $link_top = "\n\t<p class='button'><a href='$form_link#top'>top</a></p>\n";

  $hook = 'form';
  $forms =  array();

  $categories =  _user_categories();
  foreach($categories as $category) {
    foreach (module_list() as $module) {
      if ($datas = module_invoke($module, 'user', $hook, $edit, $account, $category['name'])) {
        foreach ($datas as $data) {
          $data['module'] = $module;
          $data['category'] = $category['name'];
          $idx = $module . "-" . $data['title'];
          $data['category_weight'] = $category['weight'];
          $forms[$category['name']][$data['title']] = $data;
        }
      }
    }
  }
  $form_account  = $forms['account']['Account information']['data'];
  $form_account .= $link_top;
  $form_account  = form_group("", $form_account);
 
  $form_civicrm  = $forms['Contact Information']['Contact Information']['data'];
  $form_civicrm .= $link_top;
  $form_civicrm  = form_group("", $form_civicrm);

  $forms_picture = $forms['account']['Picture']['data'];
  list($div_picture, $dl) = explode("<dl>", $forms_picture);
  $dl = "<dl>$dl";
  list($dt, $dd) = explode("</dd>", $dl);
  $forms_picture = $dt . "<div style='clear:both;height:auto'>" . $div_picture . "</div></dd>\n</dl>";
/*
<div class="picture">
  <a href="user/emanuele_quinto_wfp_org" title="View user profile.">
  <img src="http://10.11.32.35/fh463/files/pictures/picture-3933.jpg" alt="Emanuele.Quinto@wfp.org's picture" title="Emanuele.Quinto@wfp.org's picture">
  </a>
</div>
<input name="edit[picture_delete]" value="0" type="hidden">
<div class="description">
  Check this box to delete your current picture.
  </div>
<label class="option">
  <input class="form-checkbox" name="edit[picture_delete]" id="edit-picture_delete" value="1" type="checkbox">
  Delete picture
</label>
<dl>
  <dt for="edit-picture">Upload picture:</dt>
  <dd>
    <div class="description">
Your virtual face or picture.  Maximum dimensions are 85x85 and the 
maximum size is 30 kB. You can upload an image (30Kb, 80x80 px max).
    </div>
    <input class="form-file" name="edit[picture]" id="edit-picture" size="48" type="file">
  </dd>
</dl>  
*/  
  $form_emailpreference = $forms['account']['Email Preference']['data'];
  $form_emailpreference = "<dd>\n$form_emailpreference\n</dd>";
  $form_emailpreference = "<dt for='edit-mimemail_textonly'>" . t("Email preferences:") . "</dt>\n$form_emailpreference";
  $form_emailpreference = "<dl>\n$form_emailpreference\n</dl>";

  $form_partecipate .= $forms['account']['Donations link']['data'];
  $form_partecipate .= $forms_picture;
  $form_partecipate .= $forms['team_up']['team_up']['data'];
  $form_partecipate .= $forms['account']['Comment settings']['data'];
  $form_partecipate .= $form_emailpreference;
  $form_partecipate .= $link_top;
  $form_partecipate  = form_group("", $form_partecipate);
?>

<div class='profile_content'><a name='top'/>

<dl>
<dt><a href="<?php print $form_link ?>#label">Account information</a></dt>
<dd>Here you can edit info about your account (username, password...)</dd>
<dt><a href="<?php print $form_link ?>#personal">Personal information</a></dt>
<dd>Here you can edit your personal information (first name, second name, address...)</dd>
<dt><a href="<?php print $form_link ?>#team_up">Team up!</a></dt>
<dd>Here you can participate!</dd>
</dl>


<form action="<?php print $form_link ?>" method="post" enctype="multipart/form-data">

<!-- profile_left start -->
<div class='profile_left'>

<h2 class='profile'><a name='account'></a>Account information</h2>
<?php print $form_account ?>

<h2 class='profile'><a name='personal'></a>Personal information</h2>
<?php print $form_civicrm ?>

</div>
<!-- profile_left end -->

<!-- profile_right start -->
<div class='profile_right'>


<h2 class='profile'><a name='team_up'></a>Team up!</h2>
<?php print $form_partecipate ?>

</div>
<!-- profile_right end -->

<div style='clear: both;'>
<?php print form_submit(t('Submit')) ?>
</div>

</form>

</div>
<!-- user_edit start -->

<div style='clear: both;'>
<?php
/*
  $form_partecipate  = $forms['account']['Picture']['data'];

  $out .= "<table border='1'>";
  $out .= "<tr><td>#</td><td>title</td><td>module</td><td>category</td></tr>";
  foreach ($forms as $module) {
    foreach ($module as $group) {
      $out .= "<tr><td>" . $group['weight'] . "</td><td>" . $group['title'] . "</td>";
      $out .= "<td>" . $group['module'] . "</td><td>" . $group['category'] . "</td></tr>";
    }
  }
  $out .= "</table><hr />";
  print "<hr />$out";
*/  
?> 
</div>
