<?php
function print_table($tablename) {
	echo '<div class="head">'.$tablename.'</div>';
	echo '<table class="xtable">';
}

function print_cat($contents=array()) {
	echo '<tr>';

	foreach ($contents AS $key => $value) {
		echo '<td class="xhead">'.$value.'</td>';
	}

	echo '</tr>';
}

function print_table_end($footer='') {
	echo '</table>';
	echo '<div class="foot">'.$footer.'</div>';
}

function print_columns($columns=array()) {
	echo '<tr>';
	
	foreach ($columns AS $key => $value) {
		echo '<td class="alt1">';
		echo $value;
		echo '</td>';
	}
	
	echo '</tr>';
}

function print_page($sidebar_title='', $sidebar_contents = array()) {
	echo '<table class="xtable"><tr>';
	echo '<td class="sidebar">';
	
	echo '<div class="sidebar-title">'.$sidebar_title.'</div>';
					
	foreach ($sidebar_contents AS $key => $value) {
		echo '<div class="sidebar-inset">';
		echo $value;
		echo '</div>';	
	}
	
	echo '</td>';
	echo '<td class="content">';
}