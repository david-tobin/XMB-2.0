<?php

class miscModel extends Model
{
	/**
	 * miscModel::index()
	 * 
	 * @param mixed $id
	 * @return
	 */
	public function index($id)
	{
		// Do Nothing
	}

	/**
	 * miscModel::offline()
	 * 
	 * @param mixed $id
	 * @return
	 */
	public function offline($id)
	{
		if (!empty($this->registry->notice)) {
			$notice = $this->registry->notice;

			$this->registry->view->setvar('notice', $notice);
			$notices = $this->registry->view->loadtovar('notice', 'global');

			$this->registry->view->setvar('notices', $notices);
		}
	}

	/**
	 * miscModel::error()
	 * 
	 * @param mixed $id
	 * @deprecated
	 * @return
	 */
	public function error($id)
	{
		if (isset($id) && !empty($id)) {
			$this->registry->view->setvar('error', $this->registry->phrases[$id]);
		} else {
			$this->registry->view->setvar('error', $this->registry->phrases['unknown_error']);
		}
	}
}

?>