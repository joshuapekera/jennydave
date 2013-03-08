<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Instagram API and content for PyroCMS.
 *
 * @author 		Zac Vineyard
 * @website		http://zacvineyard.com
 * @package 	PyroCMS
 * @subpackage 	Instagram Module
 */

class Realtime extends Admin_Controller
{
	private $instagram_settings;

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

		// See if logged into Instagram
		if(!$this->session->userdata('instagram-token'))
		{
			$this->session->set_flashdata('error', 'Please login to Instagram.');
			redirect('admin/instagram');
		}

		// Get settings
		$this->instagram_settings = $this->instagram_m->get_settings();
		if($this->instagram_settings['instagram_client_secret'] == "")
		{
			$this->session->set_flashdata('error', 'Please add your Instagram settings to the system.');
			redirect('admin/instagram');
		}
	}

	/**
	 * Home
	 */
	public function index() 
	{
		$data['subscriptions'] = $this->instagram_m->get_remote_subscriptions($this->instagram_settings['instagram_client_secret'],$this->instagram_settings['instagram_client_id']);

		$this->form_validation->set_rules('object', 'Object Type', 'trim|required');
		$this->form_validation->set_rules('slug', 'Slug', 'trim|required');

		if ($this->form_validation->run() == FALSE)
		{
			$this->template->title($this->module_details['name'])
				->build('admin/realtime',$data);
		}
		else
		{
			$this->subscribe($this->input->post('object'),$this->input->post('slug'));
		}
	}

	/**
	 * Add subscription
	 */
	public function add_subscription() 
	{
		$this->form_validation->set_rules('object', 'Object Type', 'trim|required');
		$this->form_validation->set_rules('slug', 'Slug', 'trim|required|is_unique[instagram_subscriptions.slug]');

		if ($this->form_validation->run() == FALSE)
		{
			$this->template->title($this->module_details['name'])
				->build('admin/add_subscription');
		}
		else
		{
			// See if subscription already exists
			if($this->subscribe($this->input->post('object'),$this->input->post('slug')))
			{
				// Strore subscription in DB
				$data = array(
					'user_id' => $this->session->userdata('instagram-user-id'),
					'object' => $this->input->post('object'),
					'slug' => $this->input->post('slug')
				);
				$this->db->insert('instagram_subscriptions', $data);

				$this->session->set_flashdata('success', 'Your subscription has been created.');
			}
			else
			{
				$this->session->set_flashdata('error', 'This subscription may already exist or the service was un-reachable.');
			}

			redirect('/admin/instagram/realtime');
		}
	}

	/**
	 * Real-time subscription
	 */
	function subscribe($object,$slug)
	{
		// API Info
		$client_id = $this->instagram_settings['instagram_client_id'];
		$client_secret = $this->instagram_settings['instagram_client_secret'];
		$object = $object; // tag,user
		$object_id = $slug;
		$aspect = 'media';
		$verify_token = '';
		$callback_url = base_url().'instagram/index/'.$slug;

		// cURL data
		if($object == 'user')
		{
			$attachment =  array(
				'client_id' => $client_id,
				'client_secret' => $client_secret,
				'object' => $object,
				'aspect' => $aspect,
				'verify_token' => $verify_token,
				'callback_url'=> $callback_url
			);
		}
		else
		{
			$attachment =  array(
				'client_id' => $client_id,
				'client_secret' => $client_secret,
				'object' => $object,
				'object_id' => $object_id,
				'aspect' => $aspect,
				'verify_token' => $verify_token,
				'callback_url'=> $callback_url
			);
		}

		$url = "https://api.instagram.com/v1/subscriptions/";

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $attachment);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);  // comment out to suppress the cURL output
		$result = curl_exec($ch);

		$return = json_decode($result);
		
		if((string) $return->meta->code == '200')
		{
			return $return->data->id;
		}
		else
		{
			return false;
		}
	}

	/**
	 * Delete Real-time subscription
	 */
	public function delete($id,$object,$object_id = null)
	{
		$url = "https://api.instagram.com/v1/subscriptions?client_secret=".$this->instagram_settings['instagram_client_secret']."&client_id=".$this->instagram_settings['instagram_client_id']."&id=".$id;
		$opts = array(
			'http' => array(
				'method' => 'DELETE'
			)
		);
		$context = stream_context_create($opts);
		$result = file_get_contents($url, false, $context);
		$return = json_decode($result);
		
		if((string) $return->meta->code == '200')
		{
			if($object == 'user')
			{
				$this->db->where("object","user");
			}
			else
			{
				$this->db->where("slug",$object_id);
			}
			if($this->db->delete('instagram_subscriptions'))
			{
				$this->session->set_flashdata('success', 'Your subscription has been deleted.');
			}
			else
			{
				$this->session->set_flashdata('error', 'This subscription has been deleted with Instagram, but still exists locally. You will need remove it manually.');
			}
		}
		else
		{
			$this->session->set_flashdata('error', 'This subscription could not be deleted with Instagram. Please try again.');
		}
		redirect('/admin/instagram/realtime');
	}
}