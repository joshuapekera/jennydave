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
}