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
	 * Popular Instagram Posts
	 */
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

	/**
	 * Subscription Instagram Posts
	 */
	function tag_subscription()
	{
		$this->load->model('instagram_m');

		$tag = $this->attribute('tag');
		$limit = $this->attribute('limit');
		$offset = $this->attribute('offset');

		if(!$limit)
		{
			$limit = 20;
		}

		if(!$offset)
		{
			$offset = 0;
		}

		$output = array();
		$media_data = $this->instagram_m->get_tag_subscription($tag,$limit,$offset);
		foreach($media_data as $media)
		{
			$output[] = array(
				'user_id' => $media->user_id,
				'thumbnail' => $media->thumbnail,
				'low_resolution' => $media->low_resolution,
				'standard_resolution' => $media->standard_resolution,
				'caption' => $media->caption,
				'created_time' => $media->created_time
			);
		}
		return $output;
	}
}

/* End of file plugin.php */