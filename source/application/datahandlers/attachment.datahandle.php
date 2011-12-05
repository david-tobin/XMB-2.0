<?php

if (!defined('IN_CODE'))
{
	die('You cannot run this file directly');
}
/**
 * XMB_Datahandler_Attachment
 * 
 * @package   
 * @author XMB20x
 * @copyright HobKnockerExpress
 * @version 2010
 * @access public
 */
class XMB_Datahandler_Attachment extends XMB_Datahandler
{
	public $files = array();

	private $fileid = 0;

	private $system = '';

	/**
	 * XMB_Datahandler_Attachment::__construct()
	 * 
	 * @param mixed $registry
	 * @return
	 */
	public function __construct($registry)
	{
		parent::__construct($registry);

		// Set System
		switch ($this->registry->options['attachment_system'])
		{
			case 'file':
				$this->system = 'file';
				break;

			case 'db':
				$this->system = 'db';
				break;

			default:
				$this->system = 'file';
				break;
		}
	}

	/**
	 * XMB_Datahandler_Attachment::set_info()
	 * 
	 * @param mixed $type
	 * @param mixed $value
	 * @return
	 */
	public function set_info($type, $value)
	{
		switch ($type)
		{
			case 'name':
				$this->files[$this->fileid]['filename'] = ($value != '') ? $value:
				X_TIME;
				$this->files[$this->fileid]['uid'] = $this->registry->user['uid'];
				$this->files[$this->fileid]['dateline'] = X_TIME;
				break;

			case 'data':
				$this->files[$this->fileid]['data'] = $value;
				break;

			case 'size':
				$this->files[$this->fileid]['size'] = $value;
				break;

			case 'type':
				$this->files[$this->fileid]['type'] = $value;
				break;

			case 'pid':
				$this->files[$this->fileid]['pid'] = intval($value);
				break;
		}
	}

	/**
	 * XMB_Datahandler_Attachment::break_file()
	 * 
	 * @return
	 */
	public function break_file()
	{
		$this->fileid++;
	}

	/**
	 * XMB_Datahandler_Attachment::build()
	 * 
	 * @return
	 */
	public function build()
	{
		$this->perform_checks();

		$upload = $this->prepare_upload_file();

		foreach ($this->files as $file)
		{
			if ($this->system == 'db')
			{
				$this->upload_file($file, $upload);
			} else
			{
				$this->upload_file_filesystem($file);
			}
		}

		$this->fileid++;

		return 1;
	}

	/**
	 * XMB_Datahandler_Attachment::perform_checks()
	 * 
	 * @return
	 */
	private function perform_checks()
	{
		if (empty($this->files))
		{
			exit();
		}

		$this->registry->csrf_protection();

		foreach ($this->files as $fileid => $file)
		{
			if (empty($file['filename']))
			{
				$this->registry->xmberror('attach_filename_missing');
				exit();
			} else
				if (empty($file['data']))
				{
					$this->registry->xmberror('attach_data_empty');
					exit();
				} else
					if (empty($file['size']))
					{
						$this->registry->xmberror('attach_filesize_missing');
						exit();
					} else
						if (empty($file['type']))
						{
							$this->registry->xmberror('attach_filetype_missing');
						}
		}
	}

	/**
	 * XMB_Datahandler_Attachment::get_temp_file()
	 * 
	 * @param mixed $location
	 * @return
	 */
	public function get_temp_file($location)
	{
		if (file_exists($location))
		{
			$data = (file_get_contents($location) == false) ? '' : file_get_contents($location);

			if ($data == '')
			{
				$filehandle = fopen($location, 'r');

				$data = fread($filehandle, filesize($location));
				fclose($filehandle);
			}

			return $data;
		} else
		{
			return false;
		}
	}

	/**
	 * XMB_Datahandler_Attachment::get_file()
	 * 
	 * @param mixed $fileids
	 * @return
	 */
	public function get_file($fileids = array())
	{

	}

	/**
	 * XMB_Datahandler_Attachment::delete_file()
	 * 
	 * @param mixed $fileids
	 * @return
	 */
	public function delete_file($fileids = array())
	{

	}

	/**
	 * XMB_Datahandler_Attachment::prepare_upload_file()
	 * 
	 * @return
	 */
	private function prepare_upload_file()
	{
		$upload = $this->registry->db->prepare("
			INSERT INTO " . X_PREFIX .
			"attachments (pid, filename, filetype, filesize, attachment, downloads, dateline, uid)
			VALUES (:pid, :filename, :filetype, :filesize, :data, 0, " . X_TIME .
			", :uid)
			");

		return $upload;
	}

	/**
	 * XMB_Datahandler_Attachment::upload_file_filesystem()
	 * 
	 * @param mixed $file
	 * @return
	 */
	public function upload_file_filesystem($file)
	{
		if (is_writeable(X_PATH . '/attachments/'))
		{
			if (!is_dir(X_PATH . '/attachments/' . $this->registry->user['username'] . '/'))
			{
				mkdir(X_PATH . '/attachments/' . $this->registry->user['username'] . '/', '0777');
			}

			if (!$attach = fopen(X_PATH . '/attachments/' . $this->registry->user['username'] .
				'/' . $file['filename'], 'w'))
				$this->registry->xmberror('attach_file_noread');
			if (fwrite($attach, $file['data']) == false)
				$this->registry->xmberror('attach_write_fail');
			fclose($attach);
		} else
		{
			$this->registry->xmberror('attach_dir_unwriteable');
		}
	}

	/**
	 * XMB_Datahandler_Attachment::upload_file_db()
	 * 
	 * @param mixed $file
	 * @param mixed $query
	 * @return
	 */
	public function upload_file_db($file, $query)
	{
		$query->execute(array(':pid' => $file['pid'], ':filename' => $file['filename'],
			':filetype' => $file['type'], ':filesize' => $file['size'], ':data' => $file['data'],
			':uid' => $this->registry->user['uid']));
		$aid = $this->registry->db->lastInsertId();

		$post = $this->registry->db->prepare("
			SELECT attachments FROM " . X_PREFIX . "posts
			WHERE pid = :pid
		");

		$post->execute(array(':pid' => $file['pid']));

		if ($post->rowCount() > 0)
		{
			$post = $post->fetch();
			$attachments = $post['attachments'] . ',' . $aid;

			$postup = $this->registry->db->prepare("
				UPDATE " . X_PREFIX . "posts
				SET attachments = :attachments
				WHERE pid = :pid
			");

			$postup->execute(array(':attachments' => $attachments, ':pid' => $file['pid']));
		}
	}

	/**
	 * XMB_Datahandler_Attachment::handle_files()
	 * 
	 * @return
	 */
	public function handle_files()
	{

	}

	/**
	 * XMB_Datahandler_Attachment::delete_attachment()
	 * 
	 * @param mixed $aid
	 * @return
	 */
	public function delete_attachment($aid)
	{
		if ($this->system == 'db')
		{
			$delete = $this->registry->db->prepare("
			DELETE FROM " . X_PREFIX . "attachments
			WHERE aid = :aid && uid = :uid
		");
			$delete->execute(array(':aid' => $aid, ':uid' => $this->registry->user['uid']));
		} else
		{
			// @TODO Filesystem delete attachment!
		}
	}
}
