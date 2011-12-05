<?php

class avatarController extends Controller {
	private $method;
	
	public function index($id = '');
	
	public function icon($id = '') {
		$id = intval($id); // Userid
		
		if (isset($this->registry->user['avatar'])) {
			
		}
	}
}