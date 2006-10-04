<div id="content">

<!-- main-content start -->
<table id="content">
	<tr>
		<td class="main-content" id="content-<?php print $layout ?>">

<h2 class="content-title">My personal space</h2>

<!-- tabs start -->
<?php
// Better to change theme_menu_item_link !!!!!!!
function MY_menu_secondary_local_tasks() {
  $menu = menu_get_menu(); 
  
  $local_tasks = menu_get_local_tasks();
  $pid = menu_get_active_nontask_item();
  $output = '';

  if (count($local_tasks[$pid]['children'])) {
    foreach ($local_tasks[$pid]['children'] as $mid) {
      if (menu_in_active_trail($mid) && count($local_tasks[$mid]['children']) > 1) {
        foreach ($local_tasks[$mid]['children'] as $cid) {
          $link = explode('/', $menu['items'][$cid]['path']);
          $tab = $link[count($link)-1];
          switch ($tab) {
            case 'account':
            case 'Contact Information':
            $output .= theme('menu_local_task', $cid, menu_in_active_trail($cid), FALSE);
          }
        }
      }
    }
  }
  return $output;
}

if ($tabs != "") {

  print "<ul class=\"tabs primary\">\n". menu_primary_local_tasks() ."</ul>\n";
//  $mytabs = MY_menu_secondary_local_tasks();
//  print "<ul class=\"tabs secondary\">\n$mytabs</ul>\n";

//  print $tabs;
}
?>
<!-- tabs end -->

<?php if ($messages != ""): ?>
			<div id="message"><?php print $messages ?></div>
<?php endif; ?>
				
<!-- content start -->
<?php
//global $_menu;
//print "<pre>_menu:" . print_r($_menu, true) . "</pre>";

if (arg(2) == 'edit') {
/*
  if (arg(3) == 'Contact Information') {
    print $content; 
  } else {
    include('user_edit.tpl.php');
  }
*/
  include('user_edit.tpl.php');
//  print $content;
/*
  global $user;
  $account = user_load(array('uid' => arg(1)));
  $edit = $_POST['op'] ? $_POST['edit'] : object2array($account);
  $category = (arg(3)<>'') ? arg(3) : 'account';

  $hook = 'form';
  $groups = array();
  foreach (module_list() as $module) {
    if ($data = module_invoke($module, 'user', $hook, $edit, $account, $category)) {
      for($i=0; $i< count($data); $i++) {
        $data[$i]['module'] = $module;
        $data[$i]['category'] = $category;
      }
      $groups = array_merge($data, $groups);
    }
  }

  
  $out .= "<table border='1'>";
  $out .= "<tr><td>#</td><td>title</td><td>module</td><td>category</td></tr>";
  foreach ($groups as $group) {
    $out .= "<tr><td>" . $group['weight'] . "</td><td>" . $group['title'] . "</td>";
    $out .= "<td>" . $group['module'] . "</td><td>" . $group['category'] . "</td></tr>";
  }
  $out .= "</table><hr />";
  drupal_set_title($account->name);
  print $out;
*/ 
/*  
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
  
//  print "<pre>" . htmlentities(print_r($forms, true)) . "</pre>";
  $out = '';
  $form = $forms['account']['Account information']['data'];
  $out .= form_group("Account information *", $form);
 
  $form = $forms['Contact Information']['Contact Information']['data'];
  $form .= $forms['account']['Picture']['data'];
  $out .= form_group("Contact Information *", $form);
  
  $form = $forms['team_up']['team_up']['data'];
  $out .= form_group("Team Up! *", $form);

//  print $out;  

////////////  
/*
  $groups = array();
  $module = 'user';
  $category =	'account';
  if ($data = module_invoke($module, 'user', $hook, $edit, $account, $category)) {
    $groups = array_merge($data, $groups);
  }
  
  $module = 'civicrm';
  $category =	'Contact Information';
  if ($data = module_invoke($module, 'user', $hook, $edit, $account, $category)) {
    $groups = array_merge($data, $groups);
  }
  usort($groups, '_user_sort');
  $output = '';
  foreach ($groups as $group) {
    $output .= form_group($group['title'], $group['data']);
  }
  print $output;
*/  
  
/*
  $tmp = array('title' => 'Account information');
  $result = array_intersect($groups, array("1" => $tmp)); 

  print "<pre>result: " . print_r($result,true) . "</pre>";
*/
  
/*
  $output  = _user_forms($edit, $account, $category);
  $output .= form_submit(t('Submit'));
  if (user_access('administer users')) {
    $output .= form_submit(t('Delete'));
  }
  $output = form($output, 'post', 0, array('enctype' => 'multipart/form-data'));
*/


} else {
  print $content; 
}
?>
<!-- content end -->

		</td>
	</tr>
</table>
<!-- main-content end -->

</div>
