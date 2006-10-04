<?

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
  $countries = _gathering_get_countries();
  $user->longcountry = $countries[$user->country];
  $fields['donation_obj'] = donation_prepare($user->donation);
  return _phptemplate_callback("user_profile",array('user' => $user, 'fields' => $fields));
}

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
  return form_submit(t("Donate"));
}

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


?>
