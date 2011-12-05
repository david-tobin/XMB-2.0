<?php

class attachmentModel extends Model
{
	/**
	 * (non-PHPdoc)
	 * @see app/application/Model#index($id)
	 */
	public function index($id)
	{

	}

	public function upload($id)
	{
		$this->registry->usercp_method = 'upload';

		$content = $this->registry->view->loadtovar($this->registry->usercp_method, $this->
			registry->controller);

		$this->registry->view->setvar('content', $content);
	}

	public function doupload($id)
	{
		$this->registry->cleaner->clean('f', 'attachment', 'TYPE_ARRAY');

		$file = $this->registry->cleaner->attachment;

		require_once (X_PATH . '/application/datahandlers/attachment.datahandle.php');

		$attachment = new XMB_Datahandler_Attachment($this->registry);

		$file['data'] = $attachment->get_temp_file($file['tmp_name']);

		$attachment->set_info('name', $file['name']);
		$attachment->set_info('size', $file['size']);
		$attachment->set_info('data', $file['data']);
		$attachment->set_info('type', $file['type']);
		$attachment->set_info('pid', 0);

		if ($attachment->build() == 1)
		{
			$this->registry->redirect('/attachment/');
		}
	}

	public function myattachments($id)
	{
		$files = scandir(X_PATH . '/attachments/' . $this->registry->user['username']);
		$olddir = getcwd();
		$attaches = '';
		$attach = array();
		chdir(X_PATH . '/attachments/' . $this->registry->user['username']);
		foreach ($files as $fileid => $file)
		{
			if ($files[$fileid] == '.' || $files[$fileid] == '..')
			{
				// Do nothing
			} else
			{
				$filename = $files[$fileid];
				$files[$fileid] = str_replace('.', '__', $files[$fileid]);

				$attach['aid'] = $files[$fileid];
				$attach['filename'] = $files[$fileid];
				$attach['filesize'] = round(filesize($files[$fileid]));
				$attach['shortname'] = substr($attach['filename'], 0, 20) . '...';
				$attach['filetype'] = filetype($filename);
				$attach['uploaded'] = $this->registry->xmbtime('d-m-Y \a\t h:i a', filectime($filename));
				print_r($attach);
				$this->registry->view->setvar('attach', $attach);
				$attaches .= $this->registry->view->loadtovar('my_attachments_attachment',
					'attachment');
			}
		}
		chdir($olddir);

		$myattaches = $this->registry->db->prepare("
			SELECT * FROM " . X_PREFIX . "attachments 
			WHERE uid = :uid
		");
		$myattaches->execute(array(':uid' => $this->registry->user['uid']));

		if ($myattaches->rowCount() > 0)
		{
			foreach ($myattaches as $attach)
			{
				$attach['uploaded'] = $this->registry->xmbtime('d-m-Y \a\t h:i a', $attach['dateline']);
				$attach['filesize'] = round($attach['filesize'] / 1024);
				$attach['shortname'] = substr($attach['filename'], 0, 20) . '...';
				$this->registry->view->setvar('attach', $attach);
				$attaches .= $this->registry->view->loadtovar('my_attachments_attachment',
					'attachment');
			}
		}
		$this->registry->view->setvar('myattachments', $attaches);

		$this->registry->usercp_method = 'myattachments';

		$content = $this->registry->view->loadtovar($this->registry->usercp_method, $this->
			registry->controller);

		$this->registry->view->setvar('content', $content);
	}

	public function image($id)
	{
		$id = str_replace('__', '.', $id);

		$this->registry->options['attach_system'] = 'file';

		if ($this->registry->options['attach_system'] == 'db')
		{
			$image = $this->registry->db->prepare("
			SELECT aid, filetype, attachment FROM " . X_PREFIX . "attachments 
			WHERE aid = :aid
		");
			$image->execute(array(':aid' => $id));
			$image = $image->fetch();

			if ($image['aid'] > 0)
			{
				header('Content-type: ' . $image['filetype']);
				header('Content-length: ' . $image['filesize']);
				$expires = $this->registry->xmbtime('D, j M Y h:i:s e', X_TIME + (60 * 60 * 2));
				header("Pragma: public");
				header('Cache-Control: maxage=' . (60 * 60 * 2));
				header("Expires: $expires");

				echo $image['attachment'];
			}
		} else
		{
			if (is_dir(X_PATH . '/attachments/' . $this->registry->user['username']))
			{
				if (is_file(X_PATH . '/attachments/' . $this->registry->user['username'] . '/' .
					$id))
				{
					$file = fopen(X_PATH . '/attachments/' . $this->registry->user['username'] . '/' .
						$id);
					$filesize = filesize(X_PATH . '/attachments/' . $this->registry->user['username'] .
						'/' . $id);
					$filetype = filetype(X_PATH . '/attachments/' . $this->registry->user['username'] .
						'/' . $id);
					$contents = fread($file, $filesize);

					header('Content-type: ' . $filetype);
					header('Content-length: ' . $filesize);
					$expires = $this->registry->xmbtime('D, j M Y h:i:s e', X_TIME + (60 * 60 * 2));
					header("Pragma: public");
					header('Cache-Control: maxage=' . (60 * 60 * 2));
					header("Expires: $expires");

					echo $contents;
				}
			}
		}
	}

	public function download($id)
	{
		$id = intval($id);

		$download = $this->registry->db->prepare("
			SELECT * FROM " . X_PREFIX . "attachments
			WHERE aid = :aid
		");
		$download->execute(array(':aid' => $id));
		$download = $download->fetch();

		if ($download['aid'] > 0)
		{
			header('Content-type: ' . $download['filetype']);
			header('Content-Transfer-Encoding: binary');
			header('Content-Length: ' . $download['filesize'] . ';\n');
			header('Content-Disposition: attachment; filename=' . $download['filename'] .
				';');
			ob_start();
			echo $download['attachment'];
			ob_end_flush();
			ob_end_clean();
		}
	}
}

?>