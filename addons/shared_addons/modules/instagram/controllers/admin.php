<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Instagram API and content for PyroCMS.
 *
 * @author 		Zac Vineyard
 * @website		http://zacvineyard.com
 * @package 	PyroCMS
 * @subpackage 	Instagram Module
 */

class Admin extends Admin_Controller
{
	public function __construct()
	{
		parent::__construct();

		// Load all the required classes
		$this->load->model('instagram_m');
		$this->lang->load('instagram');
		$this->load->library('Instagram_api');

		$this->instagram_api->access_token = $this->session->userdata('instagram-token');

		// We'll set the partials and metadata here since they're used everywhere
		$this->template->append_js('module::admin.js')
			->append_css('module::admin.css');
	}

	/**
	 * Home
	 */
	public function index() 
	{
		$recent = $this->instagram_api->getUserRecent('30979615');
		print_r($recent);
		print_r($this->session->userdata('instagram-token'));

		$this->template->title($this->module_details['name'])
			->build('admin/form');
	}

	/**
	 * List all items
	 */
	public function items()
	{
		$data['popular_media'] = $this->instagram_api->getPopularMedia();

		// Build the view with sample/views/admin/items.php
		$this->template->title($this->module_details['name'])
			->build('admin/items',$data);
	}

	/**
	 * Run the authentication
	 */
	public function callback()
	{
		// The API callback URL for back-end authorization
		if(isset($_GET['code']) && $_GET['code'] != '')
		{	
			$auth_response = $this->instagram_api->authorize($_GET['code']);

			// Set up session variables containing some useful Instagram data
			$sess_data = array(
				'instagram-token' => $auth_response->access_token,
				'instagram-username' => $auth_response->user->username,
				'instagram-profile-picture' => $auth_response->user->profile_picture,
				'instagram-user-id' => $auth_response->user->id,
				'instagram-full-name' => $auth_response->user->full_name,
			);
			$this->session->set_userdata($sess_data);
			
			$this->session->set_flashdata('success', 'You have succesfully signed in with Instagram.');
			redirect('/admin/instagram');
		}
		else
		{	
			$this->session->set_flashdata('error', 'Something went wrong. Please try again.');
			redirect('/admin/instagram');
		}
	}
}