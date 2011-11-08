<section class="title">
	<h4><?php echo lang('newsletters.edit_title'); ?></h4>
</section>

<section class="item">
<?php echo form_open('admin/newsletters/edit/' . $newsletter->id, 'class="crud"'); ?>
	
	<ul>
		<li class="<?php echo alternator('', 'even'); ?>">
			<label for="title"><?php echo lang('newsletters.title_label');?></label>
			<br />
			<input type="text" id="title" name="title" maxlength="100" value="<?php echo $newsletter->title; ?>" class="text" />
			<span class="required-icon tooltip"><?php echo lang('required_label');?></span>
		</li>
		
		<hr>
			
		<li class="<?php echo alternator('', 'even'); ?>">
			<label for="newsletter_opens"><?php echo lang('newsletters.title_newsletter_opens');?></label>
			<?php echo form_checkbox('read_receipts', '1', @$newsletter->read_receipts); ?>
		</li>
		
		<hr>
		
		<li>
			<label><?php echo lang('newsletters.title_newsletter_urls');?></label>
			<?php echo image('add.png', 'newsletters', array('alt' => 'add', 'class' => 'url-add')); ?>
		</li>
		
		<?php if($newsletter->tracked_urls > ''): ?>
			<?php foreach($newsletter->tracked_urls AS $url => $hash): ?>
				<li class="<?php echo alternator('', 'even'); ?>">
					<?php echo lang('newsletters.target').': '; echo form_input('tracked_urls[target][]', $url, 'class="width-15 target" style="margin-right: 20px;"'); ?>
					<?php echo lang('newsletters.url').': '; echo form_input('tracked_urls[hash][]', $hash, 'class="width-15 url"'); ?>
					
					<label><?php echo lang('newsletters.title_newsletter_urls');?></label>
					<?php echo image('remove.png', 'newsletters', array('alt' => 'remove', 'class' => 'url-remove')); ?>
				</li>
			<?php endforeach; ?>
		<?php endif; ?>
		
		<li style="display:none;" class="<?php echo alternator('', 'even'); ?>">
			<?php echo lang('newsletters.target').': '; echo form_input('tracked_urls[target][]', set_value('tracked_urls[target][]'), 'class="width-15 target" style="margin-right: 20px;"'); ?>
			<?php echo lang('newsletters.url').': '; echo form_input('tracked_urls[hash][]', set_value('tracked_urls[hash][]'), 'class="width-15 url"'); ?>
			
			<?php echo image('remove.png', 'newsletters', array('alt' => 'remove', 'class' => 'url-remove')); ?>
		</li>
		
		<?php foreach($urls as $url): ?>
		<li  class="<?php echo alternator('', 'even'); ?>">
			<?php echo lang('newsletters.target').': '; echo form_input('tracked_urls[target][]', set_value('tracked_urls[target][]', $url->target), 'class="width-15 target" style="margin-right: 20px;"'); ?>
			<?php echo lang('newsletters.url').': '; echo form_input('tracked_urls[hash][]', set_value('tracked_urls[hash][]', rtrim(site_url(), '/') . '/newsletters/short/' . $url->hash), 'class="width-15 url"'); ?>
			
			<?php echo image('remove.png', 'newsletters', array('alt' => 'remove', 'class' => 'url-remove')); ?>
		</li>
		<?php endforeach; ?>
		
		<hr>
			
		<li class="<?php echo alternator('', 'even'); ?>">
			<label for="body"><?php echo lang('newsletters.body');?></label>
			<br />
			<?php echo form_textarea(array('id'=>'body', 'name'=>'body', 'value' => set_value('body', stripslashes($newsletter->body)), 'rows' => 40, 'class'=>'wysiwyg-advanced')); ?>
		</li>
	</ul>
	<div class="buttons float-left">
		<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
	</div>
<?php echo form_close(); ?>
</section>