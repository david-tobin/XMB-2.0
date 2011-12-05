<?php

function onoff($var, $value='') {
	$one = ($value == 1) ? 'selected' : '';
	$two = ($value == 0) ? 'selected' : '';
	echo '<select class="login-input" name="settings['.$var.']">';
	echo '<option value="1" '.$one.'>On</option>';
	echo '<option value="0" '.$two.'>Off</option>';
	echo '</select>';
}

function yesno($var, $value='') {
	$one = ($value == 1) ? 'selected' : '';
	$two = ($value == 0) ? 'selected' : '';
	echo '<select class="login-input" name="settings['.$var.']">';
	echo '<option value="1" '.$one.'>Yes</option>';
	echo '<option value="0" '.$two.'>No</option>';
	echo '</select>';
}

function free($var, $value='') {
	echo '<input class="login-input" size="40" type="text" name="settings['.$var.']" value="'.$value.'" />';
}

function textarea($var, $value) {
	echo '<textarea class="login-input" cols="38" rows="5" name="settings['.$var.']">'.$value.'</textarea>';
}

?>