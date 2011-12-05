<?php

// Logout inactive users
$this->registry->db->query("
	DELETE FROM " . X_PREFIX . "session
	WHERE lastactive < " . X_TIME . "-(60*15)
");

?>