<?php defined('BASEPATH') or exit('No direct script access allowed');

class Module_Instagram extends Module {

	public $version = '1.0';

	public function info()
	{
		return array(
			'name' => array(
				'en' => 'Instagram'
			),
			'description' => array(
				'en' => 'Instagram API and content for PyroCMS.'
			),
			'frontend' => true,
			'backend' => true,
			'menu' => 'content', // You can also place modules in their top level menu. For example try: 'menu' => 'Sample',
			'sections' => array(
				'authorize' => array(
					'name' => 'instagram:authentication',
					'uri' => 'admin/instagram'
				),
				//'endpoints' => array(
					//'name' 	=> 'instagram:endpoints', // These are translated from your language file
					//'uri' 	=> 'admin/instagram/endpoints'
				//),
				'realtime' => array(
					'name' => 'instagram:realtime',
					'uri' => 'admin/instagram/realtime',
					'shortcuts' => array(
						'create' => array(
							'name' 	=> 'instagram:create',
							'uri' 	=> 'admin/instagram/create',
							'class' => 'add'
						)
					)
				)
			)
		);
	}

	public function install()
	{
		$instagram_settings = array(
			array(
				'slug' => 'instagram_client_id',
				'title' => 'Client ID',
				'description' => 'The ID of your Instagram client.',
				'`default`' => '',
				'`value`' => '',
				'type' => 'text',
				'`options`' => '',
				'is_required' => 0,
				'is_gui' => 1,
				'module' => 'instagram'
			),
			array(
				'slug' => 'instagram_client_secret',
				'title' => 'Client Secret',
				'description' => 'The secret you should not share with anyone.',
				'`default`' => '',
				'`value`' => '',
				'type' => 'text',
				'`options`' => '',
				'is_required' => 0,
				'is_gui' => 1,
				'module' => 'instagram'
			),
			array(
				'slug' => 'instagram_website_url',
				'title' => 'Website URL',
				'description' => 'Usually the base URL of your site.',
				'`default`' => '',
				'`value`' => '',
				'type' => 'text',
				'`options`' => '',
				'is_required' => 0,
				'is_gui' => 1,
				'module' => 'instagram'
			),
			array(
				'slug' => 'instagram_redirect_uri',
				'title' => 'Redirect URI',
				'description' => 'The redirect_uri specifies where we redirect users after they have chosen whether or not to authenticate your application.',
				'`default`' => '',
				'`value`' => '',
				'type' => 'text',
				'`options`' => '',
				'is_required' => 0,
				'is_gui' => 1,
				'module' => 'instagram'
			)
		);
		$this->db->insert_batch('settings', $instagram_settings);

		// Add table 'instagram_subscriptions'
		$fields = array(
	        'id' => array(
				'type' => 'INT',
				'constraint' => 8, 
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
	        'object' => array(
				'type' => 'VARCHAR',
				'constraint' => '50',
			),
	        'slug' => array(
				'type' => 'VARCHAR',
				'constraint' => '50',
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', TRUE);
		if(!$this->dbforge->create_table('instagram_subscriptions'))
		{
			return false;
		}

		// Add table 'instagram_posts'
		$fields = array(
	        'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
	        'changed_aspect' => array(
				'type' => 'VARCHAR',
				'constraint' => '50',
			),
	        'subscription_id' => array(
				'type' => 'VARCHAR',
				'constraint' => '50',
			),
	        'object' => array(
				'type' => 'VARCHAR',
				'constraint' => '50',
			),
	        'object_id' => array(
				'type' => 'VARCHAR',
				'constraint' => '50',
			),
	        'time' => array(
				'type' => 'INT',
				'constraint' => 11,
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', TRUE);
		if(!$this->dbforge->create_table('instagram_posts'))
		{
			return false;
		}

		// Add table 'instagram_media'
		$fields = array(
	        'id' => array(
				'type' => 'INT',
				'constraint' => 11,
				'unsigned' => TRUE,
				'auto_increment' => TRUE
			),
	        'media_id' => array(
				'type' => 'VARCHAR',
				'constraint' => '50',
			),
	        'user_id' => array(
				'type' => 'INT',
				'constraint' => 11,
			),
	        'thumbnail' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
			),
	        'low_resolution' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
			),
	        'standard_resolution' => array(
				'type' => 'VARCHAR',
				'constraint' => '100',
			),
	        'tags' => array(
				'type' => 'TEXT',
				'null' => TRUE
			),
	        'caption' => array(
				'type' => 'TEXT',
				'null' => TRUE
			),
	        'created_time' => array(
				'type' => 'INT',
				'constraint' => 11,
			)
		);
		$this->dbforge->add_field($fields);
		$this->dbforge->add_key('id', TRUE);
		if(!$this->dbforge->create_table('instagram_media'))
		{
			return false;
		}

		return true;
	}

	public function uninstall()
	{
		$this->db->delete('settings', array('module' => 'instagram'));
		$this->dbforge->drop_table('instagram_subscriptions');
		$this->dbforge->drop_table('instagram_posts');
		$this->dbforge->drop_table('instagram_media');
		/*
		$this->db->delete('settings', array('module' => 'sample'));
		{
			return TRUE;
		}
		*/
		return true;
	}


	public function upgrade($old_version)
	{
		// Your Upgrade Logic
		return true;
	}

	public function help()
	{
		// Return a string containing help info
		// You could include a file and return it here.
		return "No documentation has been added for this module.<br />Contact the module developer for assistance.";
	}
}
/* End of file details.php */
