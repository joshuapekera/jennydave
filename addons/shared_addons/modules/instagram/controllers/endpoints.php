<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Instagram API and content for PyroCMS.
 *
 * @author 		Zac Vineyard
 * @website		http://zacvineyard.com
 * @package 	PyroCMS
 * @subpackage 	Instagram Module
 */

class Endpoints extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();

		// Load all the required classes
		$this->load->model('instagram_m');
		$this->lang->load('instagram');
		$this->load->library('Instagram_api');

		// Set the instagram access token
		$this->instagram_api->access_token = $this->session->userdata('instagram-token');

		// We'll set the partials and metadata here since they're used everywhere
		$this->template->append_js('module::admin.js')
			->append_css('module::admin.css');
	}

	/**
	 * User profile
	 */
	function profile($user_id = FALSE)
	{
		if($user_id === FALSE) {
			$user_id = $this->session->userdata('instagram-user-id');
		}

		$profile_data = $this->instagram_api->getUser($user_id);
		print_r($profile_data);
		die();
	}

	/**
	 * Recent Tags
	 */
	function recent($tag = FALSE)
	{
		$tags_recent_data = $this->instagram_api->tagsRecent($tag);
		print_r($tags_recent_data);
		die();
	}

	/**
	 * Home
	 */
	public function index() 
	{
		$this->template->title($this->module_details['name'])
			->build('admin/endpoints');
	}
}