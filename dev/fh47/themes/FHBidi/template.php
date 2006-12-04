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

  switch (_banner_type($file->filemime)) {
    case 'image':
      $img_attr = array(
        'width'  => $node->width,
        'height' => $node->height,
        'alt'    => '',
      );

      $url_attr = array('title' => $node->url);
      if ($node->target != '_none') {
        $url_attr['target'] = $node->target;
      }
      $output = l(theme('banner_image', file_create_url($file->filepath), $img_attr), 'node/'. $node->nid, $url_attr, NULL, NULL, FALSE, TRUE);
      break;
    case 'swf':
      $url = check_url(file_create_url($file->filepath));
      $output = <<<EOD
        <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0" width="$node->width" height="$node->height">
          <param name="movie" value="$url">
          <param name="quality" value="high">
          <embed src="$url" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="$node->width" height="$node->height"></embed>
        </object>
EOD;
      break;
    case 'text':
      // FIXME: used a couple of places, move to separate function?
      $path = file_create_path($file->filepath);
      if ($content = file_get_contents($path)) {
        if (strpos($content, '%url')) {
          // %url present in content, replace with banner link
          $output .= strtr($content, array('%url' => check_url(url('node/'. $node->nid))));
        }
        else {
          // no %url in content, add link at the end
          $output .= $content;
          $output .= ' '. l(t('&raquo;'), 'node/'. $node->nid, array(), NULL, NULL, FALSE, TRUE);
        }
      }
  }

  return $output;
}


?>
