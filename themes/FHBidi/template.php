<?

function phptemplate_stylesheet_import($stylesheet, $media = 'all') {
  $rtl = in_array(locale_initialize(), array('ar', 'fa', 'he', 'ur'));
  if (!$rtl) {
    return theme_stylesheet_import($stylesheet, $media);
  }
  if ($stylesheet == base_path() . 'misc/drupal.css') {
    $stylesheet = 'misc/drupal-rtl.css';
  }
  if ($stylesheet == base_path() . path_to_theme() . '/style.css') {
    $stylesheet = base_path() . path_to_theme() . '/style-rtl.css';
  }
  return theme_stylesheet_import($stylesheet, $media);
}


/*
function phptemplate_form_element($title, $value, $description = NULL, $id = NULL, $required = FALSE, $error = FALSE){
  if ((arg(0) == "F1") || (arg(1) == "F1")){
    $output = f1_form_element($title,$value,$description,$id,$required,$error);
  } else {
    if ($title){
      $output  = "\n<dl>\n";
    }
  
    $required = $required ? theme('mark') : '';
  
    if ($title) {
      if ($id) {
        $output .= " <dt for=\"$id\">$title:$required</dt>\n";
      }
      else {
        $output .= " <dt>$title:$required</dt>\n";
      }
      $output .= "<dd>";
    }
  
    
    if ($description) {
      $output .= " <div class=\"description\">$description</div>";
    }
    if ($error){
      $output .= " <div class=\"error\">$error</div>";
    }
  
    $output .= "$value";
  
    if ($title){
      $output .= "</dd>\n</dl>\n";
    }
  }
  
  return $output;
}
*/
function phptemplate_gathering_btn_create($uri) {
  $out  = "<a href='".$uri."'>\n";
  $out .= "<img src='".path_to_theme()."/images/event_create.png' width='79' height='80' class='event_create'/>";
  $out .= "</a>";
  return $out;
}

function phptemplate_item_list($items = array(), $title = NULL) {
  return _phptemplate_callback("item_list",array('items' => $items, 'title' => $title));
}


function phptemplate_mark() {
  return '<span class="marker">*</span>';
}

function phptemplate_gathering_node($node, $fields=NULL) {
//  return _phptemplate_callback("gathering_node", $node);
  $sql = "SELECT * FROM {gathering_campaign} WHERE camid=%d";
  $fields = db_fetch_array(db_query($sql, $node['camid']));
  return _phptemplate_callback("gathering_node",array('node' => $node, 'fields' => $fields));
}


function phptemplate_user_profile($user, $fields) {
  $user->longcountry = fh_get_country_name($user->profile_country);
//  $user->longcountry = $user->profile_country;
//  $fields['donation_obj'] = donation_prepare($user->donation);
  return _phptemplate_callback("user_profile",array('user' => $user, 'fields' => $fields));
}

/*
function phptemplate_user_edit($form) {
//  $user->longcountry = fh_get_country_name($user->country);
//  $user->longcountry = $user->profile_country;
//  $fields['donation_obj'] = donation_prepare($user->donation);

  return _phptemplate_callback('user_edit',array('form' => $form));
}

function phptemplate_user_register($form) {
//  $user->longcountry = fh_get_country_name($user->country);
//  $user->longcountry = $user->profile_country;
//  $fields['donation_obj'] = donation_prepare($user->donation);
  $form_tmp = $form['account']; unset ($form['account']);
  $form['account']['account'] = $form_tmp;
  $form_tmp = $form['team_up']; unset ($form['team_up']);
  $form['team_up']['team_up'] = $form_tmp;
  $form_tmp = $form['Personal Information']; unset ($form['Personal Information']);
  $form['Personal Information']['Personal Information'] = $form_tmp;
  unset($form['civicrm-profile-register']);
  return _phptemplate_callback('user_edit',array('form' => $form));
// return "paperina" . form_render($form);
}
*/

function phptemplate_gathering_btn_walk() {
  $ats['src'] = path_to_theme()."/images/btn_walk.png";
  return form_button(t("Walk"),NULL,"image",$ats);
}

function phptemplate_donation_link($dl) {
  return _phptemplate_callback('donation_link',array('dl' => $dl)); 
}

function phptemplate_donation_btn_donate() {
//   return form_submit(t("Donate"));
// <input class="form-submit" name="op" value="Donate" type="submit">
// <div class="donation_link">
// <a href="donation/986">Donate to this event</a></div>
//  return form_submit(t("Donate"));
}

/* Needs updating for 4.7
function phptemplate_donation_presentation($donation) {
  switch($donation->type) {
    case 'plain':
      $out = "<div class='info'>$donation->presentation</div>";
      $out =  form_group(t('Donation details'), $out );
      break;
    case 'event':
    case 'fee':
      $link = l(_donation_list($donation->nid), "node/$donation->nid");
      $out = "<div class='info'><div class='links'>" . t('details') . ": $link</div></div>";
      break;
    case 'campaign':
      $camid = substr($donation->nid,1);
      $link = l($donation->title, "gathering/home/$camid");
      $out  = "<div class='info'>";
      $out .= "$donation->presentation";
      $out .= "<div class='links'>" . t('details') . ": $link</div>";
      $out .= "</div>";
      $out =  form_group(t('Donation details'), $out );
  }
  return  $out;
}
*/

function phptemplate_settings() {
    $settings = variable_get('theme_FHBidi_settings', array());
    $form['banner'] = array(
      '#type' => 'fieldset',
      '#title' => t('Banner image settings'),
      '#description' => t('Following banner will be displayed.'),
      '#attributes' => array('class' => 'theme-settings-bottom'),
    );
    $form['banner']['banner_path'] = array(
      '#type' => 'textfield',
      '#title' => t('Path to custom banner'),
      '#default_value' => $settings['banner_path'],
      '#description' => t('The path to the file you would like to use as your banner file.')
    );
    $form['profile'] = array(
      '#type' => 'fieldset',
      '#title' => t('User profile settings'),
      '#attributes' => array('class' => 'theme-settings-bottom'),
    );
    $form['profile']['FH_profile_account'] = array(
      '#type' => 'textarea',
      '#title' => t('Account Information'),
      '#default_value' => $settings['FH_profile_account'],
      '#description' => t('Account Information help text')
    );
    $form['profile']['FH_profile_PersonalInformation'] = array(
      '#type' => 'textarea',
      '#title' => t('Personal Information'),
      '#default_value' => $settings['FH_profile_PersonalInformation'],
      '#description' => t('Personal Information help text')
    );
    $form['profile']['FH_profile_TeamUp'] = array(
      '#type' => 'textarea',
      '#title' => t('Team Up'),
      '#default_value' => $settings['FH_profile_TeamUp'],
      '#description' => t('Team Up help text')
    );
    return $form;
}

/**
 * Produces a language link without icon
 */
function phptemplate_i18n_link($text, $target, $lang, $separator='&nbsp;'){
  $output = '<span class="i18n-link">';
  $attributes = ($lang == i18n_get_lang()) ? array('class' => 'active') : NULL;
  $output .= l($text, $target, $attributes, NULL, NULL, FALSE, TRUE);
  $output .= '</span>';
  return $output;
}

/**
 * User profile view
 */
function phptemplate_fhuser_profile($fields) {
  $account = $fields['_account']['#value'];
  $categories = $fields['_categories']['#value'];


  // Set user name
  if( ($fields['Personal Information']['first_name']['#value']<>'') || 
      ($fields['Personal Information']['last_name']['#value']<>'') ) {
    $user_name = trim($fields['Personal Information']['first_name']['#value'] . 
      ' ' . $fields['Personal Information']['last_name']['#value']); 
  } else {
    $user_name = $account->name;
  }
  $fields['Personal Information']['profile_username'] = array(
            '#type'   => 'item', 
            '#attributes'   => array('class' => 'profile_username'),
            '#theme'  => 'profile_item', 
            '#value'  => t('My name is') . " $user_name",
            '#weight' => -10
          );
  unset($fields['Personal Information']['first_name']);
  unset($fields['Personal Information']['last_name']);
 
  // Set country 
  if($fields['Personal Information']['country']['#value']<>'') {
    $fields['Personal Information']['country']['#title'] = '';
    $fields['Personal Information']['country']['#value'] = t("I live in") . ' '.
      $fields['Personal Information']['country']['#value']; 
  } else {
    unset($fields['Personal Information']['country']);
  }
  unset($fields['Location']);
  
  // Set profile_presentation field
  if( $pres_val = $fields['Personal Information']['profile_presentation']['#value'] ) {
    $pres_val = $fields['Personal Information']['profile_presentation']['#value'];
    $fields['Personal Information']['profile_presentation']['#title'] = t("Why I'm supporting Fight Hunger?");
    $fields['Personal Information']['profile_presentation']['#attributes'] = array('class' => 'profile_motivation');
  }

  // Set flickr
  if($fields['Personal Information']['profile_flickr']['#value']) {
    $fields['Personal Information']['profile_flickr']['#title'] = '';
    $fields['Personal Information']['profile_flickr']['#value'] = 
      theme('profile_flickr', $account);
  }
  
  // Set blog
  if($fields['Personal Information']['profile_blog']['#value']) {
    $fields['Personal Information']['profile_blog']['#title'] = '';
    $fields['Personal Information']['profile_blog']['#value'] = 
      theme('profile_blog', $account);
  }
  
  // Set profile_delicious
  if($fields['Personal Information']['profile_delicious']['#value']) {
    $fields['Personal Information']['profile_delicious']['#title'] = '';
    $fields['Personal Information']['profile_delicious']['#value'] = 
      theme('profile_delicious', $account);
  }
    
  // Change fieldset to profile_set  
  foreach($fields['_categories']['#value'] as $key_set => $cat) {
      $fields[$key_set]['#attributes'] = array('class' => 'profile_set', 'id' => str_replace(' ', '', $key_set) );
      $fields[$key_set]['#type'] = 'profile_set';
  }

  // Change item to profile_item (ONLY for 'Personal Information')
  foreach($fields['Personal Information'] as $key_item => $value_item) {
    if( $key_item{0} <> '#' ) {
      $fields['Personal Information'][$key_item]['#theme'] = 'profile_item';
    }
  }

  // Set picture
  if($account->picture) {
    $fields['Personal Information']['profile_picture'] = array('#value' => theme('user_picture', $account), '#weight' => -11);
  }

//$output .= _print_cat($fields['Team Up']);
//$output .= _print_cat($fields['Personal Information']);
//$output .= _print_cat($fields['_categories']['#value']);
//$output .= _print_cat($fields);

  $output = "<div class='profile'>\n\n";
  $output .= "<div class='profile_left'>\n";
  $output .= form_render($fields['Personal Information']);
  $output .= "\n</div>\n\n";
  $output .= "<div class='profile_right'>\n";
  $output .= form_render($fields['Team Up']);
  $output .= "\n</div>\n\n";
  $output .= "<div style='clear: both;'>\n";
  $output .= form_render($fields);
  $output .= "\n</div>\n\n";
  $output .= "</div>\n";

//$output .= _print_cat($fields['Personal Information']);
//$output .= _print_cat($fields['Team Up']);
//$output .= _print_cat($fields);

  return $output;
}

/**
 * User edit
 */
function phptemplate_fhuser_user_edit($form) {

//  $output  .= _print_cat($form['_categories']['#value']);
//  $output  .= _print_cat($form);
//  $output  .= _print_cat($form['account']['theme_select']);
//  $output .= _print_cat($form['Team Up']);
//  $output .= _print_cat($form['Personal Information']);

  // Move 'Personal Information' in 'account' and reorder
//  $form['account']['personal_information'] = $form['Personal Information'];
//  unset($form['Personal Information']);
  
  // Reorder account fieldset
  $form['account']['account']['#weight'] = -19;
  $form['account']['personal_information']['#weight'] = -18;
  $form['account']['picture']['#weight'] = -17;
  $form['account']['locale']['#weight'] = -16;
  $form['account']['comment_settings']['#weight'] = -15;
  $form['account']['timezone']['#weight'] = -14;
  $form['account']['theme_select']['#weight'] = -13;

  // Collapse fields
  $form['account']['locale']['#collapsible']  = 1;
  $form['account']['comment_settings']['#collapsible']  = 1;
  $form['account']['timezone']['#collapsible']  = 1;
  $form['account']['theme_select']['themes']['#collapsible']  = 1;
  
  $form['account']['locale']['#collapsed']  = 1;
  $form['account']['comment_settings']['#collapsed']  = 1;
  $form['account']['timezone']['#collapsed']  = 1;
  $form['account']['theme_select']['themes']['#collapsed']  = 1;

  // Arrange Team Up
  $form['Team Up']['fundraising'] = $form['account']['fundraising'];
  unset($form['account']['fundraising']);
  
  // Set theme issue
  $item_button = array('#value' => "\n<p class='button'><a href='#top'>" . t('top') ."</a></p>\n",
                       '#weight' => 99);
  $settings = variable_get('theme_FHBidi_settings', array());
  $out_header = "<dl>\n";
  foreach($form['_categories']['#value'] as $key_set => $cat) {
    $id = str_replace(' ', '', $key_set);
    $form[$key_set]['button'] = $item_button;
    $form[$key_set]['#attributes'] = array('class' => 'profile_set', 'id' => "$id" );
    $form[$key_set]['#type'] = 'profile_set';
    $out_header .= "<dt><a href='#$id'>" . $cat['title'] . "</a></dt>\n";
    $out_header .= "<dd>" . $settings["FH_profile_$id"] . "</dd>\n";     
  }
  $out_header .= "</dl>\n";

//  $output  .= _print_cat($form['account']);
//  $form['#prefix'] = '<div class="profile">';
//  $form['#suffix'] = "</div>\n<div style='clear: both;'>&nbsp;</div>\n";

  // Renders elements
  $output .= "<div class='profile'><a name='top'/>\n\n";
  $output .= "$out_header \n";
  
  $output .= "<div class='profile_left'>\n";
  $output .= form_render($form['account']);
  $output .= form_render($form['Personal Information']);
  $output .= "\n</div>\n\n";
  $output .= "<div class='profile_right'>\n";
  $output .= form_render($form['Team Up']);
  $output .= "\n</div>\n\n";
  $output .= "<div style='clear: both;'>\n";
  $output .= form_render($form);
  $output .= "\n</div>\n\n";
  $output .= "</div>\n";
  
  return $output;
}

/**
 * Produces themed link (with icon) for profile flickr field
 */
function phptemplate_profile_flickr($account) {
  $output ='';
  if($account->profile_flickr) {
    $output  = "<img src='/" . path_to_theme() . "/images/flickricon.jpg' class='profile_icon'>";
    $output .= "<a href='$account->profile_flickr'>" . t('My Flickr photos') . "</a>";
  }
  return $output;
}

/**
 * Produces themed link (with icon) for profile blog field
 */
function phptemplate_profile_blog($account) {
  $output ='';
  if($account->profile_blog) {
    $output  = "<img src='/" . path_to_theme() . "/images/feed-icon.png' class='profile_icon'>";
    $output .= "<a href='$account->profile_blog'>" . t('My blog/web site') . "</a>";
  }
  return $output;
}

/**
 * Produces themed link (with icon) for profile delicious field
 */
function phptemplate_profile_delicious($account) {
  $output ='';
  if($account->profile_delicious) {
    $output  = "<img src='/" . path_to_theme() . "/images/delicious.gif' class='profile_icon'>";
    $output .= "<a href='$account->profile_delicious'>" . t('My delicious tags') . "</a>";
  }
  return $output;
}

function phptemplate_profile_item($element) {
  if ( isset($element['#attributes']['class']) ){
    $element['#attributes']['id'] = $element['#attributes']['class'];
    $element['#attributes']['class'] = 'profile_item';  
  } else {
    $element['#attributes']['class'] = 'profile_item';  
  }

  $out = '<div' . drupal_attributes($element['#attributes']) . ">\n";
  if ($element['#title']) {
    $out .= '<h3>'. t($element['#title']) . "</h3>\n";
  }
  $out .= $element['#value'] . $element['#children'] . "\n";
  if ($element['#description']) {
    $out .= ' <div class="description">'. $element['#description'] ."</div>\n";
  }
  $out .= "</div>\n";
  return $out;
} 

/**
 * Theme functions for user page:
 * @ingroup themeable
 */
function phptemplate_profile_set($element) {
  $out = '<fieldset' . drupal_attributes($element['#attributes']) . ">\n";
  if ($element['#attributes']['id']) $out .= "<a name='" . $element['#attributes']['id'] . "'>&nbsp;</a>\n";
  if ($element['#title']) {
    $out .= '<h2>'. t($element['#title']) . "</h2>\n";
  }
  $out .= $element['#value'] . $element['#children'] . "\n";
  if ($element['#description']) {
    $out .= ' <div class="description">'. $element['#description'] ."</div>\n";
  }
  $out .= "</fieldset>\n";
  return $out;
}

/**
 * Theme a user page
 * 
 * @ingroup themeable
 */
function _print_cat($cat) {
 if($cat) {
   $out .= "<h3>" . $cat['#title'] . "</h3>";
   $out .= "<table border='1'>";
   foreach($cat as $key => $val) {
     $out .= "<tr><td>$key</td><td>" . check_plain(print_r($val, true)) . "</td></tr>";
   }
   $out .= '</table>';
 }
 return $out;
}

/**
 * Theming forward form
 *
 * @param $form
 *   form array
 */
/* 
function phptemplate_forward_form($form) {
  $form['message']['#title'] = t('Tell a friend about FightHunger.org');
  $form['message']['#collapsed'] = FALSE;
  return form_render($form);
}
*/
/**
 * Format email forward
 *
 * @param vars
 *   An array of email variables
 */
function phptemplate_forward_email($vars) {

	$style = "<style>
      <!--
      	html, body {margin:0; padding:0; background-color:#fff;}
      	#container {margin:0 auto; width:670px; font:normal 10pt arial,helvetica,sans-serif;}
        #header {width:670px; margin:0; text-align:center;}
      	#body {width:630px; margin:0; padding:5px 20px; text-align:left; background-color:#fff;}
      	#footer {width:670px; height:35px; margin:0; padding:5px 0 0 0; font-size:9pt; text-align:center; color:#fff;}
        .ad_footer, .message, .article  {font-size:10pt; padding:0;}
      	.frm_title, .frm_txt {font-size:12pt;}
        .frm_txt {padding-bottom:15px;}
        .links {font-size:10pt; font-style:italic;}
        .article_title {font-size:12pt;}
        .dyn_content { padding-top:10px;}
      -->
    </style>";

  $output = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
  <head>
    '.$style.'
    <base href="'.url('',NULL,NULL,TRUE).'" />
  </head>
  <body>
    <div id="container">
      <div id="header">'.l('<img src="'.$vars['forward_header_image'].'" border="0" alt="'.$vars['site_name'].'">', '',NULL,NULL,NULL,TRUE,TRUE).'</div>
      <div id="body">
        <div class="frm_txt">'.$vars['forward_message'].'</div>';
        if ($vars['message']) { $output .= '
        <div class="frm_title">'.t('Message from Sender').':</div>
        <div class="frm_txt"><p><b>'.$vars['message'].'</b></p></div>';}
        $output .= '
        <div><b>'.l($vars['content']->title, 'forward/'.$vars['content']->nid.'/email_ref',NULL,NULL,NULL,TRUE).'</b>';
        if (theme_get_setting('toggle_node_info_'.$vars['content']->type)) { $output .= '
        <br /><i>'.t('by %author', array('%author' => $vars['content']->name)).'</i>';}
        $output .= '
        </div>
        <div class="article">'.$vars['content']->teaser.'</div>
        <div class="links">'.l(t('Click here to read more on our site'), 'home',NULL,NULL,NULL,TRUE).'</div>
        <div class="dyn_content"><br />'.$vars['dynamic_content'].'</div>
        <div class="ad_footer"><br />'.$vars['forward_ad_footer'].'<br></div>
      </div>
      <div id="footer">'.$vars['forward_footer'].'</div>
    </div>
  </body>
</html>';

  return $output;
}

/**
 * Theme uploaded banner (image/swf)
 *
 * @param $node
 *   Node object
 * @return
 *   Themed banner
 */
function phptemplate_banner_view_upload($node) {
  $output = '';

  // get first attached file
  if ($node->files) {
    foreach ($node->files as $key => $file) {
      $file = (object)$file;
      if ($file->list && !$file->remove) {
        break; // we only need the first listed file
      }
    }
  }

  $img_attr = array(
    'width'  => $node->width,
    'height' => $node->height,
    'alt'    => '',
  );

  $url_attr = array('title' => $node->url);
  if ($node->target != '_none') {
    $url_attr['target'] = $node->target;
  }
  $output = l(theme('banner_image', file_create_url($file->filepath), $img_attr), $node->url, $url_attr, NULL, NULL, FALSE, TRUE);

  return $output;
}


/**
 * Theme fhbat banner
 *
 * @param $node
 *   Node object
 * @return
 *   Themed banner
 */
function phptemplate_fhbat_banner_view($node) {
  $output = '<b>One child has been fed by:</b><br />';
  $output .= theme('banner_view_upload', $node) . '<br />';
  $output .= $node->content;
  return "<center>$output</center>";
}

/**
 * Theme functions for aggregator block:
 * @ingroup themeable
 */
function phptemplate_aggregator_block_item($item, $feed = 0) { 
  global $user; 
  
  if ($user->uid && module_exist('blog') && user_access('edit own blog')) { 
    if ($image = theme('image', 'misc/blog.png', t('blog it'), t('blog it'))) { 
      $output .= '<div class="icon">'. l($image, 'node/add/blog', array('title' => t('Comment on this news item in your personal blog.'), 'class' => 'blog-it'), "iid=$item->iid", NULL, FALSE, TRUE) .'</div>'; 
    } 
  } 
  
  // Display the external link to the item. ADD redirection to a new window 
  $output .= "<a href='". check_url($item->link) . "' target='nwin'>". check_plain($item->title) ."</a>\n"; 
  
  return $output; 
}

/**
 * Theme function to render product node.
 */
function phptemplate_node_product($node, $teaser = 0, $page = 0) {
  $theme = 'product_'. $node->ptype . '_view';
  if (theme_get_function($theme)) {
    $node = theme($theme, $node, $teaser, $page);
  } else {
    $price_string = '<div class="price"><strong>'. t('Price') .'</strong>: ' . module_invoke('payment', 'format', product_adjust_price($node)+product_get_specials($node, true)) . '</div>';
    if ($node->is_recurring) {
      $price_string .= '<div class="recurring-details">'. product_recurring_nice_string($node) . '</div>';
    }
    $node->teaser .= $price_string;
    $node->body .= $price_string;
  }

  foreach ($node->taxonomy as $term) {
    if( $term->vid == variable_get('fhcommerce_store_vocabulary',5) ) {
      $node->store  = $term->tid;
      $node->store_link = l($term->name, 'store/' . $node->store, array('rel' => 'tag', 'title' => strip_tags($term->description)));
    }
  }

  return $node;
}


function phptemplate_store_product_table($result) {
	$number = db_num_rows($result);
	if ($number>0)
    $output = "<div class='product-table'>"; 
    while ($node = db_fetch_object($result)) {
      	$output .= "<div class='product'>" . node_view(node_load($node->nid), FALSE) . '</div>';
    }
    $output .= "</div>"; 
  return $output;	
}

function phptemplate_store_product_list($result) {
  if (db_num_rows($result)) {
    $output .= theme('pager', NULL, variable_get('default_nodes_main', 10), 0);  
    $output .= "<div class='product-list'><ul>";
    while ($res = db_fetch_object($result)) {
      $node = node_load($res->nid);
      if ($node->is_recurring) {
        $price_string .= '<div class="recurring-details">'. product_recurring_nice_string($node) . '</div>';
      } else {
        $price = module_invoke('payment', 'format', product_adjust_price($node)+product_get_specials($node, true));
        $price_string = "<div class='price'>$price</div>";
      }
      $output .= '<li>' . l($node->title, 'node/' . $node->nid) . " - $price_string</li>";
    }
    $output .= "\n</ul></div>\n";
    $output .= theme('pager', NULL, variable_get('default_nodes_main', 10));
  }
  return $output;
}

function phptemplate_subproducts_in_cart($table) {
  return;
}


/********************************************************************
 * Themeable Functions
 ********************************************************************/

function phptemplate_store_invoice($txn, $print_mode = TRUE, $trial = FALSE) {
  global $base_url;

  $header = array();
  $row    = array();

  if (empty($txn->mail) && $txn->uid > 0) {
    $txn->mail = db_result(db_query('SELECT mail FROM {users} WHERE uid = %d', $txn->uid));
  }

  if ($txn->items) {
    $header = array(t('Quantity'), t('Item'), t('Unit Price'), t('Total Price'));

    $shippable = FALSE;
    foreach ($txn->items as $p) {
      $prod = product_load($p);
      if (product_is_shippable($p->vid)) $shippable = TRUE;

      $price = store_adjust_misc($txn, $p);
      $price_total = product_has_quantity($p) ? $p->qty * $price : $price;

      $subtotal += $price_total;
      $details = '';
      if (is_array($p->data)) {
        foreach ($p->data as $key => $value) {
          if ($value) {
            $items[] = '<strong>'. check_plain($key). ': </strong>'. check_plain($value);
          }
        }
        if ($items) {
          $details = theme('item_list', $items);
        }
      }

      $row[] = array(array('data' => $p->qty, 
                           'align' => 'center', 
                           'valign' => 'top'), 
                           '<em>'. check_plain($p->title). '</em> '. (($prod->sku != '') ? "[". check_plain($prod->sku) ."]" : ''). '<br />'. $details, 
                     array('data' => payment_format($price), 'valign' => 'top', 'align' => 'right'),
                     array('data' => payment_format($price_total), 'valign' => 'top', 'align' => 'right')
                     );
    }

    if (is_array($txn->misc)) {
      foreach ($txn->misc as $misc) {
        if (!$misc->seen) {
          $row[] = array(array('data' => t("<strong>{$misc->description}</strong>: %price", array('%price' => payment_format($misc->price))), 'colspan' => 4, 'align' => 'right'));
        }
      }
    }

    $row[] = array(array('data' => '<hr size="1" noshade="noshade" />', 'colspan' => 4, 'align' => 'right'));
    $row[] = array(array('data' => t('<strong>Total:</strong> %total', array('%total' => payment_format(store_transaction_calc_gross($txn)))), 'colspan' => 4, 'align' => 'right'));
  }

  $payment_info  = t('<div><strong>Ordered On:</strong> %order-date</div>', array('%order-date' => format_date($txn->created)));
  if ($txn->duedate) {
    $payment_info.= t('<div><strong>Due Date:</strong> %due-date</div>', array('%due-date' => format_date($txn->duedate)));
  }
  $payment_info .= t('<div><strong>Transaction ID:</strong> %txnid</div>', array('%txnid' => $trial ? t('Trial Invoice - Not Yet Posted') : $txn->txnid));

  $css        = base_path(). drupal_get_path('module', 'store') .'/invoice.css';
  $site_name  = t('%site-name Invoice', array('%site-name' => variable_get("site_name", "drupal")));

  if ($shipping_to = store_format_address($txn, 'shipping', 'html')) {
    $shipping_label = t('Shipping to');
  }

  if ($billing_to = store_format_address($txn, 'billing', 'html')) {
    $billing_label = t('Billing to');
  }

  if ($txn->ship) {
    $shipping_method_label = t('Shipping method:');
    $shipping_method = store_format_shipping_method($txn);
  }
  $email_label = t('E-mail:');
  $items_label = t('Items ordered');
  $items_view = theme('table', $header, $row, array('cellpadding' => 3, 'cellspacing' => 3));

  $payment_label = t('Payment Info');

  if ($print_mode) {
    $output .= <<<EOD
<html>
  <head>
    <style type="text/css" media="all">@import url('$css');</style>
  </head>
  <body>    
EOD;
  }

$logo = theme_get_setting('logo');
$site_slogan = variable_get('site_slogan', ''); 
$site_mission = variable_get('site_mission', '');
$banner = base_path() . theme_get_setting('banner_path');
$output .= <<<EOD

<table border="0" width='100%'>
  <tr>
    <td width='180'>
<img src="$logo" alt="logo" title="Index Page" />
    </td>
    <td>
<img src="$banner" alt="banner" title="banner" />
    </td>
    <td align="center" cellpadding='5'>
<span id="site-mission">$site_mission</span>
    </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td colspan=3>
<span id="site-slogan">$site_slogan</span>
    </td>
  </tr>
  <tr>
    <td colspan=3  align="center">
WFP, Via Cesare Giulio Viola, 68/70, Parco de'Medici, 00148, Rome, Italy<br/>
www.FightHunger.org - team@fighthunger.org
    </td>
  </tr>
</table>

    <h1>$site_name</h1>

    <table cellspacing="5">
      <tr>
        <th align="left">$shipping_label</th>
        <th align="left">$billing_label</th>
      </tr>
      <tr>
        <td>$shipping_to</td>
        <td>$billing_to</td>
      </tr>
    </table>

    <p><strong>$shipping_method_label</strong> $shipping_method</p>
    <p><strong>$email_label</strong> $txn->mail</p>

    <h2>$items_label</h2>
    $items_view

    <h2>$payment_label</h2>
    $payment_info
EOD;

if ($print_mode) {
  $output .= <<<EOD
    </body>
  </html>
EOD;
}

  if (!$print_mode) {  
    return $output;
  }
  print $output;
}

?>
