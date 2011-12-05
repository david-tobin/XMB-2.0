<?php

class faqModel extends Model
{
	/**
	 * 
	 * @param $id
	 */
	public function index($id)
	{
		$id = intval($id);

		if ($this->registry->cache->get_cache('faqs') == false)
		{
			$faqquery = $this->registry->db->prepare("
				SELECT * FROM " . X_PREFIX . "faqs
			");

			$faqs = array();

			if ($faqquery->rowCount() > 0)
			{
                $faqs = $faqquery->execute();
                
				foreach ($faqs AS $faqid => $faq)
				{
					$faqs[$faq['faqid']] = $faq;
				}
			} else {
				$this->registry->xmberror('faq_no_results');
			}

			$this->registry->cache->rebuild_cache('faqs', serialize($faqs));
		}

		$faqs = $this->registry->cache->get_cache('faqs');

		$this->registry->view->setvar('faqs', $faqs);
	}

	/**
	 * 
	 * @param $id
	 * @return unknown_type
	 */
	public function display($id)
	{

	}
}

?>