<?php

class avatarController extends Controller {
	private $method;
	
	public function index($id = '') {
	   $this->get($this->registry->user['userid']);
	}
	
	public function get($id = '') {
		$id = intval($id); // Userid
		
        if (empty($this->registry->user['avatar'])) {
            $this->registry->user['avatar'] = './images/default_avatar.gif'; // Default avatar    
        }
        
        if (file_exists($this->registry->user['avatar'])) {
            header('Content-type: image/png'); // Needs work - Placeholder
            
            echo file_get_contents($this->registry->user['avatar']);
        }
	}
}