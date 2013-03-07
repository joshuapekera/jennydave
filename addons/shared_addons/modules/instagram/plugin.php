<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Instagram API and content for PyroCMS.
 *
 * @author 		Zac Vineyard
 * @website		http://zacvineyard.com
 * @package 	PyroCMS
 * @subpackage 	Instagram Module
 */

class Plugin_Instagram extends Plugin
{
	/**
	 * Item List
	 * Usage:
	 * 
	 * {{ sample:items limit="5" order="asc" }}
	 *      {{ id }} {{ name }} {{ slug }}
	 * {{ /sample:items }}
	 *
	 * @return	array
	 */
	function items()
	{
		$limit = $this->attribute('limit');
		$order = $this->attribute('order');
		
		return $this->db->order_by('name', $order)
						->limit($limit)
						->get('sample_items')
						->result_array();
	}

	function popular()
	{
		$this->load->library('Instagram_api');

		$output = array();
		$popular_media = $this->instagram_api->getPopularMedia();
		foreach($popular_media->data as $media)
		{
			$output[] = array(
				'username' => $media->user->username,
				'user_id' => $media->user->id,
				'user_profile_pic' => $media->user->profile_picture,
				'url' => $media->images->standard_resolution->url,
				'thumb_url' =>$media->images->thumbnail->url
			);
		}
		return $output;
	}
}

/* End of file plugin.php */