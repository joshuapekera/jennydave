<section class="title">
	<!-- We'll use $this->method to switch between sample.create & sample.edit -->
	<h4><?php echo lang('instagram:realtime'); ?></h4>
</section>

<section class="item">

	<div class="content">

		<?php echo form_open_multipart($this->uri->uri_string(), 'class="crud"'); ?>
			
			<div class="form_inputs">
		
			<ul>
				<li class="<?php echo alternator('', 'even'); ?>">
					<label for="name"><?php echo lang('instagram:object'); ?> <span>*</span></label>
					<div class="input"><?php echo form_dropdown('object', array('tag' => 'Tag','user' => 'User'), set_value('object')); ?></div>
				</li>

				<li class="<?php echo alternator('', 'even'); ?>">
					<label for="slug"><?php echo lang('instagram:slug'); ?> <span>*</span></label>
					<div class="input"><?php echo form_input('slug', set_value('slug'), 'class="width-15"'); ?></div>
				</li>
			</ul>
			
			</div>
			
			<div class="buttons">
				<?php $this->load->view('admin/partials/buttons', array('buttons' => array('save', 'cancel') )); ?>
			</div>
			
		<?php echo form_close(); ?>

	</div>

</section>