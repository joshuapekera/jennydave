<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Instagram API and content for PyroCMS.
 *
 * @author 		Zac Vineyard
 * @website		http://zacvineyard.com
 * @package 	PyroCMS
 * @subpackage 	Instagram Module
 */

class Instagram extends Public_Controller
{
	public function __construct()
	{
		parent::__construct();

		$this->load->library('Instagram_api');

		$this->instagram_api->access_token = $this->session->userdata('instagram-token');
	}

	public function index($tag = null)
	{
		if($tag == null)
		{
			redirect('/');
		}

		// Check to see if subscription exists using $tag
		$this->db->where('slug', $tag);
		$this->db->from('instagram_subscriptions');
		if($this->db->count_all_results() == 0)
		{
			// If subscription doesn't exist, return challenge
			if(isset($_GET['hub_challenge']) && !empty($_GET['hub_challenge']))
			{
				echo $_GET['hub_challenge'];
				die();
			}
		}
		else
		{
			// Verify data is from Instagram [NEEDED]

			// Handle data from subscription
			$posted_data = json_decode(file_get_contents("php://input"));
			foreach($posted_data as $post)
			{
				if($post->object == "user")
				{
					$recent = $this->instagram_api->getUserRecent($post->object_id);
					if($recent)
					{
						foreach($recent->data as $v)
						{
							$media_data = array(
								"media_id" => $v->id,
								"user_id" => $v->user->id,
								"thumbnail" => $v->images->thumbnail->url,
								"low_resolution" => $v->images->low_resolution->url,
								"standard_resolution" => $v->images->standard_resolution->url,
								"tags" => json_encode($v->tags),
								"caption" => $v->caption->text,
								"created_time" => $v->created_time
							);
							$this->db->insert('instagram_media',$media_data);
						}
					}
				}

				$insert_data[] = array(
					"changed_aspect" => $post->changed_aspect,
					"subscription_id" => $post->subscription_id,
					"object" => $post->object,
					"object_id" => $post->object_id,
					"time" => $post->time
				);
			}
			$this->db->insert_batch('instagram_posts',$insert_data);
		}
	}
}