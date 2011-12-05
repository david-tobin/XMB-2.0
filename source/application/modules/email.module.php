<?php
class emailModule {
	private $registry;

	public $recipients = array();

	public $subject = '';

	public $message = '';

	public function __construct($registry) {
		$this->registry = $registry;
	}

	public function send() {
		$headers  = 'MIME-Version: 1.0' . "\n";
		$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\n";
		$headers .= "X-Priority: 1 (Higest)\n";
        $headers .= "X-MSMail-Priority: High\n";
        $headers .= "Importance: High\n";
		$headers .= 'From: XMB 2.0 Development <development@localhost.com>' . "\n";
		
		mail(implode(',', $this->recipients), $subject, $message, $headers, '-fdtobin08@gmail.com');
	}
}

?>