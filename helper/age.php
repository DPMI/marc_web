<?php

/**
 * Show time difference in a readable format.
 */
function age($t){
	$diff = time() - strtotime($t);
	if ( $diff == 0 ) return 'just now';
	$day_diff = floor($diff / 86400);

	if($day_diff == 0){
		if ( $diff < 60) return "$diff seconds ago";
		if ( $diff < 3600) return floor($diff / 60) . ' minutes ago';
		if ( $diff < 86400) return floor($diff / 3600) . ' hours ago';
	}
	if($day_diff == 1) return '1 day ago';
	if($day_diff < 7) return $day_diff . ' days ago';
	return ceil($day_diff / 7) . ' weeks ago';
}
