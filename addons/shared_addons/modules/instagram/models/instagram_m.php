<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Instagram API and content for PyroCMS.
 *
 * @author 		Zac Vineyard
 * @website		http://zacvineyard.com
 * @package 	PyroCMS
 * @subpackage 	Instagram Module
 */

class Instagram_m extends MY_Model {

	public function __construct()
	{		
		parent::__construct();
	}

	public function get_settings()
	{
		$output = array();
		$query = $this->db->where('module', $this->module)->get('settings');
		if($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[$row->slug] = $row->value;
			}
		}
		return $data;
	}

	public function get_token($slug)
	{
		$this->db->select('instagram_subscriptions.user_id,token')
			->from('instagram_subscriptions')
			->where('slug', $slug)
			->join('instagram_users', 'instagram_subscriptions.user_id = instagram_users.user_id')
			->limit(1);
		$query = $this->db->get();
		if($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}

	public function get_remote_subscriptions($client_secret,$client_id)
	{
		$url = "https://api.instagram.com/v1/subscriptions?client_secret=".$client_secret."&client_id=".$client_id;
		$subscriptions = json_decode(file_get_contents($url));
		$subs = array();
		foreach($subscriptions->data as $obj)
		{
			$subs[] = array(
				'object' => $obj->object,
				'object_id' => $obj->object_id,
				'aspect' => $obj->aspect,
				'callback_url' => $obj->callback_url,
				'type' => $obj->type,
				'id' => $obj->id
			);
		}
		return $subs;
	}

	public function get_tag_subscription($tag,$limit,$offset)
	{
		$this->db->order_by("created_time", "desc");
		$query = $this->db->get_where('instagram_media', array('object_id' => $tag), $limit, $offset);
		if($query->num_rows() > 0) {
			foreach ($query->result() as $row) {
				$data[] = $row;
			}
			return $data;
		}
	}
}