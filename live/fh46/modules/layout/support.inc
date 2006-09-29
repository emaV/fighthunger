<?php

function layout_this_week($dow=1, $week_offset=0, $format = "")
{
	$days = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
	
	$today_dow = date('w');
	
	$step = "this";
	if ( $dow != 0 && $dow < $today_dow )
	{
		$step = "last";
	}
	else if ( $dow != 0 && $today_dow == 0 )
	{
		$step = "last";
	}

	$when = "$step " . $days[$dow];
	
	$now = strtotime($when);
	
	if ( $week_offset > 0 )
	{
		$now = strtotime('+' . $week_offset . "weeks", $now);
	}
	else if ( $week_offset < 0 )
	{
		$now = strtotime($week_offset . "weeks", $now);
	}
	
	if ( $format && strlen($format) > 0 )
	{
		return date($format, $now);
	}
	else
	{
		return $now; // $day;
	}
	
}

function layout_this_week_range($week_offset=0, $format = "")
{
	
	$start = layout_this_week(1, $week_offset);
	$end = layout_this_week(0, $week_offset);
	
	
	if ( !$format || strlen($format) ==  0 )
	{
		if ( $start->year == $end->year )
		{
			$format = 'M j';
		}
		else
		{
			$format = 'M j Y';
		}		
		
	}
	
	return date($format, $start) . ' - ' . date($format, $end);
}

function layout_this_month($month_offset, $format = "")
{

}

function layout_sql_this_week($clip=false)
{
	global $user;
	
	$first = layout_this_week(1);
	$last = layout_this_week(1, 1);

    
  if (variable_get('event_timezone', '1')) {
  
    $first -= $user->timezone;
    $last -= $user->timezone;
  }

//			Drupal_set_message('Time():' . time() . ', strtotime()' . strtotime('now') . ', diff' . ((strtotime('now')-time())/3600));
	
//	    Drupal_set_message('First: ' . event_format_date($first));
//	    Drupal_set_message('Last: ' . event_format_date($last));
   
  $sql .= "(start >= $first AND start < $last)";
  
  return $sql;
}

function layout_sql_this_month($value, $clip=false)
{

}

function layout_mixed_type($node)
{
	switch ( $node->type )
	{
		case 'event':
			$output = event_format_date_range($node);
			$output .= '<br>';
			$output .= event_city($node, true);
			$output .= " - ";
			$output .= $node->title;
			break;
			
		case 'alerts':
			$output = $node->title;
			$output .= alert_due($node, " - ");
			break;
		default:
			$output = $node->title;
	}
	
	return $output;
}


?>
