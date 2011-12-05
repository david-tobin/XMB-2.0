<?php
class streamModule {
	private $registry;
	
	public function __construct($registry) {
		$this->registry = $registry;
	}
	
	public function createStream($info=array()) {
		$streamlist = $this->registry->db->query("
        SELECT * FROM " . X_PREFIX . "stream
        ORDER BY streamid DESC
        ");
        
        $streams = '';
        foreach ($streamlist AS $stream) {
            $streamtemplate = 'stream_' . $stream['type'];
            $streamparams = unserialize($stream['params']);
            
            if (isset($streamparams['dateline'])) $streamparams['dateline'] = $this->registry->xmbtime('d-m-Y \a\t h:ia', $streamparams['dateline']);
            
            $this->registry->view->setvar('params', $streamparams);
            $this->registry->view->setvar('stream', $stream);
            $streams .= $this->registry->view->loadtovar($streamtemplate, 'index');
        }
        
        $this->registry->view->setvar('streams', $streams);
        
        return $streams;
	}
	
	public function addStream($info=array()) {
		if (!empty($info) && isset($info['streamer']) && isset($info['type']) && isset($info['params'])) {
			$stream = $this->registry->db->prepare("
				INSERT INTO " . X_PREFIX . "stream (streamer, type, params)
				VALUES (:streamer, :type, :params)
			");
			
			$stream->execute(array(
				':streamer'	=>	$info['streamer'],
				':type'		=>	$info['type'],
				':params'	=>	serialize($info['params'])
			));
		}
	}
}