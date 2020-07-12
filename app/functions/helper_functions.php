<?php
function initials($name)
{
	$initials_html = '';
	$initials = '';
	if($name){
		$initials = explode(' ',$name);

		$initials_html = '<div class="reserved_initials">';
		foreach($initials as $initial){
			$initials_html = $initials_html.substr($initial,0,1);

		}
		$initials_html = $initials_html.'</div>';
	}
	return $initials_html;
}
?>