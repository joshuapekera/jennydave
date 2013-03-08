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

		// We'll set the partials and metadata here since they're used everywhere
		$this->template->append_js('module::admin.js')
			->append_css('module::admin.css');
	}

	/**
	 * Home
	 */
	public function index() 
	{
		$data['settings'] = $this->instagram_m->get_settings();
		$this->template->title($this->module_details['name'])
			->build('admin/form',$data);
	}

	/**
	 * Get popular Instagrm media
	 */
	public function popular()
	{
		$data['popular_media'] = $this->instagram_api->getPopularMedia();

		$this->template->title($this->module_details['name'])
			->build('admin/popular',$data);
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

			$token = $auth_response->access_token;
			$username = $auth_response->user->username;
			$picture = $auth_response->user->profile_picture;
			$user_id = $auth_response->user->id;

			// Set up session variables containing some useful Instagram data
			$insert_data = array(
				'token' => $token,
				'username' => $username,
				'picture' => $picture,
				'user_id' => $user_id
			);

			$sess_data = array(
				'instagram-token' => $token,
				'instagram-username' => $username,
				'instagram-profile-picture' => $picture,
				'instagram-user-id' => $user_id
			);

			$query = $this->db->select('user_id')->where('user_id', $user_id)->get('instagram_users');
			if($query->num_rows() == 0)
			{
				$this->db->insert('instagram_users', $insert_data);
			}

			// Set settion data
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